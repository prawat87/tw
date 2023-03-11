<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Mail\CommonEmailTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;
use Cookie;


class Utility extends Model
{
    public static function settings()
    {
        $data = DB::table('settings');

        if (\Auth::check()) {
            $data = $data->where('created_by', '=', \Auth::user()->creatorId())->get();

            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
        } else {

            $data->where('created_by', '=', 1);
            $data = $data->get();
        }


        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "site_enable_stripe" => "off",
            "site_stripe_key" => "",
            "site_stripe_secret" => "",
            "site_enable_paypal" => "off",
            "site_paypal_mode" => "sandbox",
            "site_paypal_client_id" => "",
            "site_paypal_secret_key" => "",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "invoice_prefix" => "#INV",
            "contract_prefix" => "#CON",
            "bug_prefix" => "#ISSUE",
            "estimation_prefix" => "#EST",
            "invoice_template" => "template1",
            "invoice_color" => "fffff",
            "invoice_logo" => "",
            "estimation_template" => "template1",
            "estimation_color" => "fffff",
            "estimation_logo" => "",
            "default_language" => "en",
            "enable_landing" => "yes",
            "footer_title" => "Payment Information",
            "footer_note" => "Thank you for your business.",
            "signup_button" => 'on',
            'dark_mode' => 'off',
            'is_sidebar_transperent' => 'on',
            'theme_color' => 'theme-3',
            'company_logo_light' => '2light_logo.png',
            'company_logo' => '2logo_dark.png',
            'logo_light' => 'logo-light.png',
            'logo' => 'logo-dark.png',
            'SITE_RTL' => 'off',
            'DISPLAY_LANDING' => 'on',

            "storage_setting" => "local",
            "local_storage_validation" => "jpg,png,jpeg,xls,pdf,xlsx",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
        ];


        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        // $user = User::where('type','super admin')->first();

        // if(!is_null($user)){
        //     $theme_setting = DB::table('settings')->where('created_by',$user->id)->whereIn('name',['theme_color','is_sidebar_transperent','dark_mode','SITE_RTL'])->get();

        //     if(count($theme_setting)>0){
        //         foreach($theme_setting as $row)
        //         {
        //             $settings[$row->name] = $row->value;
        //         }
        //     }
        // }

