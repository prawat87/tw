<?php
  
namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
   
class PayPalController extends Controller
{
    public function paymentSetting()
    {
        $payment_setting = Utility::payment_settings();
        
        config(
            [
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]
        );
        return $payment_setting;
    }
    

    public function planPayWithPaypal(Request $request)
    {
        $this->paymentSetting();
       // dd($this->paymentSetting());

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $authuser       = Auth::user();
        $coupons_id ='';
        if($plan)
        {
            $plan->discounted_price = false;
            $price                  = $plan->price;
            if(isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if($usedCoupun >= $coupons->limit)
                    {
                        return Utility::error_res( __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                }
                else
                {
                    return Utility::error_res( __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    if(!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '')
                    {
                        try
                        {
                            $authuser->cancel_subscription($authuser->id);
                        }
                        catch(\Exception $exception)
                        {
                            \Log::debug($exception->getMessage());
                        }
                    }

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price==null?0:$price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => 'Paytm',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 2;
                    return $res;
                }
                else
                {
                    return Utility::error_res( __('Plan fail to upgrade.'));
                }
            }


          //  $call_back = route('plan.paytm',[$request->plan_id,'_token='.csrf_token()]);

            

            $paypalToken = $provider->getAccessToken();
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('plan.get.payment.status', [ $plan->id, $price]),
                    "cancel_url" => route('plan.get.payment.status', [ $plan->id, $price ]),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => Utility::getValByName('site_currency'),
                            "value" => $price,
                        ]
                    ]
                ]
            ]);
            // dd($response);
            if (isset($response['id']) && $response['id'] != null) {
                // redirect to approve href
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()
                    ->route('plans.index')
                    ->with('error', 'Something went wrong.');
            } else {
                return redirect()
                    ->route('plans.index')
                    ->with('error', $response['message'] ?? 'Something went wrong.');
            }
        
        } else {
                return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }
   
    public function planGetPaymentStatus(Request $request,$plan_id)
    {
       
        $this->paymentSetting();

        $user = Auth::user();
        $plan = Plan::find($plan_id);

        if($plan)
        {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $userCoupon         = new UserCoupon();
                            $userCoupon->user_id   = $user->id;
                            $userCoupon->coupon_id = $coupons->id;
                            $userCoupon->order_id  = $orderID;
                            $userCoupon->save();

                            $usedCoupun = $coupons->used_coupon();
                            if($coupons->limit <= $usedCoupun)
                            {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }
                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $plan->price;
                   // $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($request->TXNID) ? $request->TXNID : '';
                    $order->payment_type   = __('PAYPAL');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();

                    $assignPlan = $user->assignPlan($plan->id);
                    // dd($assignPlan);
                    if($assignPlan['is_success'])
                    {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    }
                    else
                    {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }
                
        }
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }
    public function clientPayWithPaypal(Request $request, $invoice_id)
    {
        $this->paymentSetting();

        $settings = Utility::settings();

        $get_amount = $request->amount;

        $request->validate(['amount' => 'required|numeric|min:0']);

        $invoice = Invoice::find($invoice_id);
        $user = User::where('id',$invoice->created_by)->first();

        if($invoice)
        {
            if($get_amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {
                $this->paymentSetting();
                $name = $settings['company_name'] . " - " . Utility::invoiceNumberFormat($invoice->invoice_id);

                $provider = new PayPalClient;
                $provider->setApiCredentials(config('paypal'));

                $paypalToken = $provider->getAccessToken();
                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('client.get.payment.status',[$invoice->id,$get_amount]),
                        "cancel_url" =>  route('client.get.payment.status',[$invoice->id,$get_amount]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => Utility::getValByName('site_currency'),
                                "value" => $get_amount
                            ]
                        ]
                    ]
                ]);

                if (isset($response['id']) && $response['id'] != null) 
                {
                    // redirect to approve href
                    foreach ($response['links'] as $links) 
                    {
                        if ($links['rel'] == 'approve') 
                        {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('invoice.show', \Crypt::encrypt($invoice->id))
                        ->with('error', 'Something went wrong.');
                } 
                else 
                {
                    return redirect()
                        ->route('invoice.show', \Crypt::encrypt($invoice->id))
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }
            }
            return redirect()->route('invoice.show',\Crypt::encrypt($invoice_id))->back()->with('error', __('Unknown error occurred'));
        } 
        else 
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
   
    public function clientGetPaymentStatus(Request $request,$invoice_id,$get_amount)
    {
        $this->paymentSetting();

        $invoice = Invoice::find($invoice_id);
        $user = User::where('id',$invoice->created_by)->first();

        if($invoice)
        {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
           // dd($response);
            $payment_id = Session::get('paypal_payment_id');


            if (isset($response['status']) && $response['status'] == 'COMPLETED') 
            {
                if ($response['status'] == 'COMPLETED') 
                {
                    $statuses = 'success';
                }
                $invoice_payment = new InvoicePayment();
                $invoice_payment->transaction_id =  app('App\Http\Controllers\InvoiceController')->transactionNumber($user);
                $invoice_payment->invoice_id = $invoice->id;
                $invoice_payment->amount = $get_amount;
                $invoice_payment->date = date('Y-m-d');
                $invoice_payment->payment_id = 0;
                $invoice_payment->payment_type = __('PAYPAL');
                $invoice_payment->client_id = $user->id;
                $invoice_payment->notes = '';
                $invoice_payment->save();
                     
                if(($invoice->getDue() - $invoice_payment->amount) == 0) 
                {
                    $invoice->status = 'paid';

                    $invoice->save();
                }

                if(\Auth::check())
                {
                    return redirect()->route('invoices.show', $invoice_id)->with('success', __('Payment added Successfully'));
                }
                else
                {
                    return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Payment added Successfully'));
                }

            }
            else
            {
                if(\Auth::check())
                {
                    return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction has been '));
                }
                else
                {
                    return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been '));
                }
            }
                
        } 
        else 
        {
            if(\Auth::check())
            {
                return redirect()->back()->with('error',__('Permission denied.'));
            }
            else
            {
                return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error',__('Permission denied.'));
            }
        }
    }

    // public function clientPayWithPaypal(Request $request, $invoice_id)
    // {
    //     $this->paymentSetting();
      
    //     $settings = Utility::settings();

    //     $get_amount = $request->amount;

    //     $request->validate(['amount' => 'required|numeric|min:0']);

    //     $invoice = Invoice::find($invoice_id);
    //     $user = User::where('id',$invoice->created_by)->first();
            
    //     if($invoice)
    //     {
    //         if($get_amount > $invoice->getDue())
    //         {
    //             return redirect()->back()->with('error', __('Invalid amount.'));
    //         }
    //         else
    //         {
    //             $this->paymentSetting();

    //             $provider = new PayPalClient;
    //             $provider->setApiCredentials(config('paypal'));

    //             $name = $settings['company_name'] . " - " . Utility::invoiceNumberFormat($invoice->invoice_id);

    //             $paypalToken = $provider->getAccessToken();
    //             $response = $provider->createOrder([
    //                 "intent" => "CAPTURE",
    //                 "application_context" => [
    //                     "return_url" => route('client.get.payment.status', [$invoice->id]),
    //                     "cancel_url" => route('client.get.payment.status', [$invoice->id]),
    //                 ],
    //                 "purchase_units" => [
    //                     0 => [
    //                         "amount" => [
    //                             "currency_code" => Utility::getValByName('site_currency'),
    //                             "value" => $get_amount,
    //                         ]
    //                     ]
    //                 ]
    //             ]);

        
    //             //     if(\Config::get('app.debug'))
    //             //     {
    //             //         return redirect()->route('invoices.show', $invoice_id)->with('error', __('Connection timeout'));
    //             //     }
    //             //     else
    //             //     {
    //             //         return redirect()->route('invoices.show', $invoice_id)->with('error', __('Some error occur, sorry for inconvenient'));
    //             //     }
    
    //             // foreach($payment->getLinks() as $link)
    //             // {
    //             //     if($link->getRel() == 'approval_url')
    //             //     {
    //             //         $redirect_url = $link->getHref();
    //             //         break;
    //             //     }
    //             // }
    //             // Session::put('paypal_payment_id', $payment->getId());
    //             // if(isset($redirect_url))
    //             // {
    //             //     return Redirect::away($redirect_url);
    //             // }

    //             if (isset($response['id']) && $response['id'] != null) {
    //                 // redirect to approve href
    //                 foreach ($response['links'] as $links) {
    //                     if ($links['rel'] == 'approve') {
    //                         return redirect()->away($links['href']);
    //                     }
    //                 }
    //                 return redirect()
    //                     ->route('plans.index')
    //                     ->with('error', 'Something went wrong.');
    //             } else {
    //                 return redirect()
    //                     ->route('plans.index')
    //                     ->with('error', $response['message'] ?? 'Something went wrong.');
    //             }

    //             return redirect()->route('invoices.show', $invoice_id)->with('error', __('Unknown error occurred'));
    //         }
    //     }
    //     else
    //     {
    //         return redirect()->back()->with('error', __('Permission denied.'));
    //     }
    // }

    // public function clientGetPaymentStatusa(Request $request, $invoice_id)
    // {
    //     $this->paymentSetting();
    //  //   dd($this->paymentSetting());

    //     $invoice = Invoice::find($invoice_id);
    //       $user = User::where('id',$invoice->created_by)->first();
    //     if($invoice)
    //     {
    //         $provider = new PayPalClient;
    //         $provider->setApiCredentials(config('paypal'));
    //         $provider->getAccessToken();
    //         $response = $provider->capturePaymentOrder($request['token']);
    //         $payment_id = Session::get('paypal_payment_id');
            
    //         if (isset($response['status']) && $response['status'] == 'COMPLETED') 
    //         {
    //             $invoice_payment = new InvoicePayment();
    //             $invoice_payment->transaction_id =  app('App\Http\Controllers\InvoiceController')->transactionNumber($user);
    //             $invoice_payment->invoice_id = $invoice->id;
    //             $invoice_payment->amount = $result['transactions'][0]['amount']['total'];
    //             $invoice_payment->date = date('Y-m-d');
    //             $invoice_payment->payment_id = 0;
    //             $invoice_payment->payment_type = __('PAYPAL');
    //             $invoice_payment->client_id = $user->id;
    //             $invoice_payment->notes = '';

    //             $invoice_payment->save();
                     
    //             if(($invoice->getDue() - $invoice_payment->amount) == 0) {

    //                 $invoice->status = 'paid';

    //                 $invoice->save();
    //             }
    //             return redirect()->route('invoices.show', $invoice_id)->with('success', __('Payment added Successfully'));

    //         } else {

    //             return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Payment added Successfully'));
    //         }
    //     //     $this->setApiContext();

    //     //     $payment_id = Session::get('paypal_payment_id');

    //     //     Session::forget('paypal_payment_id');

    //     //     if(empty($request->PayerID || empty($request->token)))
    //     //     {
    //     //         return redirect()->route('invoices.show', $invoice_id)->with('error', __('Payment failed'));
    //     //     }

    //     //     $payment   = Payment::get($payment_id, $this->_api_context);

    //     //     $execution = new PaymentExecution();
    //     //     $execution->setPayerId($request->PayerID);

    //     //     try
    //     //     {
    //     //         $result = $payment->execute($execution, $this->_api_context)->toArray();

    //     //         $status = ucwords(str_replace('_', ' ', $result['state']));

    //     //         if($result['state'] == 'approved')
    //     //         {
    //     //             $invoice_payment = new InvoicePayment();
    //     //             $invoice_payment->transaction_id =  app('App\Http\Controllers\InvoiceController')->transactionNumber($user);
    //     //             $invoice_payment->invoice_id = $invoice->id;
    //     //             $invoice_payment->amount = $result['transactions'][0]['amount']['total'];
    //     //             $invoice_payment->date = date('Y-m-d');
    //     //             $invoice_payment->payment_id = 0;
    //     //             $invoice_payment->payment_type = __('PAYPAL');
    //     //             $invoice_payment->client_id = $user->id;
    //     //             $invoice_payment->notes = '';

    //     //             $invoice_payment->save();
                     
    //     //             if(($invoice->getDue() - $invoice_payment->amount) == 0) {

    //     //                 $invoice->status = 'paid';

    //     //                 $invoice->save();
    //     //             }

    //     //             if(\Auth::check())
    //     //             {
    //     //                 return redirect()->route('invoices.show', $invoice_id)->with('success', __('Payment added Successfully'));
    //     //             }
    //     //             else
    //     //             {
    //     //                 return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Payment added Successfully'));
    //     //             }

    //     //         }
    //     //         else
    //     //         {
    //     //             if(\Auth::check())
    //     //             {
    //     //                 return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction has been ' . $status));
    //     //             }
    //     //             else
    //     //             {
    //     //                 return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been ' . $status));
    //     //             }
    //     //         }

    //     //     } 
    //     //     catch(\Exception $e) 
    //     //     {
    //     //         if(\Auth::check())
    //     //         {
    //     //             return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction has been failed!'));
    //     //         }
    //     //         else
    //     //         {
    //     //             return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been failed!'));
    //     //         }


    //     //     }
    //     // } 
    //     // else 
    //     // {
    //     //     if(\Auth::check())
    //     //     {
    //     //         return redirect()->back()->with('error',__('Permission denied.'));
    //     //     }
    //     //     else
    //     //     {
    //     //         return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error',__('Permission denied.'));
    //     //     }
    //      }
    // }
    // public function clientGetPaymentStatus(Request $request, $invoice_id)
    // {
    //     $this->paymentSetting();

    //     $invoice = Invoice::find($invoice_id);
    //     $user = User::where('id',$invoice->created_by)->first();
    //     if($invoice)
    //     {
    //         $provider = new PayPalClient;
    //         $provider->setApiCredentials(config('paypal'));
    //         $provider->getAccessToken();
    //         $response = $provider->capturePaymentOrder($request['token']);
    //         $payment_id = Session::get('paypal_payment_id');

    //         if(empty($request->PayerID || empty($request->token)))
    //         {
    //             return redirect()->route('invoices.show', $invoice_id)->with('error', __('Payment failed'));
    //         }

    //         try
    //         {
    //             $result = $payment->execute($execution, $this->_api_context)->toArray();

    //             $status = ucwords(str_replace('_', ' ', $result['state']));

    //             if($result['state'] == 'approved')
    //             {
    //                 $invoice_payment = new InvoicePayment();
    //                 $invoice_payment->transaction_id =  app('App\Http\Controllers\InvoiceController')->transactionNumber($user);
    //                 $invoice_payment->invoice_id = $invoice->id;
    //                 $invoice_payment->amount = $result['transactions'][0]['amount']['total'];
    //                 $invoice_payment->date = date('Y-m-d');
    //                 $invoice_payment->payment_id = 0;
    //                 $invoice_payment->payment_type = __('PAYPAL');
    //                 $invoice_payment->client_id = $user->id;
    //                 $invoice_payment->notes = '';

    //                 $invoice_payment->save();
    //              //   dd($invoice_payment);
                     
    //                 if(($invoice->getDue() - $invoice_payment->amount) == 0) {

    //                     $invoice->status = 'paid';

    //                     $invoice->save();
    //                 }

    //                 if(\Auth::check())
    //                 {
    //                     return redirect()->route('invoices.show', $invoice_id)->with('success', __('Payment added Successfully'));
    //                 }
    //                 else
    //                 {
    //                     return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('success', __('Payment added Successfully'));
    //                 }

    //             }
    //             else
    //             {
    //                 if(\Auth::check())
    //                 {
    //                     return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction has been ' . $status));
    //                 }
    //                 else
    //                 {
    //                     return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been ' . $status));
    //                 }
    //             }

    //         } 
    //         catch(\Exception $e) 
    //         {
    //            if(\Auth::check())
    //             {
    //                 return redirect()->route('invoices.show', $invoice_id)->with('error', __('Transaction has been failed!'));
    //             }
    //             else
    //             {
    //                 return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error', __('Transaction has been failed!'));
    //             }
    //         }
    //     } 
    //     else 
    //     {
    //         if(\Auth::check())
    //         {
    //             return redirect()->back()->with('error',__('Permission denied.'));
    //         }
    //         else
    //         {
    //             return redirect()->route('pay.invoice',\Illuminate\Support\Facades\Crypt::encrypt($invoice->id))->with('error',__('Permission denied.'));
    //         }
    //     }
    // }
}