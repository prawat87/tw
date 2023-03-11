<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Mail\EmailTest;
use App\Models\Utility;
use Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Cookie;

class SystemController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage system settings'))
        {
            $settings = Utility::settings();
            $payment = Utility::set_payment_settings();

            return view('settings.index', compact('settings','payment'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

        public function store(Request $request)
        {
        
            if(\Auth::user()->can('manage system settings'))
            {
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                $header_text = (empty($request->header_text)) ? '' : $request->header_text;
                $footer_text = (empty($request->footer_text)) ? '' : $request->footer_text;
                $gdpr_cookie = (empty($request->gdpr_cookie)) ? '' : $request->gdpr_cookie;
                $cookie_text = (empty($request->cookie_text)) ? '' : $request->cookie_text;


                $arrSetting = [
                    'gdpr_cookie'=>  $gdpr_cookie,
                    'cookie_text'=>  $cookie_text,
                    'header_text' => $header_text,
                    'footer_text' => $footer_text,
                    'default_language' => $request->default_language,
                    'enable_landing' => empty($request->display_landing) ? 'no' : 'yes'
                ];

                if(empty($request->gdpr_cookie))
                {
                    $arrSetting['gdpr_cookie'] = 'off';
                }

                if (!empty($request->SITE_RTL)) {
                    $arrSetting['SITE_RTL'] = 'on';
                }else{
                    $arrSetting['SITE_RTL'] = 'off';
                }

                foreach($arrSetting as $key => $val)
                {
                    \DB::insert(
                        'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                        $val,
                                                                                                                                                                                                                        $key,
                                                                                                                                                                                                                        \Auth::user()->creatorId(),
                                                                                                                                                                                                                        $created_at,
                                                                                                                                                                                                                        $updated_at,
                                                                                                                                                                                                                    ]
                    );
                }
                $post = $request->all();
                if($request->favicon)
                {
                    $request->validate(
                        [
                            'favicon' => 'image|mimes:png',
                        ]
                    );
                    $favicon = 'favicon.png';

                    $dir = 'logo/';
                    $validation =[
                        'mimes:'.'png',
                        'max:'.'20480',
                    ];

                    $path = Utility::upload_file($request,'favicon',$favicon,$dir,$validation);
                    if($path['flag'] == 1){
                        $favicon = $path['url'];
                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    
                    // $request->validate(['favicon' => 'required|image|mimes:png|max:1024',]);
                    // $faviconName = 'favicon.png';
                    // $path        = $request->file('favicon')->storeAs('logo', $faviconName);
                }
                if($request->logo)
                {
                    $request->validate(['logo' => 'required|image|mimes:png|max:1024',]);
                    $logoNames = 'logo-dark.png';
                    // $path     = $request->file('logo')->storeAs('logo', $logoNames);
                    // $post['logo']=$logoNames;
                    $logoName = 'logo-dark.png';
                    $dir = 'logo/';

                    $validation =[
                        'mimes:'.'png',
                        'max:'.'20480',
                    ];
                    
                    $path = Utility::upload_file($request,'logo',$logoName,$dir,$validation);
                    if($path['flag'] == 1){
                        $logo = $path['url'];
                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }

                if($request->logo_light)
                {
                    $request->validate(['logo_light' => 'required|image|mimes:png|max:1024',]);
                    $lightlogoName = 'logo-light.png';
                    $dir = 'logo/';
                    $validation =[
                        'mimes:'.'png',
                        'max:'.'20480',
                    ];
                    $path = Utility::upload_file($request,'logo_light',$lightlogoName,$dir,$validation);
                    if($path['flag'] == 1){
                        $logo_light = $path['url'];
                    }else{
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }

                $arrEnv = [
                    'SITE_RTL' => !isset($request->SITE_RTL) ? 'off' : 'on',
                ];

                Utility::setEnvironmentValue($arrEnv);
                
                    if (!isset($request->signup_button)) {
                        $post['signup_button'] = 'off';
                    }

                    if (!isset($request->dark_mode)) {
                        $post['dark_mode'] = 'off';
                    }

                    if (!isset($request->is_sidebar_transperent)) {
                        $post['is_sidebar_transperent'] = 'off';
                    }

                    $settings = Utility::settings();
                    unset($post['_token'],$post['logo'],$post['logo_light'],$post['favicon']);
                    foreach ($post as $key => $data) {


                        if (in_array($key, array_keys($settings)) && !empty($data)) {
                            if (!empty($data)) {
                                \DB::insert(
                                    'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                                    [
                                        $data,
                                        $key,
                                        \Auth::user()->creatorId(),
                                    ]
                                );
                            }
                        }
                    }

                    // Artisan::call('config:cache');
                    // Artisan::call('config:clear');

                return redirect()->back()->with('success', __('Brand Setting successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }

    public function saveEmailSettings(Request $request)
    {
        if(\Auth::user()->can('manage system settings'))
        {
            $request->validate(
                [
                    'mail_driver' => 'required|string|max:255',
                    'mail_host' => 'required|string|max:255',
                    'mail_port' => 'required|string|max:255',
                    'mail_username' => 'required|string|max:255',
                    'mail_password' => 'required|string|max:255',
                    'mail_encryption' => 'required|string|max:255',
                    'mail_from_address' => 'required|string|max:255',
                    'mail_from_name' => 'required|string|max:255',
                ]
            );

            $arrEnv = [
                'MAIL_DRIVER' => $request->mail_driver,
                'MAIL_HOST' => $request->mail_host,
                'MAIL_PORT' => $request->mail_port,
                'MAIL_USERNAME' => $request->mail_username,
                'MAIL_PASSWORD' => $request->mail_password,
                'MAIL_ENCRYPTION' => $request->mail_encryption,
                'MAIL_FROM_ADDRESS' => $request->mail_from_address,
                'MAIL_FROM_NAME' => $request->mail_from_name,
            ];

            $env = Utility::setEnvironmentValue($arrEnv);

                Artisan::call('config:cache');
                Artisan::call('config:clear');

            if($env)
            {
                return redirect()->back()->with('success', __('Setting successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', 'Something went wrong.');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function recaptchaSettingStore(Request $request)
    {

        $user = \Auth::user();
        $rules = [];
        $recaptcha_module = 'yes';
        if (!isset($request->recaptcha_module)) {
            $recaptcha_module = 'no';
        }
        if($recaptcha_module == 'yes')
        {
            $rules['google_recaptcha_key'] = 'required|string|max:50';
            $rules['google_recaptcha_secret'] = 'required|string|max:50';
        }
        $validator = \Validator::make(
            $request->all(), $rules
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $arrEnv = [
            'RECAPTCHA_MODULE' => $recaptcha_module ?? 'no',
            'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_key,
            'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret,
        ];
        if(Utility::setEnvironmentValue($arrEnv))
        {
            return redirect()->back()->with('success', __('Recaptcha Settings updated successfully'));
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function slack(Request $request)
    {
        $post = [];
        $post['slack_webhook'] = $request->input('slack_webhook');
        $post['lead_notificaation'] = $request->has('lead_notificaation')?$request->input('lead_notificaation'):0;
        $post['estimation_notificaation'] = $request->has('estimation_notificaation')?$request->input('estimation_notificaation'):0;
        $post['project_notificaation'] = $request->has('project_notificaation')?$request->input('project_notificaation'):0;
        $post['task_notificaation'] = $request->has('task_notificaation')?$request->input('task_notificaation'):0;
        $post['taskmove_notificaation'] = $request->has('taskmove_notificaation')?$request->input('taskmove_notificaation'):0;
        $post['taskcom_notificaation'] = $request->has('taskcom_notificaation')?$request->input('taskcom_notificaation'):0;
        $post['milestone_notificaation'] = $request->has('milestone_notificaation')?$request->input('milestone_notificaation'):0;
        $post['milestonest_notificaation'] = $request->has('milestonest_notificaation')?$request->input('milestonest_notificaation'):0;
        $post['invoice_notificaation'] = $request->has('invoice_notificaation')?$request->input('invoice_notificaation'):0;
        $post['invoicest_notificaation'] = $request->has('invoicest_notificaation')?$request->input('invoicest_notificaation'):0;
        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      Auth::user()->id,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }

    public function telegram(Request $request)
    {
        $post = [];
        $post['telegram_token'] = $request->input('telegram_token');

        $post['telegram_chatid'] = $request->input('telegram_chatid');

        $post['telegram_lead_notificaation'] = $request->has('telegram_lead_notificaation')?$request->input('telegram_lead_notificaation'):0;

        $post['telegram_estimation_notificaation'] = $request->has('telegram_estimation_notificaation')?$request->input('telegram_estimation_notificaation'):0;

        $post['telegram_task_notificaation'] = $request->has('telegram_task_notificaation')?$request->input('telegram_task_notificaation'):0;

        $post['telegram_project_notificaation'] = $request->has('telegram_project_notificaation')?$request->input('telegram_project_notificaation'):0;

        $post['telegram_taskmove_notificaation'] = $request->has('telegram_taskmove_notificaation')?$request->input('telegram_taskmove_notificaation'):0;


        $post['telegram_taskcom_notificaation'] = $request->has('telegram_taskcom_notificaation')?$request->input('telegram_taskcom_notificaation'):0;

        $post['telegram_milestone_notificaation'] = $request->has('telegram_milestone_notificaation')?$request->input('telegram_milestone_notificaation'):0;


        $post['telegram_milestonest_notificaation'] = $request->has('telegram_milestonest_notificaation')?$request->input('telegram_milestonest_notificaation'):0;

        $post['telegram_invoice_notificaation'] = $request->has('telegram_invoice_notificaation')?$request->input('telegram_invoice_notificaation'):0;


        $post['telegram_invoicest_notificaation'] = $request->has('telegram_invoicest_notificaation')?$request->input('telegram_invoicest_notificaation'):0;

        if(isset($post) && !empty($post) && count($post) > 0)
        {
            $created_at = $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                    $data,
                                                                                                                                                                                                                    $key,
                                                                                                                                                                                                                    Auth::user()->id,
                                                                                                                                                                                                                    $created_at,
                                                                                                                                                                                                                    $updated_at,
                                                                                                                                                                                                                ]
                );
            }
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }

    public function saveCompanySettings(Request $request)
    {
        if(\Auth::user()->can('manage company settings'))
        {
            $user = \Auth::user();
            $request->validate(
                [
                    'company_name' => 'required|string|max:50',
                    'company_email' => 'required',
                    'company_email_from_name' => 'required|string',
                ]
            );
            $post = $request->all();
            unset($post['_token']);

            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      \Auth::user()->creatorId(),
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function savePaymentSettings(Request $request)
    {
        $user = \Auth::user();

        $validator = \Validator::make(
            $request->all(), [
                'currency' => 'required|string|max:255',
                'currency_symbol' => 'required|string|max:255',
            ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }else{

            if($user->type == 'Super Admin')
            {
                $arrEnv['CURRENCY_SYMBOL'] = $request->currency_symbol;
                $arrEnv['CURRENCY'] = $request->currency;

                $env = Utility::setEnvironmentValue($arrEnv);
            }

            $post['currency_symbol'] = $request->currency_symbol;
            $post['currency'] = $request->currency;

        }

        if(isset($request->is_stripe_enabled) && $request->is_stripe_enabled == 'on')
        {
            $validator = \Validator::make(
                $request->all(), [
                    'stripe_key' => 'required|string',
                    'stripe_secret' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_stripe_enabled']     = $request->is_stripe_enabled;
            $post['stripe_secret']         = $request->stripe_secret;
            $post['stripe_key']            = $request->stripe_key;
        }
        else
        {
            $post['is_stripe_enabled'] = 'off';
        }


        if(isset($request->is_paypal_enabled) && $request->is_paypal_enabled == 'on')
        {
            $validator = \Validator::make(
                $request->all(), [
                    'paypal_mode' => 'required|string',
                    'paypal_client_id' => 'required|string',
                    'paypal_secret_key' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paypal_enabled'] = $request->is_paypal_enabled;
            $post['paypal_mode']       = $request->paypal_mode;
            $post['paypal_client_id']  = $request->paypal_client_id;
            $post['paypal_secret_key'] = $request->paypal_secret_key;
        }
        else
        {
            $post['is_paypal_enabled'] = 'off';
        }

        if(isset($request->is_paystack_enabled) && $request->is_paystack_enabled == 'on')
        {

            $validator = \Validator::make(
                $request->all(), [
                    'paystack_public_key' => 'required|string',
                    'paystack_secret_key' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paystack_enabled'] = $request->is_paystack_enabled;
            $post['paystack_public_key'] = $request->paystack_public_key;
            $post['paystack_secret_key'] = $request->paystack_secret_key;
        }
        else
        {
            $post['is_paystack_enabled'] = 'off';
        }

        if(isset($request->is_flutterwave_enabled) && $request->is_flutterwave_enabled == 'on')
        {

            $validator = \Validator::make(
                $request->all(), [
                    'flutterwave_public_key' => 'required|string',
                    'flutterwave_secret_key' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_flutterwave_enabled'] = $request->is_flutterwave_enabled;
            $post['flutterwave_public_key'] = $request->flutterwave_public_key;
            $post['flutterwave_secret_key'] = $request->flutterwave_secret_key;
        }
        else
        {
            $post['is_flutterwave_enabled'] = 'off';
        }

        if(isset($request->is_razorpay_enabled) && $request->is_razorpay_enabled == 'on')
        {

            $validator = \Validator::make(
                $request->all(), [
                    'razorpay_public_key' => 'required|string',
                    'razorpay_secret_key' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_razorpay_enabled'] = $request->is_razorpay_enabled;
            $post['razorpay_public_key'] = $request->razorpay_public_key;
            $post['razorpay_secret_key'] = $request->razorpay_secret_key;
        }
        else
        {
            $post['is_razorpay_enabled'] = 'off';
        }

        if(isset($request->is_mercado_enabled) && $request->is_mercado_enabled == 'on')
        {
            $request->validate(
                [
                    'mercado_access_token' => 'required|string',
                ]
            );
            $post['is_mercado_enabled'] = $request->is_mercado_enabled;
            $post['mercado_access_token']     = $request->mercado_access_token;
            $post['mercado_mode'] = $request->mercado_mode;
        }
        else
        {
            $post['is_mercado_enabled'] = 'off';
        }

        if(isset($request->is_paytm_enabled) && $request->is_paytm_enabled == 'on')
        {

            $validator = \Validator::make(
                $request->all(), [
                    'paytm_mode' => 'required',
                    'paytm_merchant_id' => 'required|string',
                    'paytm_merchant_key' => 'required|string',
                    'paytm_industry_type' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paytm_enabled']    = $request->is_paytm_enabled;
            $post['paytm_mode']          = $request->paytm_mode;
            $post['paytm_merchant_id']   = $request->paytm_merchant_id;
            $post['paytm_merchant_key']  = $request->paytm_merchant_key;
            $post['paytm_industry_type'] = $request->paytm_industry_type;
        }
        else
        {
            $post['is_paytm_enabled'] = 'off';
        }

        if(isset($request->is_mollie_enabled) && $request->is_mollie_enabled == 'on')
        {


            $validator = \Validator::make(
                $request->all(), [
                    'mollie_api_key' => 'required|string',
                    'mollie_profile_id' => 'required|string',
                    'mollie_partner_id' => 'required',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_mollie_enabled'] = $request->is_mollie_enabled;
            $post['mollie_api_key']    = $request->mollie_api_key;
            $post['mollie_profile_id'] = $request->mollie_profile_id;
            $post['mollie_partner_id'] = $request->mollie_partner_id;
        }
        else
        {
            $post['is_mollie_enabled'] = 'off';
        }

        if(isset($request->is_skrill_enabled) && $request->is_skrill_enabled == 'on')
        {



            $validator = \Validator::make(
                $request->all(), [
                    'skrill_email' => 'required|email',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_skrill_enabled'] = $request->is_skrill_enabled;
            $post['skrill_email']      = $request->skrill_email;
        }
        else
        {
            $post['is_skrill_enabled'] = 'off';
        }

        if(isset($request->is_coingate_enabled) && $request->is_coingate_enabled == 'on')
        {


            $validator = \Validator::make(
                $request->all(), [
                    'coingate_mode' => 'required|string',
                    'coingate_auth_token' => 'required|string',
                ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_coingate_enabled'] = $request->is_coingate_enabled;
            $post['coingate_mode']       = $request->coingate_mode;
            $post['coingate_auth_token'] = $request->coingate_auth_token;
        }
        else
        {
            $post['is_coingate_enabled'] = 'off';
        }

        if(isset($request->is_paymentwall_enabled) && $request->is_paymentwall_enabled == 'on')
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'paymentwall_public_key' => 'required|string',
                                   'paymentwall_private_key' => 'required|string',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $post['is_paymentwall_enabled'] = $request->is_paymentwall_enabled;
            $post['paymentwall_public_key'] = $request->paymentwall_public_key;
            $post['paymentwall_private_key'] = $request->paymentwall_private_key;
        }
        else
        {
            $post['is_paymentwall_enabled'] = 'off';
        }

        foreach($post as $key => $data)
        {
            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];

            $insert_payment_setting = \DB::insert(
                'insert into admin_payment_settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );
        }

        return redirect()->back()->with('success', __('Settings updated successfully.'));
    }

    public function savePusherSettings(Request $request)
    {
        if(\Auth::user()->can('manage system settings'))
        {
            if(isset($request->enable_chat))
            {
                $request->validate(
                    [
                        'pusher_app_id' => 'required',
                        'pusher_app_key' => 'required',
                        'pusher_app_secret' => 'required',
                        'pusher_app_cluster' => 'required',
                    ]
                );
            }

            $arrEnvStripe = [
                'CHAT_MODULE' => $request->enable_chat,
                'PUSHER_APP_ID' => $request->pusher_app_id,
                'PUSHER_APP_KEY' => $request->pusher_app_key,
                'PUSHER_APP_SECRET' => $request->pusher_app_secret,
                'PUSHER_APP_CLUSTER' => $request->pusher_app_cluster,
            ];

            $envStripe = Utility::setEnvironmentValue($arrEnvStripe);


            Artisan::call('config:cache');
            Artisan::call('config:clear');

            if($envStripe)
            {
                return redirect()->back()->with('success', __('Pusher Setting successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveSystemSettings(Request $request)
    {
        if(\Auth::user()->can('manage company settings'))
        {
            $user = \Auth::user();
            $request->validate(
                [
                    'site_currency' => 'required',
                    'site_currency_symbol' => 'required',
                ]
            );
            $post = $request->all();
            unset($post['_token']);

            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      \Auth::user()->creatorId(),
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveCompanyPaymentSettings(Request $request)
    {
        if(\Auth::user()->can('manage company settings'))
        {
            $post = $request->all();
            unset($post['_token']);

            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            $stripe_status = $request->site_enable_stripe ?? 'off';
            $paypal_status = $request->site_enable_paypal ?? 'off';

            $validatorArray = [];

            if($stripe_status == 'on')
            {
                $validatorArray['site_stripe_key']    = 'required|string|max:255';
                $validatorArray['site_stripe_secret'] = 'required|string|max:255';
            }
            if($paypal_status == 'on')
            {
                $validatorArray['site_paypal_client_id']  = 'required|string|max:255';
                $validatorArray['site_paypal_secret_key'] = 'required|string|max:255';
            }

            $validator = Validator::make(
                $request->all(), $validatorArray
            );

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $post['site_enable_stripe'] = $stripe_status;
            $post['site_enable_paypal'] = $paypal_status;

            foreach($post as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      \Auth::user()->creatorId(),
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
            }

            return redirect()->back()->with('success', __('Setting successfully updated.'));

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function companyIndex()
    {
        if(\Auth::user()->can('manage company settings'))
        {
            $settings       = Utility::settings();
            $setting      = Utility::getLayoutsSetting();
            $EmailTemplates = EmailTemplate::all();
            $payment = Utility::set_payment_settings();

            return view('settings.company', compact('settings', 'EmailTemplates','payment','setting'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveBusinessSettings(Request $request)
    { 
        if(\Auth::user()->can('manage business settings'))
        {
            $user = \Auth::user();

            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            $arrSetting = [];

            if($request->company_logo)
            {

                $request->validate(['company_logo' => 'required|image|mimes:png|max:1024',]);
                $logoNames = 'logo-dark.png';
                $logoName = $user->id . 'logo_dark.png';
                $dir = 'logo/';
                $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];
                $path = Utility::upload_file($request,'company_logo',$logoName,$dir,$validation);
                if($path['flag'] == 1){
                    $company_logo = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $logoName,
                                                                                                                                                                                                                      'company_logo',
                                                                                                                                                                                                                      \Auth::user()->creatorId(),
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );

                // $request->validate(
                //     [
                //         'company_logo' => 'image|mimes:png',
                //     ]
                // );
                // $arrSetting['company_logo'] = $user->id . '_logo.png';
                // $path                       = $request->file('company_logo')->storeAs('logo', $arrSetting['company_logo']);
                // $company_logo               = !empty($request->company_logo) ? $arrSetting['company_logo'] : 'logo-dark.png';
                
            }

            if($request->company_logo_light)
            {
                $request->validate(
                    [
                        'company_logo_light' => 'image|mimes:png|max:20480',
                    ]
                );

                $logoName_light     = $user->id . 'light_logo.png';

                $dir = 'logo/';
                $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];
                // $arrSetting['company_logo'] = $user->id . 'logo_dark.png';
                $path = Utility::upload_file($request,'company_logo_light',$logoName_light,$dir,$validation);
                if($path['flag'] == 1){
                    $company_logo_light = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $logoName_light,
                                                                                                                                                                                                                      'company_logo_light',
                                                                                                                                                                                                                      \Auth::user()->creatorId(),
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );
                // $request->validate(
                //     [
                //         'company_logo_light' => 'image|mimes:png',
                //     ]
                // );
                // $arrSetting['company_logo_light'] = $user->id . '_light_logo.png';
                // $path                       = $request->file('company_logo_light')->storeAs('logo', $arrSetting['company_logo_light']);
                // $company_logo_light         = !empty($request->company_logo_light) ? $arrSetting['company_logo_light'] : 'logo-light.png';

            }

            if($request->company_favicon)
            {

                $request->validate(
                    [
                        'company_favicon' => 'image|mimes:png|max:20480',
                    ]
                );

                $favicon  = $user->id . '_favicon.png';

                $dir = 'logo/';
                $validation =[
                    'mimes:'.'png',
                    'max:'.'20480',
                ];
                $path = Utility::upload_file($request,'company_favicon',$favicon,$dir,$validation);
                if($path['flag'] == 1){
                    $company_favicon = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }

                $company_favicon = !empty($request->company_favicon) ? $favicon : 'favicon.png';
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $favicon,
                                                                                                                                                                                                                      'company_favicon',
                                                                                                                                                                                                                      \Auth::user()->creatorId(),
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                                  ]
                );


                // $request->validate(
                //     [
                //         'company_favicon' => 'image|mimes:png',
                //     ]
                // );
                // $arrSetting['company_favicon'] = $user->id . '_favicon.png';
                // $path                          = $request->file('company_favicon')->storeAs('logo', $arrSetting['company_favicon']);
                // $company_favicon               = !empty($request->favicon) ? $arrSetting['company_favicon'] : 'favicon.png';
            }

            $arrSetting['header_text'] = (!isset($request->header_text) && empty($request->header_text)) ? '' : $request->header_text;
            $arrSetting['theme_color'] = (!isset($request->theme_color) && empty($request->theme_color)) ? 'theme-3' : $request->theme_color;
            $arrSetting['SITE_RTL'] = (!isset($request->SITE_RTL) && empty($request->SITE_RTL)) ? 'off' : $request->SITE_RTL;
            // $arrSetting['dark_mode'] = (!isset($request->dark_mode)) ? 'off' : $request->dark_mode;

            $arrSetting['dark_mode'] = !empty($request->dark_mode) ? $request->dark_mode : 'off';

           
            $arrSetting['is_sidebar_transperent'] = (!isset($request->is_sidebar_transperent) && empty($request->is_sidebar_transperent)) ? 'off' :$request->is_sidebar_transperent;

            foreach($arrSetting as $key => $data)
            {
                \DB::insert(
                    'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                      $data,
                                                                                                                                                                                                                      $key,
                                                                                                                                                                                                                      \Auth::user()->id,
                                                                                                                                                                                                                      $created_at,
                                                                                                                                                                                                                      $updated_at,
                                                                                                                                                                                                  ]
                );
            }

            return redirect()->back()->with('success', 'Setting successfully updated.');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function saveTemplateSettings(Request $request)
    {
        $user = \Auth::user();
        $post = $request->all();
        unset($post['_token']);

        if(isset($post['invoice_template']) && (!isset($post['invoice_color']) || empty($post['invoice_color'])))
        {
            $post['invoice_color'] = "ffffff";
        }

        if(isset($post['estimation_template']) && (!isset($post['estimation_color']) || empty($post['estimation_color'])))
        {
            $post['estimation_color'] = "ffffff";
        }

        if($request->invoice_logo)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'invoice_logo' => 'image|mimes:png|max:2048',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $validator = \Validator::make(
                $request->all(), [
                                   'invoice_logo' => 'image|mimes:png|max:2048',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $invoice_logo         = $user->id . '_invoice_logo.png';
            $dir = 'invoice_logo/';

            $validation =[
                'mimes:'.'png',
                'max:'.'20480',
            ];

            $path = Utility::upload_file($request,'invoice_logo',$invoice_logo,$dir, $validation);
            if($path['flag'] == 1){
                $invoice_logo = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }
            $post['invoice_logo'] = $invoice_logo;

            // $invoice_logo         = $user->id . '_invoice_logo.png';
            // $path                 = $request->file('invoice_logo')->storeAs('invoice_logo', $invoice_logo);
            // $post['invoice_logo'] = $invoice_logo;
        }

        if($request->estimation_logo)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'estimation_logo' => 'image',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $estimation_logo         = $user->id . '_estimation_logo.png';
            $dir = 'estimation_logo/';

            $validation =[
                'mimes:'.'png',
                'max:'.'20480',
            ];

            $path = Utility::upload_file($request,'estimation_logo',$estimation_logo,$dir,$validation);
            if($path['flag'] == 1){
                $estimation_logo = $path['url'];
            }else{
                return redirect()->back()->with('error', __($path['msg']));
            }
            $post['estimation_logo'] = $estimation_logo;


            // $estimation_logo         = $user->id . '_estimation_logo.png';
            // $path                    = $request->file('estimation_logo')->storeAs('estimation_logo', $estimation_logo);
            // $post['estimation_logo'] = $estimation_logo;
        }

        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');

        foreach($post as $key => $data)
        {
            \DB::insert(
                'INSERT INTO settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`) ', [
                                                                                                                                                                                                                  $data,
                                                                                                                                                                                                                  $key,
                                                                                                                                                                                                                  \Auth::user()->creatorId(),
                                                                                                                                                                                                                  $created_at,
                                                                                                                                                                                                                  $updated_at,
                                                                                                                                                                                                              ]
            );
        }

        if(isset($post['invoice_template']))
        {
            return redirect()->back()->with('success', __('Invoice Setting updated successfully'));
        }

        if(isset($post['estimation_template']))
        {
            return redirect()->back()->with('success', __('Estimation Setting updated successfully'));
        }
    }


    public function saveZoomSettings(Request $request)
    {
        $post = $request->all();

        unset($post['_token']);
        $created_by = \Auth::user()->creatorId();
        foreach($post as $key => $data)
        {
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', [
                                                                                                                                                                                $data,
                                                                                                                                                                                $key,
                                                                                                                                                                                $created_by,
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                                date('Y-m-d H:i:s'),
                                                                                                                                                                            ]
            );
        }
        return redirect()->back()->with('success', __('Setting added successfully saved.'));
    }

    public function testEmail(Request $request)
    {
        // dd($request->all());
        $user = \Auth::user();
        
            $data                      = [];
            $data['mail_driver']       = $request->mail_driver;
            $data['mail_host']         = $request->mail_host;
            $data['mail_port']         = $request->mail_port;
            $data['mail_username']     = $request->mail_username;
            $data['mail_password']     = $request->mail_password;
            $data['mail_encryption']   = $request->mail_encryption;
            $data['mail_from_address'] = $request->mail_from_address;
            $data['mail_from_name']    = $request->mail_from_name;

            return view('settings.test_email', compact('data'));
      
    }

    public function testEmailSend(Request $request)
    {

        $validator = \Validator::make(
            $request->all(), [
                               'email' => 'required|email',
                               'mail_driver' => 'required',
                               'mail_host' => 'required',
                               'mail_port' => 'required',
                               'mail_username' => 'required',
                               'mail_password' => 'required',
                               'mail_from_address' => 'required',
                               'mail_from_name' => 'required',
                           ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return response()->json(
                [
                    'is_success' => false,
                    'message' => $messages->first(),
                ]
            );
        }

        try
        {
            config(
                [
                    'mail.driver' => $request->mail_driver,
                    'mail.host' => $request->mail_host,
                    'mail.port' => $request->mail_port,
                    'mail.encryption' => $request->mail_encryption,
                    'mail.username' => $request->mail_username,
                    'mail.password' => $request->mail_password,
                    'mail.from.address' => $request->mail_from_address,
                    'mail.from.name' => $request->mail_from_name,
                ]
            );
            Mail::to($request->email)->send(new EmailTest());
        }
        catch(\Exception $e)
        {
            return response()->json(
                [
                    'is_success' => false,
                    'message' => $e->getMessage(),
                ]
            );
        }

        return response()->json(
            [
                'is_success' => true,
                'message' => __('Email send Successfully'),
            ]
        );
    }


    public function storageSettingStore(Request $request)
    {
        if(isset($request->storage_setting) && $request->storage_setting == 'local')
        {
             $validator = \Validator::make(
                $request->all(), [
                'local_storage_validation' => 'required',
                'local_storage_max_upload_size' => 'required',
                           ]
                );
            $post['storage_setting'] = $request->storage_setting;
            $local_storage_validation = implode(',', $request->local_storage_validation);
            $post['local_storage_validation'] = $local_storage_validation;
            $post['local_storage_max_upload_size'] = $request->local_storage_max_upload_size;
            
        }
        
        if(isset($request->storage_setting) && $request->storage_setting == 's3')
        {
            
            $validator = \Validator::make(
                $request->all(), [
                    's3_key'                  => 'required',
                    's3_secret'               => 'required',
                    's3_region'               => 'required',
                    's3_bucket'               => 'required',
                    's3_url'                  => 'required',
                    's3_endpoint'             => 'required',
                    's3_max_upload_size'      => 'required',
                    's3_storage_validation'   => 'required',
                           ]
                );
            $post['storage_setting']            = $request->storage_setting;
            $post['s3_key']                     = $request->s3_key;
            $post['s3_secret']                  = $request->s3_secret;
            $post['s3_region']                  = $request->s3_region;
            $post['s3_bucket']                  = $request->s3_bucket;
            $post['s3_url']                     = $request->s3_url;
            $post['s3_endpoint']                = $request->s3_endpoint;
            $post['s3_max_upload_size']         = $request->s3_max_upload_size;
            $s3_storage_validation              = implode(',', $request->s3_storage_validation);
            $post['s3_storage_validation']      = $s3_storage_validation;
        }
        
        if(isset($request->storage_setting) && $request->storage_setting == 'wasabi')
        {
            
            $validator = \Validator::make(
                $request->all(), [
                    'wasabi_key'                    => 'required',
                    'wasabi_secret'                 => 'required',
                    'wasabi_region'                 => 'required',
                    'wasabi_bucket'                 => 'required',
                    'wasabi_url'                    => 'required',
                    'wasabi_root'                   => 'required',
                    'wasabi_max_upload_size'        => 'required',
                    'wasabi_storage_validation'     => 'required',
                           ]
                );
            $post['storage_setting']            = $request->storage_setting;
            $post['wasabi_key']                 = $request->wasabi_key;
            $post['wasabi_secret']              = $request->wasabi_secret;
            $post['wasabi_region']              = $request->wasabi_region;
            $post['wasabi_bucket']              = $request->wasabi_bucket;
            $post['wasabi_url']                 = $request->wasabi_url;
            $post['wasabi_root']                = $request->wasabi_root;
            $post['wasabi_max_upload_size']     = $request->wasabi_max_upload_size;
            $wasabi_storage_validation          = implode(',', $request->wasabi_storage_validation);
            $post['wasabi_storage_validation']  = $wasabi_storage_validation;
        }
        
        foreach($post as $key => $data)
        {

            $arr = [
                $data,
                $key,
                \Auth::user()->id,
            ];
            
            \DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ', $arr
            );
        }
        
        return redirect()->back()->with('success', 'Storage setting successfully updated.');
        
    }
}