        return $settings;
    }

    public static function getStorageSetting()
    {

        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1);
        $data     = $data->get();
        $settings = [
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,png,jpeg,xls,pdf,xlsx",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url"    => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function get_cookie_var($var)
    {
        $data = '';
        if ($var == 'SITE_RTL') {
            $data = !empty(Cookie::get('SITE_RTL')) ? Cookie::get('SITE_RTL') : '';
        } else if ($var == 'THEME_COLOR') {
            $data = !empty(Cookie::get('THEME_COLOR')) ? Cookie::get('THEME_COLOR') : 'theme-3';
        } else if ($var == 'is_sidebar_transperent') {
            $is_sidebar_transperent = !empty(Cookie::get('is_sidebar_transperent')) ? Cookie::get('is_sidebar_transperent') : 'on';
            $SITE_RTL = !empty(Cookie::get('SITE_RTL')) ? Cookie::get('SITE_RTL') : '';
            $data = ($is_sidebar_transperent == 'on' || $SITE_RTL == 'on') ? 'on' : 'off';
        } else if ($var == 'dark_mode') {
            $SITE_RTL = !empty(Cookie::get('SITE_RTL')) ? Cookie::get('SITE_RTL') : '';
            $dark_mode = !empty(Cookie::get('dark_mode')) ? Cookie::get('dark_mode') : '';
            $data = ($dark_mode != 'on' || $SITE_RTL == 'on') ? 'off' : 'on';
        }

        return $data;
    }

    public static function settingsById($id)
    {
        $data = DB::table('settings');
        $invoice = Invoice::where('id', $id)->first();

        if ($invoice) {
            $users = User::where('id', $invoice->created_by)->first();
        } else {
            $users = User::where('id', \Auth::user()->id)->first();
        }

        $data->where('created_by', '=', $users->id);

        $data = $data->get();

        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "site_enable_stripe" => "off",
            "site_stripe_key" => "",
            "site_stripe_secret" => "",
            "site_enable_paypal" => "off",
            "site_paypal_mode" => "sandbox",
            "site_paypal_client_id" => "",
            "site_paypal_secret_key" => "",
            "site_currency_symbol_position" => "pre",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "invoice_prefix" => "#INV",
            "contract_prefix" => "#CON",
            "bug_prefix" => "#ISSUE",
            "estimation_prefix" => "#EST",
            "invoice_template" => "template1",
            "invoice_color" => "fffff",
            "invoice_logo" => "",
            "estimation_template" => "template1",
            "estimation_color" => "fffff",
            "estimation_logo" => "",
            "default_language" => "en",
            "enable_landing" => "yes",
            "footer_title" => "Payment Information",
            "footer_note" => "Thank you for your business.",
            'dark_mode' => 'off',
            'is_sidebar_transperent' => 'on',
            'theme_color' => 'theme-3',
            'company_logo_light' => '2light_logo.png',
            'company_logo' => '2logo_dark.png',
            'logo_light' => 'logo-light.png',
            'logo' => 'logo-dark.png',
            'DISPLAY_LANDING' => 'on',
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;

        $user = User::where('type', 'super admin')->first();

        if (!is_null($user)) {
            $theme_setting = DB::table('settings')->where('created_by', $user->id)->whereIn('name', ['theme_color', 'is_sidebar_transperent', 'dark_mode', 'SITE_RTL'])->get();

            if (count($theme_setting) > 0) {
                foreach ($theme_setting as $row) {
                    $settings[$row->name] = $row->value;
                }
            }
        }
    }

    public static function payment_settings()
    {
        $data = DB::table('admin_payment_settings');
        if (Auth::check()) {
            $data->where('created_by', '=', Auth::user()->createId());
        } else {
            $data->where('created_by', '=', 1);
        }
        $data = $data->get();
        $res = [];
        foreach ($data as $key => $value) {
            $res[$value->name] = $value->value;
        }
        return $res;
    }


    public static function invoice_payment_settings($id)
    {
        $data = [];
        $user = User::where(['id' => $id])->first();

        if (!is_null($user)) {
            $data = DB::table('admin_payment_settings');
            $data->where('created_by', '=', $user->id);
            $data = $data->get();
        }

        $res = [];
        foreach ($data as $key => $value) {
            $res[$value->name] = $value->value;
        }

        return $res;
    }

    public static function set_payment_settings()
    {
        $data = DB::table('admin_payment_settings');

        if (Auth::check()) {
            $data->where('created_by', '=', Auth::user()->creatorId());
        } else {
            $data->where('created_by', '=', 1);
        }
        $data = $data->get();
        $res = [];
        foreach ($data as $key => $value) {
            $res[$value->name] = $value->value;
        }

        return $res;
    }

    public static function error_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "error" : $msg;
        $msg_id    = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }

    public static function success_res($msg = "", $args = array())
    {
        $msg       = $msg == "" ? "success" : $msg;
        $msg_id    = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg       = $msg_id == $converted ? $msg : $converted;
        $json      = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }

    public static function getValByName($key)
    {
        $setting = self::settings();
        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }
        return $setting[$key];
    }

    public static function languages()
    {
        $dir     = base_path() . '/resources/lang/';
        $glob    = glob($dir . "*", GLOB_ONLYDIR);
        $arrLang = array_map(
            function ($value) use ($dir) {
                return str_replace($dir, '', $value);
            },
            $glob
        );
        $arrLang = array_map(
            function ($value) use ($dir) {
                return preg_replace('/[0-9]+/', '', $value);
            },
            $arrLang
        );
        $arrLang = array_filter($arrLang);

        return $arrLang;
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);

        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }

    public static function dateFormat($date)
    {
        $settings = self::settings();

        return date($settings['site_date_format'], strtotime($date));
    }

    public static function contractNumberFormat($number)
    {
        $settings = self::settings();

        return $settings["contract_prefix"] . sprintf("%05d", $number);
    }

    public static function invoiceNumberFormat($number)
    {
        $settings = self::settings();

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }

    public static function estimateNumberFormat($number)
    {
        $settings = self::settings();

        return $settings["estimation_prefix"] . sprintf("%05d", $number);
    }

    public static function sendNotification($type, $user_id, $obj)
    {
        if ($user_id != \Auth::user()->id) {
            $notification = Notification::create(
                [
                    'user_id' => $user_id,
                    'type' => $type,
                    'data' => json_encode($obj),
                    'is_read' => 0,
                ]
            );

            // Push Notification
            $options = array(
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            );

            $pusher          = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
            $data            = [];
            $data['html']    = $notification->toHtml();
            $data['user_id'] = $notification->user_id;

            if (!empty(env('PUSHER_APP_KEY')) && !empty(env('PUSHER_APP_SECRET')) && !empty(env('PUSHER_APP_ID'))) {
                $pusher->trigger('send_notification', 'notification', $data);
            }


            // End Push Notification
        }
    }

    public static function templateData()
    {
        $arr              = [];
        $arr['colors']    = [
            '003580',
            '666666',
            '6777f0',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];

        return $arr;
    }

    // Email Template Modules Function START
    // Common Function That used to send mail with check all cases
    public static function sendEmailTemplate($emailTemplate, $user_id, $obj)
    {
        // dd($emailTemplate);
        $usr = Auth::user();
        if ($user_id != $usr->id) {
            $mailTo = User::find($user_id)->email;

            if ($usr->type != 'super admin') {
                // find template is exist or not in our record
                $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();

                if (isset($template) && !empty($template)) {
                    // check template is active or not by company
                    $is_active = UserEmailTemplate::where('template_id', '=', $template->id)->where('user_id', '=', $usr->creatorId())->first();

                    if ($is_active->is_active == 1) {
                        $settings = self::settings();

                        // get email content language base
                        $content       = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $usr->lang)->first();
                        $content->from = $template->from;
                        if (!empty($content->content)) {
                            $content->content = self::replaceVariable($emailTemplate, $content->content, $obj);

                            // send email
                            try {
                                Mail::to($mailTo)->send(new CommonEmailTemplate($content, $settings));
                            } catch (\Exception $e) {
                                $error = __('E-Mail has been not sent due to SMTP configuration');
                            }

                            if (isset($error)) {
                                $arReturn = [
                                    'is_success' => false,
                                    'error' => $error,
                                ];
                            } else {
                                $arReturn = [
                                    'is_success' => true,
                                    'error' => false,
                                ];
                            }
                        } else {
                            $arReturn = [
                                'is_success' => false,
                                'error' => __('Mail not send, email is empty'),
                            ];
                        }

                        return $arReturn;
                    } else {
                        return [
                            'is_success' => true,
                            'error' => false,
                        ];
                    }
                } else {
                    return [
                        'is_success' => false,
                        'error' => __('Mail not send, email not found'),
                    ];
                }
            }
        }
    }

    // used for replace email variable (parameter 'template_name','id(get particular record by id for data)')
    public static function replaceVariable($name, $content, $obj)
    {
        $arrVariable = [
            '{project_name}',
            '{project_label}',
            '{project_status}',
            '{task_name}',
            '{task_priority}',
            '{task_status}',
            '{task_old_stage}',
            '{task_new_stage}',
            '{estimation_name}',
            '{estimation_client}',
            '{estimation_status}',
            '{app_name}',
            '{company_name}',
            '{email}',
            '{password}',
            '{app_url}',
            '{contract_client}',
            '{contract_subject}',
            '{contract_project}',
            '{contract_start_date}',
            '{contract_end_date}',
        ];
        $arrValue    = [
            'project_name' => '-',
            'project_label' => '-',
            'project_status' => '-',
            'task_name' => '-',
            'task_priority' => '-',
            'task_status' => '-',
            'task_old_stage' => '-',
            'task_new_stage' => '-',
            'estimation_name' => '-',
            'estimation_client' => '-',
            'estimation_status' => '-',
            'app_name' => '-',
            'company_name' => '-',
            'email' => '-',
            'password' => '-',
            'app_url' => '-',
            'contract_client' => '-',
            'contract_subject' => '-',
            'contract_project' => '-',
            'contract_start_date' => '-',
            'contract_end_date' => '-',
        ];

        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }

        $arrValue['app_name']     = env('APP_NAME');
        $arrValue['company_name'] = self::settings()['company_name'];
        $arrValue['app_url']      = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';

        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    // Make Entry in email_tempalte_lang table when create new language
    public static function makeEmailLang($lang)
    {
        $template = EmailTemplate::all();
        foreach ($template as $t) {
            $default_lang                 = EmailTemplateLang::where('parent_id', '=', $t->id)->where('lang', 'LIKE', 'en')->first();
            $emailTemplateLang            = new EmailTemplateLang();
            $emailTemplateLang->parent_id = $t->id;
            $emailTemplateLang->lang      = $lang;
            $emailTemplateLang->subject   = $default_lang->subject;
            $emailTemplateLang->content   = $default_lang->content;
            $emailTemplateLang->save();
        }
    }
    // Email Template Modules Function END

    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R   = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for ($i = 0; $i < count($C); ++$i) {
            if ($C[$i] <= 0.03928) {
                $C[$i] = $C[$i] / 12.92;
            } else {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if ($L > 0.179) {
            $color = 'black';
        } else {
            $color = 'white';
        }

        return $color;
    }

    // Function not used any where just create for translate some keyword language based.
    public function extraKeyword()
    {
        [
            __('account'),
            __('user'),
            __('client'),
            __('role'),
            __('company settings'),
            __('project'),
            __('product'),
            __('lead'),
            __('lead stage'),
            __('project stage'),
            __('lead source'),
            __('label'),
            __('product unit'),
            __('expense category'),
            __('expense'),
            __('tax'),
            __('invoice'),
            __('payment'),
            __('invoice product'),
            __('invoice payment'),
            __('task'),
            __('checklist'),
            __('plan'),
            __('note'),
            __('bug report'),
            __('timesheet'),
            __('language'),
            __('permission'),
            __('system settings'),
            __('Create Milestone'),
            __('Upload File'),
            __('Create Task'),
            __('Move'),
        ];
    }

    // End
    public static function get_messenger_packages_migration()
    {
        $totalMigration = 0;
        $messengerPath  = glob(base_path() . '/vendor/munafio/chatify/database/migrations' . DIRECTORY_SEPARATOR . '*.php');
        if (!empty($messengerPath)) {
            $messengerMigration = str_replace('.php', '', $messengerPath);
            $totalMigration     = count($messengerMigration);
        }

        return $totalMigration;
    }

    public static function getDateFormated($date, $time = false)
    {
        if (!empty($date) && $date != '0000-00-00') {
            if ($time == true) {
                return date("d M Y H:i A", strtotime($date));
            } else {
                return date("d M Y", strtotime($date));
            }
        } else {
            return '';
        }
    }

    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public static function diffance_to_time($start, $end)
    {
        $start         = new Carbon($start);
        $end           = new Carbon($end);
        $totalDuration = $start->diffInSeconds($end);

        return $totalDuration;
    }

    public static function second_to_time($seconds = 0)
    {
        $H = floor($seconds / 3600);
        $i = ($seconds / 60) % 60;
        $s = $seconds % 60;

        $time = sprintf("%02d:%02d:%02d", $H, $i, $s);

        return $time;
    }

    public static function send_slack_msg($msg)
    {

        $settings  = Utility::settings(Auth::user()->createId());

        if (isset($settings['slack_webhook']) && !empty($settings['slack_webhook'])) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $settings['slack_webhook']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $msg]));

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
    }

    public static function send_telegram_msg($resp)
    {
        $settings  = Utility::settings(\Auth::user()->creatorId());

        $msg = $resp;

        // Set your Bot ID and Chat ID.
        $telegrambot    = $settings['telegram_token'];
        $telegramchatid = $settings['telegram_chatid'];
        // Function call with your own text or variable
        $url     = 'https://api.telegram.org/bot' . $telegrambot . '/sendMessage';
        $data    = array(
            'chat_id' => $telegramchatid,
            'text' => $msg,
        );
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type:application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);
        $url     = $url;
    }

    public static function colorset()
    {
        if (\Auth::user()) {
            if (\Auth::user()->type == 'super admin') {
                $user = \Auth::user();
                $setting = DB::table('settings')->where('created_by', $user->id)->pluck('value', 'name')->toArray();
            } else {
                $setting = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->pluck('value', 'name')->toArray();
            }
        } else {
            $user = User::where('type', 'super admin')->first();
            //$setting = DB::table('settings')->where('created_by', $user->id)->pluck('value', 'name')->toArray();
            $setting = DB::table('settings')->pluck('value', 'name')->toArray();
        }
        if (!isset($setting['color'])) {
            $setting = Utility::settings();
        }
        return $setting;
    }

    public static function GetLogo()
    {
        $setting = Utility::colorset();
        if ($setting['dark_mode'] == 'on') {
            return 'logo-light.png';
        } else {
            return 'logo-dark.png';
        }
    }

    public static function getLayoutsSetting()
    {
        $data = DB::table('settings');

        if (\Auth::check()) {
            $data = $data->where('created_by', '=', \Auth::user()->creatorId())->get();

            if (count($data) == 0) {
                $data = DB::table('settings')->where('created_by', '=', 1)->get();
            }
        } else {
            $data = $data->where('created_by', '=', 1)->get();
        }

        $settings = [
            "is_sidebar_transperent" => "on",
            "dark_mode" => "off",
            "theme_color" => "theme-3",
            "SITE_RTL" => "off",
            "company_logo_light" => "",
            "company_logo" => "",
            "company_favicon" => "",
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function get_superadmin_logo()
    {
        $is_dark_mode = Cookie::get('dark_mode');
        if ($is_dark_mode == 'on') {
            return 'logo-light.png';
        } else {
            return 'logo-dark.png';
        }
    }

    public static function get_company_logo()
    {
        $is_dark_mode = Cookie::get('dark_mode');
        if ($is_dark_mode == 'on') {

            return Utility::getValByName('company_logo_light');
        } else {
            return Utility::getValByName('company_logo');
        }
    }

    public static function getFirstSeventhWeekDay($week = null)
    {
        $first_day = $seventh_day = null;

        if (isset($week)) {
            $first_day   = Carbon::now()->addWeeks($week)->startOfWeek();
            $seventh_day = Carbon::now()->addWeeks($week)->endOfWeek();
        }

        $dateCollection['first_day']   = $first_day;
        $dateCollection['seventh_day'] = $seventh_day;

        $period = CarbonPeriod::create($first_day, $seventh_day);

        foreach ($period as $key => $dateobj) {
            $dateCollection['datePeriod'][$key] = $dateobj;
        }

        return $dateCollection;
    }

    public static function upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $settings = Utility::getStorageSetting();
            //    dd($settings);

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';
                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes =  !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';
                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048000000';

                    $mimes =  !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '2048000000';
                }


                $file = $request->$key_name;


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];
                }
                $validator = \Validator::make($request->all(), [
                    $key_name => $validation
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {
                        $request->$key_name->move(storage_path($path), $name);
                        $path = $path . $name;
                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;

                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );
                        // $path = $path.$name;
                        // dd($path);
                    }


                    $res = [
                        'flag' => 1,
                        'msg'  => 'success',
                        'url'  => $path
                    ];
                    return $res;
                }
            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }
        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function get_file($path)
    {
        $settings = Utility::getStorageSetting();

        try {
            if ($settings['storage_setting'] == 'wasabi') {
                config(
                    [
                        'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com'
                    ]
                );
            } elseif ($settings['storage_setting'] == 's3') {
                config(
                    [
                        'filesystems.disks.s3.key' => $settings['s3_key'],
                        'filesystems.disks.s3.secret' => $settings['s3_secret'],
                        'filesystems.disks.s3.region' => $settings['s3_region'],
                        'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                        'filesystems.disks.s3.use_path_style_endpoint' => false,
                    ]
                );
            }

            return \Storage::disk($settings['storage_setting'])->url($path);
        } catch (\Throwable $th) {
            return '';
        }
    }

    /** Get user details by id **/
    public static function getAssignedUserDetails($assignto)
    {

        //return gettype($assignto);

        if (gettype($assignto) == ('integer')) {
            $userInfo = User::where('id', $assignto)->get();
            if (!empty($userInfo)) {
                return $userInfo->toArray();
            }
        } else if (gettype($assignto) == 'array') {
            $userInfo = User::whereIn('id', $assignto)->get();
            if (!empty($userInfo)) {
                return $userInfo->toArray();
            }
        }
    }

    /** Get group name by id */
    public static function getGroupDetailsByID($groupid)
    {
        if ($groupid != 0) {
            $groupinfo = TaskGroup::where('id', $groupid)->first();

            if (!empty($groupinfo)) {
                return $groupinfo->toArray();
            }
        } else {
            $nogrp = array("name" => "No Group");
            return $nogrp;
        }
    }

    /**
     * Function that groups an array of associative arrays by some key.
     *
     * @param {String} $key Property to sort by.
     * @param {Array} $data Array that stores multiple associative arrays.
     */
    public static function group_by($key, $data)
    {
        $result = array();

        foreach ($data as $val) {
            if (array_key_exists($key, $val)) {
                $result[$val[$key]][] = $val;
            } else {
                $result[""][] = $val;
            }
        }

        return $result;
    }

    /**
     * getTeamMembers
     *
     * @param  mixed $managerID
     * @return void | array
     */
    public static function getTeamMembers($managerID)
    {
        $memberId = User::where('user_parent_id', $managerID)->pluck('id');
        $memberId = (!empty($memberId)) ? $memberId->toArray() : null;
        return $memberId;
    }

    /**
     * getTaskGroupNameByGroupID
     *
     * @param  mixed $groupID
     * @return void | string
     */
    public static function getTaskGroupNameByGroupID($groupID)
    {

        if ($groupID != 0) {
            $group_result = TaskGroup::find($groupID, 'name');
        }


        $group_name = (!empty($group_result)) ? $group_result->name : 'No Group';

        return $group_name;
    }
}
