<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\InvoiceProduct;
use App\Models\Mail\CustomInvoiceSend;
use App\Models\Mail\InvoiceSend;
use App\Models\Mail\PaymentReminder;
use App\Models\Milestone;
use App\Models\Payment;
use App\Models\Products;
use App\Models\Task;
use App\Models\Tax;
use App\Models\User;
use App\Models\Utility;
use App\Exports\invoiceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Stripe;

class InvoiceController extends Controller
{
    public $currancy;
    public $currancy_symbol;
        
    public $stripe_secret;
    public $stripe_key;
    public $stripe_webhook_secret;

    public function __construct()
    {
        $this->middleware(
            [
               
                'XSS',
            ]
        );
    }

    public function index()
    {
        if(\Auth::user()->can('manage invoice') || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'client')
            {
                $invoices = Invoice::select(['invoices.*'])->join('projects', 'projects.id', '=', 'invoices.project_id')->where('projects.client', '=', \Auth::user()->id)->where('invoices.created_by', '=', \Auth::user()->creatorId())->get();
            }
            else
            {
                $invoices = Invoice::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('invoices.index')->with('invoices', $invoices);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function export()
    {
        $name = 'invoice_' . date('Y-m-d i:h:s');
        $data = Excel::download(new invoiceExport(), $name . '.xlsx');ob_end_clean();

        return $data;
    }
    
    public function create()
    {
        if(\Auth::user()->can('create invoice'))
        {
            $taxes    = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $projects = \Auth::user()->projects->pluck('name', 'id');

            $taxes->prepend('Select Tax', '');
            $projects->prepend('Select Project', '');

            return view('invoices.create', compact('projects', 'taxes'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create invoice'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   // 'project_id' => 'required',
                                   'issue_date' => 'required|date',
                                   'due_date' => 'required|date',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('invoices.index')->with('error', $messages->first());
            }

            $invoice             = new Invoice();
            $invoice->invoice_id = $this->invoiceNumber();
            $invoice->status     = 0;
            $invoice->issue_date = $request->issue_date;
            $invoice->due_date   = $request->due_date;
            $invoice->discount   = 0;
            $invoice->terms      = $request->terms;

            if(isset($request->project_id) && !empty($request->project_id))
            {
                $invoice->project_id = $request->project_id;
            }
            if(isset($request->tax_id) && !empty($request->tax_id))
            {
                $invoice->tax_id = $request->tax_id;
            }
            $invoice->created_by = \Auth::user()->creatorId();
            $invoice->save();

            ActivityLog::create(
                [
                    'user_id' => \Auth::user()->creatorId(),
                    'project_id' => (isset($request->project_id) && !empty($request->project_id)) ? $request->project_id : 0,
                    'log_type' => 'Create Invoice',
                    'remark' => sprintf(__('%s Create new invoice "%s"'), \Auth::user()->name, Utility::invoiceNumberFormat($invoice->invoice_id)),
                ]
            );

             $settings  = Utility::settings(Auth::user()->creatorId());
               if(isset($settings['invoice_notificaation']) && $settings['invoice_notificaation'] ==1){
                $msg = "New invoice" ." ". Utility::invoiceNumberFormat($invoice->id)." created by ".\Auth::user()->name.'.';
             
                Utility::send_slack_msg($msg); 
            }

             if(isset($settings['telegram_invoice_notificaation']) && $settings['telegram_invoice_notificaation'] ==1){
                $msg = "New invoice" ." ". Utility::invoiceNumberFormat($invoice->id)." created by the ".\Auth::user()->name.'.';
                Utility::send_telegram_msg($msg); 
            }


            return redirect()->route('invoices.show', $invoice->id)->with('success', __('Invoice successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function invoiceNumber()
    {
        $latest = Invoice::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->invoice_id + 1;
    }

    public function show(Invoice $invoice)
    {
        if(\Auth::user()->can('show invoice') || \Auth::user()->type == 'client')
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $settings = Utility::settings();
                $payment_setting = Utility::payment_settings();
                $user     = '';
                if(!empty($invoice->project))
                {
                    $client = $invoice->project->client;
                    if($client != 0)
                    {
                        $user = User::where('id', $client)->first();
                    }
                }
                
                return view('invoices.view', compact('invoice', 'settings', 'user', 'payment_setting'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function payinvoice($invoice_id)
    {
        $id=\Illuminate\Support\Facades\Crypt::decrypt($invoice_id);
        $invoice = Invoice::where('id',$id)->first();
        $users = User::where('id',$invoice->created_by)->first();

            
        if( $users->can('show invoice') ||  $users->type == 'client')
        {
            if($invoice->created_by ==  $users->creatorId())
            { 
               // $ids =\Crypt::decrypt($invoice->id);
                $settings = Utility::settingsById($id);
                $payment_setting = Utility::payment_settings();
            
                $user     = '';
                if(!empty($invoice->project))
                {
                    $client = $invoice->project->client;
                    if($client != 0)
                    {
                        $user = User::where('id', $client)->first();
                    }
                }

                return view('invoices.invoicepay', compact('invoice', 'settings', 'user', 'payment_setting','users'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function edit(Invoice $invoice)
    {
        if(\Auth::user()->can('edit invoice'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $taxes    = Tax::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $projects = \Auth::user()->projects->pluck('name', 'id');

                $taxes->prepend('Select Tax', '');
                $projects->prepend('Select Project', '');

                return view('invoices.edit', compact('invoice', 'projects', 'taxes'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Invoice $invoice)
    {

        if(\Auth::user()->can('edit invoice'))
        {

            if($invoice->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'project_id' => 'required',
                                       'issue_date' => 'required',
                                       'due_date' => 'required',
                                       'discount' => 'required|min:0',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('invoices.index')->with('error', $messages->first());
                }

                $invoice->project_id = $request->project_id;
                $invoice->issue_date = $request->issue_date;
                $invoice->due_date   = $request->due_date;
                $invoice->tax_id     = $request->tax_id;
                $invoice->terms      = $request->terms;
                $invoice->discount   = $request->discount;
                $invoice->save();

                return redirect()->back()->with('success', __('Invoice successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Invoice $invoice)
    {
        if(\Auth::user()->can('delete invoice'))
        {
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $invoice->delete();
                InvoicePayment::where('invoice_id', '=', $invoice->id)->delete();
                InvoiceProduct::where('invoice_id', '=', $invoice->id)->delete();

                return redirect()->route('invoices.index')->with('success', __('Invoice successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function productAdd($id)
    {
        if(\Auth::user()->can('create invoice product'))
        {
            $invoice = Invoice::find($id);
           
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $products   = Products::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $milestones = Milestone::where('project_id', $invoice->project_id)->get();
                $tasks      = Task::where('project_id', $invoice->project_id)->get();

                return view('invoices.product', compact('invoice', 'milestones', 'tasks'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function productStore($id, Request $request)
    {
        if(\Auth::user()->can('create invoice product'))
        {
            $invoice = Invoice::find($id);

            if($invoice->getTotal() == 0.0)
            {
                Invoice::change_status($invoice->id, 1);

                    $settings  = Utility::settings(Auth::user()->creatorId());
                    if(isset($settings['invoicest_notificaation']) && $settings['invoicest_notificaation'] ==1){
                    $msg = " Invoice status updated by ".\Auth::user()->name.'.';
                    Utility::send_slack_msg($msg); 
                }

                if(isset($settings['telegram_invoicest_notificaation']) && $settings['telegram_invoicest_notificaation'] ==1){
                    $msg = " Invoice status updated by  ".\Auth::user()->name.'.';
                    Utility::send_telegram_msg($msg); 
                }

            }

            if($invoice->created_by == \Auth::user()->creatorId())
            {
                if($request->type == 'milestone')
                {
                    $validator = \Validator::make(
                        $request->all(), [
                                           'milestone_id' => 'required',
                                       ]
                    );
                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();

                        return redirect()->route('invoices.show', $invoice->id)->with('error', __('Please select milestone or task.'));

                    }

                    $task      = Task::find($request->task_id);
                    $milestone = Milestone::find($request->milestone_id);
                    $item      = (!empty($task->title) ? $task->title : '') . '-' . (!empty($milestone->title) ? $milestone->title : '');
                    $price     = $request->price;
                }
                else
                {
                    $validator = \Validator::make(
                        $request->all(), [
                                           'title' => 'required',
                                           'price' => 'required',
                                       ]
                    );
                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();

                        return redirect()->route('invoices.show', $invoice->id)->with('error', __('title and price filed are required'));

                    }

                    $item  = $request->title;
                    $price = $request->price;
                }


                InvoiceProduct::create(
                    [
                        'invoice_id' => $invoice->id,
                        'iteam' => $item,
                        'price' => $price,
                        'type' => $request->type,
                    ]
                );

                $invoice = Invoice::where('id', $id)->first();
                $due     = $invoice->getDue();
                $total   = $invoice->getTotal();

                if($due == 0)
                {
                    $invoice->status = 0;
                    $invoice->save();
                }
                else
                {
                    $invoice->status = 1;
                    $invoice->save();
                }

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Product successfully added.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function productEdit($id, $product_id)
    {
        if(\Auth::user()->can('edit invoice product'))
        {
            $invoice = Invoice::find($id);
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $product  = InvoiceProduct::find($product_id);
                $products = Products::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('invoices.product', compact('invoice', 'products', 'product'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function productUpdate($id, $product_id, Request $request)
    {

        if(\Auth::user()->can('edit invoice product'))
        {
            $invoice = Invoice::find($id);
            if($invoice->created_by == \Auth::user()->creatorId())
            {

                $validator = \Validator::make(
                    $request->all(), [
                                       'product_id' => 'required',
                                       'quantity' => 'required|numeric|min:1',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('invoices.show', $invoice->id)->with('error', $messages->first());
                }
                $product                     = Products::find($request->product_id);
                $invoiceProduct              = InvoiceProduct::find($product_id);
                $invoiceProduct->product_id  = $product->id;
                $invoiceProduct->price       = $product->price;
                $invoiceProduct->quantity    = $request->quantity;
                $invoiceProduct->description = $request->description;
                $invoiceProduct->save();

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Product successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function productDelete($id, $product_id)
{

        if(\Auth::user()->can('delete invoice product'))
        {
            $invoice = Invoice::find($id);
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $invoiceProduct = InvoiceProduct::find($product_id);
                $invoiceProduct->delete();
                if($invoice->getDue() <= 0.0)
                {
                    Invoice::change_status($invoice->id, 3);

                    $settings  = Utility::settings(Auth::user()->creatorId());
                     if(isset($settings['invoicest_notificaation']) && $settings['invoicest_notificaation'] ==1){
                    $msg = " Invoice status updated by ".\Auth::user()->name.'.';
                   
                    Utility::send_slack_msg($msg); 
                    }

                if(isset($settings['telegram_invoicest_notificaation']) && $settings['telegram_invoicest_notificaation'] ==1){
                   $msg = " Invoice status updated by  ".\Auth::user()->name.'.';
                 Utility::send_telegram_msg($msg); 
                  }
            
                }

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Product successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function milestoneTask(Request $request)
    {
        if(!empty($request->milestone_id))
        {
            $tasks = Task::where('milestone_id', $request->milestone_id)->get();

            return $tasks;
        }
        else
        {
            $tasks = Task::where('project_id', $request->project_id)->get();

            return $tasks;
        }

    }

    public function paymentAdd($id)
    {
        if(\Auth::user()->can('create invoice payment'))
        {
            $invoice = Invoice::find($id);
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $payment_methods = Payment::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

                return view('invoices.payment', compact('invoice', 'payment_methods'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function paymentStore($id, Request $request)
    {
        if(\Auth::user()->can('create invoice payment'))
        {
            $invoice = Invoice::find($id);
            if($invoice->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'amount' => 'required|numeric|min:1',
                                       'date' => 'required',
                                       'payment_id' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('invoices.show', $invoice->id)->with('error', $messages->first());
                }
                InvoicePayment::create(
                    [
                        'transaction_id' => $this->transactionNumber(\Auth::user()),
                        'invoice_id' => $invoice->id,
                        'amount' => $request->amount,
                        'date' => $request->date,
                        'payment_id' => $request->payment_id,
                        'payment_type' => __('MANUAL'),
                        'notes' => $request->notes,
                    ]
                );
                if($invoice->getDue() == 0.0)
                {
                    Invoice::change_status($invoice->id, 3);
                }
                else
                {
                    Invoice::change_status($invoice->id, 2);
                }

                $invoice = Invoice::where('id', $id)->first();
                $due     = $invoice->getDue();
                $total   = $invoice->getTotal();

                if($due <= 0)
                {
                    $invoice->status = 2;
                    $invoice->save();
                }
                else
                {
                    $invoice->status = 3;
                    $invoice->save();
                }

                $settings  = Utility::settings(Auth::user()->creatorId());
                 if(isset($settings['invoicest_notificaation']) && $settings['invoicest_notificaation'] ==1){
                $msg = " Invoice status updated by ".\Auth::user()->name.'.';
               
                Utility::send_slack_msg($msg); 
                }

                 if(isset($settings['telegram_invoicest_notificaation']) && $settings['telegram_invoicest_notificaation'] ==1){
                $msg = " Invoice status updated by  ".\Auth::user()->name.'.';
                Utility::send_telegram_msg($msg); 
                 }
            

                return redirect()->route('invoices.show', $invoice->id)->with('success', __('Payment successfully added.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function addPayment($id, Request $request)
    {
        $this->paymentSetting();
        

        $objUser = \Illuminate\Support\Facades\Auth::user();
        $invoice = Invoice::find($id);

        $settings = Utility::settings();

        if($invoice)
        {
            if($request->amount > $invoice->getDue())
            {
                return redirect()->back()->with('error', __('Invalid amount.'));
            }
            else
            {
                try
                {
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $price   = $request->amount;
                    Stripe\Stripe::setApiKey($this->stripe_secret);
                    $data = Stripe\Charge::create(
                        [
                            "amount" => 100 * $price,
                            "currency" => $this->currancy,
                            "source" => $request->stripeToken,
                            "description" => $settings['company_name'] . " - " . Utility::invoiceNumberFormat($invoice->invoice_id),
                            "metadata" => ["order_id" => $orderID],
                        ]
                    );

                    if($data['amount_refunded'] == 0 && empty($data['failure_code']) && $data['paid'] == 1 && $data['captured'] == 1)
                    {
                        InvoicePayment::create(
                            [
                                'transaction_id' => $this->transactionNumber(Auth::user()),
                                'invoice_id' => $invoice->id,
                                'amount' => $price,
                                'date' => date('Y-m-d'),
                                'payment_id' => 0,
                                'payment_type' => __('STRIPE'),
                                'client_id' => $objUser->id,
                                'notes' => '',
                            ]
                        );

                        if(($invoice->getDue() - $request->amount) == 0)
                        {
                            $invoice->status = 'paid';
                            $invoice->save();
                        }

                        return redirect()->back()->with('success', __(' Payment added Successfully'));
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Transaction has been failed!'));
                    }

                }
                catch(\Exception $e)
                {
                    return redirect()->route('invoices.show', $invoice->id)->with('error', __($e->getMessage()));
                }
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    function transactionNumber($user)
    {
        $latest = InvoicePayment::select('invoice_payments.*')->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', $user->creatorId())->latest()->first();
        if($latest)
        {
            return $latest->transaction_id + 1;
        }
        return 1;
    }


    public function payments()
    {
        if(\Auth::user()->can('manage invoice payment') || \Auth::user()->type == 'client')
        {
            if(\Auth::user()->type == 'client')
            {
                $payments = InvoicePayment::select(['invoice_payments.*'])->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')->join('projects', 'invoices.project_id', '=', 'projects.id')->where('projects.client', '=', \Auth::user()->id)->where(
                    'invoices.created_by', '=', \Auth::user()->creatorId()
                )->get();
            }
            else
            {
                $payments = InvoicePayment::select(['invoice_payments.*'])->join('invoices', 'invoice_payments.invoice_id', '=', 'invoices.id')->where('invoices.created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('invoices.all-payments', compact('payments'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function printInvoice($id)
    {
           $invoiceId = Crypt::decrypt($id);
            $invoice   = Invoice::findOrFail($invoiceId);

            $users = User::where('id',$invoice->created_by)->first();

        if( $users->can('manage invoice'))
        { 
            $settings  = Utility::settings();

            //Set your logo
            // $logo         = asset(\Storage::url('logo/'));
            $logo=\App\Models\Utility::get_file('logo/');
            $logo_invoice=\App\Models\Utility::get_file('invoice_logo/');
            $company_logo = Utility::getValByName('company_logo');
            $invoice_logo = Utility::getValByName('invoice_logo');
            if(isset($invoice_logo) && !empty($invoice_logo))
            {
                
                $img = $logo_invoice.$invoice_logo;
            }
            else
            {
                $img = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
            }

            if(empty($settings))
            {
                $settings = Utility::settings();
            }

            if(!empty($invoice->project))
            {
                $client = $invoice->project->client;
                if($client != 0)
                {
                    $user = User::where('id', $client)->first();
                }
                else
                {
                    $user = '';
                }
            }
            else
            {
                $user = '';
            }

           
            if($invoice)
            {
                $color      = '#' . $settings['invoice_color'];
                $font_color = Utility::getFontColor($color);

                return view('invoices.templates.' . $settings['invoice_template'], compact('invoice', 'color', 'settings', 'user', 'img', 'font_color','users'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function previewInvoice($template, $color)
    {
        $settings   = Utility::settings();
        $preview    = 1;
        $color = '#' . ($color != 'undefined' ? $color : 'ffffff');
        $font_color = Utility::getFontColor($color);

        $invoice       = new Invoice();
        $user          = new \stdClass();
        $tax           = new \stdClass();
        $project       = new \stdClass();
        $user->name    = 'Client';
        $user->email   = 'client@example.com';
        $tax->name     = 'GST';
        $tax->rate     = 10;
        $project->name = 'Test Project';

        $items = [];
        for($i = 1; $i <= 3; $i++)
        {
            $item        = new \stdClass();
            $item->iteam = 'Item ' . $i;;
            $item->price = 100;
            $items[]     = $item;
        }

        $invoice->invoice_id = 1;
        $invoice->issue_date = date('Y-m-d H:i:s');
        $invoice->due_date   = date('Y-m-d H:i:s');
        $invoice->discount   = 50;
        $invoice->items      = $items;
        $invoice->tax        = $tax;
        $invoice->project    = $project;


        //Set your logo
        // $logo         = asset(\Storage::url('logo/'));
        $logo=\App\Models\Utility::get_file('logo/');
        $logo_invoice=\App\Models\Utility::get_file('/');
        $company_logo = Utility::getValByName('company_logo');
        $invoice_logo = Utility::getValByName('invoice_logo');
        if(isset($invoice_logo) && !empty($invoice_logo))
        {
            $img = $logo_invoice.$invoice_logo;
        }
        else
        {
            $img = asset($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png'));
        }

        return view('invoices.templates.' . $template, compact('invoice', 'preview', 'color', 'settings', 'user', 'img', 'font_color'));
    }

    public function paymentReminder($invoice_id)
    {
        if(\Auth::user()->can('payment reminder invoice'))
        {
            $invoice            = Invoice::find($invoice_id);
            $client             = !empty($invoice->project) ? $invoice->project->client() : '';
            $invoice->dueAmount = \Auth:: user()->priceFormat($invoice->getDue());
            $invoice->name      = !empty($client) ? $client->name : 'Dear';
            $email              = !empty($client) ? $client->email : '';
            $invoice->date      = \Auth::user()->dateFormat($invoice->issue_date);
            $invoice->invoice   = Utility::invoiceNumberFormat($invoice->invoice_id);

            try
            {
                Mail::to($email)->send(new PaymentReminder($invoice));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->back()->with('success', __('Payment reminder successfully send.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function sent($id)
    {
        if(\Auth::user()->can('send invoice'))
        {
            $invoice          = Invoice::where('invoice_id', $id)->first();
            $client           = !empty($invoice->project) ? $invoice->project->client() : '';
            $invoice->name    = !empty($client) ? $client->name : 'Dear';
            $email            = !empty($client) ? $client->email : '';
            $invoice->invoice = Utility::invoiceNumberFormat($invoice->invoice_id);
            $invoiceId        = Crypt::encrypt($invoice->invoice_id);
            $invoice->url     = route('get.invoice', $invoiceId);
            try
            {
                Mail::to($email)->send(new InvoiceSend($invoice));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->back()->with('success', __('Invoice successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customMail($invoice_id)
    {
        if(\Auth::user()->can('custom mail send invoice'))
        {
            return view('invoices.invoice_send', compact('invoice_id'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function customMailSend(Request $request, $invoice_id)
    {
        if(\Auth::user()->can('custom mail send invoice'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'email' => 'required|email',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $email            = $request->email;
            $invoice          = Invoice::where('invoice_id', $invoice_id)->first();
            $invoice->name    = \Auth::user()->name;
            $invoice->invoice = Utility::invoiceNumberFormat($invoice->invoice_id);
            $invoiceId        = Crypt::encrypt($invoice->invoice_id);
            $invoice->url     = route('get.invoice', $invoiceId);
            try
            {
                Mail::to($email)->send(new CustomInvoiceSend($invoice));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->back()->with('success', __('Invoice successfully sent.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function paymentSetting()
    {
        
        $admin_payment_setting = Utility::payment_settings();

        $this->currancy_symbol = isset($admin_payment_setting['currency_symbol'])?$admin_payment_setting['currency_symbol']:'';
        $this->currancy = isset($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'';

        $this->stripe_secret = isset($admin_payment_setting['stripe_secret'])?$admin_payment_setting['stripe_secret']:'';
        $this->stripe_key = isset($admin_payment_setting['stripe_key'])?$admin_payment_setting['stripe_key']:'';
        $this->stripe_webhook_secret = isset($admin_payment_setting['stripe_webhook_secret'])?$admin_payment_setting['stripe_webhook_secret']:'';
    }
}
