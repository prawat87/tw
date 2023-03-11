<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{TaxController, NoteController, PlanController, RoleController, UserController, LeadsController, ClientController, CouponController, LabelsController, SystemController, ExpenseController, InvoiceController, PaymentController, CalenderController, LanguageController, ProductsController, ProjectsController, BugStatusController, DashboardController, TaskGroupController, EstimationController, LeadsourceController, LeadstagesController, PermissionController, ProductunitsController, EmailTemplateController, ProjectstagesController, StripePaymentController, ExpensesCategoryController};
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');


require __DIR__ . '/auth.php';

Route::get('/invoices/pay/{invoice}', [InvoiceController::class, 'payinvoice',])->name('pay.invoice');

Route::get('/estimations/pay/{estimation}', [EstimationController::class, 'payestimation',])->name('pay.estimation');

Route::get('/register/{lang?}', [Auth\RegisteredUserController::class, 'showRegistrationForm'])->name('register');

Route::get('/login/{lang?}', [Auth\AuthenticatedSessionController::class, 'showLoginForm'])->name('login');

Route::get('/reset/password/{lang?}', [Auth\AuthenticatedSessionController::class, 'showLinkRequestForm'])->name('password.request');

Route::get('searchJson', [ProjectsController::class, 'getSearchJson'])->name('search.json')->middleware(['auth', 'XSS']);

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['XSS']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'XSS']);

Route::get('profile', [UserController::class, 'profile'])->name('profile')->middleware(['auth', 'XSS']);

Route::post('edit-profile', [UserController::class, 'editprofile'])->name('update.account')->middleware(['auth', 'XSS']);

Route::resource('users', UserController::class)->middleware(['auth', 'XSS']);

Route::resource('users', UserController::class)->middleware(['auth', 'XSS']);

Route::post('change-password', [UserController::class, 'updatePassword'])->name('update.password');

Route::resource('clients', ClientController::class)->middleware(['auth', 'XSS']);
Route::resource('roles', RoleController::class)->middleware(['auth', 'XSS']);
Route::resource('permissions', PermissionController::class)->middleware(['auth', 'XSS']);


Route::group(['middleware' => ['auth', 'XSS']], function () {
    Route::get('change-language/{lang}', [LanguageController::class, 'changeLanquage'])->name('change.language');
    Route::get('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language');
    Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data');
    Route::get('create-language', [LanguageController::class, 'createLanguage'])->name('create.language');
    Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language');
    Route::delete('destroy-language/{lang}', [LanguageController::class, 'destroyLang'])->name('destroy.language');
});

Route::group(['middleware' => ['auth', 'XSS']], function () {
    Route::resource('systems', SystemController::class);
    Route::post('email-settings', [SystemController::class, 'saveEmailSettings'])->name('email.settings');
    Route::post('company-settings', [SystemController::class, 'saveCompanySettings'])->name('company.settings');
    Route::post('payment-settings', [SystemController::class, 'savePaymentSettings'])->name('payment.settings');
    Route::post('company-payment-settings', [SystemController::class, 'saveCompanyPaymentSettings'])->name('company.payment.settings');
    Route::post('pusher-settings', [SystemController::class, 'savePusherSettings'])->name('pusher.settings');
    Route::get('settings', [SystemController::class, 'companyIndex'])->name('settings');

    Route::post('/template-setting', [SystemController::class, 'saveTemplateSettings'])->name('template.setting');
    Route::post('/test', [SystemController::class, 'testEmail'])->name('test.email');
    Route::post('/test/send', [SystemController::class, 'testEmailSend'])->name('test.email.send');
});


Route::post('system-settings', [SystemController::class, 'saveSystemSettings'])->name('system.settings');
Route::post('storage-settings', [SystemController::class, 'storageSettingStore'])->name('storage.setting.store')->middleware(['auth', 'XSS']);

Route::group(['middleware' => ['auth', 'XSS']], function () {
    Route::resource('leadstages', LeadstagesController::class);
    Route::post('/leadstages/order', [LeadstagesController::class, 'order',])->name('leadstages.order');
});

Route::group(['middleware' => ['auth', 'XSS']], function () {
    Route::resource('projectstages', ProjectstagesController::class);
    Route::post('/projectstages/order', [ProjectstagesController::class, 'order',])->name('projectstages.order');
});

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::resource('leadsources', LeadsourceController::class);
    }
);

Route::resource('labels', LabelsController::class)->middleware(['auth', 'XSS']);
//Route::resource('taskgroup', TaskGroupController::class, ['names' => 'taskgroup'])->middleware(['auth', 'XSS']);
Route::resource('productunits', ProductunitsController::class)->middleware(['auth', 'XSS']);
Route::resource('expensescategory', ExpensesCategoryController::class)->middleware(['auth', 'XSS']);
Route::post('/leads/order', [LeadsController::class, 'order',])->name('leads.order')->middleware(['auth', 'XSS']);
Route::resource('leads', LeadsController::class)->middleware(['auth', 'XSS']);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::post('projects/{id}/status', [ProjectsController::class, 'updateStatus'])->name('projects.update.status');
        Route::resource('projects', ProjectsController::class);
        Route::get('project-invite/{project_id}', [ProjectsController::class, 'userInvite'])->name('project.invite');
        Route::post('invite/{project}', [ProjectsController::class, 'Invite'])->name('invite');
        Route::delete('project/{project_id}/user/{id}', [ProjectsController::class, 'removeUser'])->name('project.remove.user');

        Route::get('projects/{id}/milestone', [ProjectsController::class, 'milestone'])->name('project.milestone');
        Route::post('projects/{id}/milestone', [ProjectsController::class, 'milestoneStore'])->name('project.milestone.store');
        Route::get('projects/milestone/{id}/edit', [ProjectsController::class, 'milestoneEdit'])->name('project.milestone.edit');
        Route::post('projects/milestone/{id}/update', [ProjectsController::class, 'milestoneUpdate'])->name('project.milestone.update');
        Route::delete('projects/milestone/{id}', [ProjectsController::class, 'milestoneDestroy'])->name('project.milestone.destroy');
        Route::get('projects/milestone/{id}/show', [ProjectsController::class, 'milestoneShow'])->name('project.milestone.show');

        Route::post('projects/{id}/file', [ProjectsController::class, 'fileUpload'])->name('project.file.upload');
        Route::get('projects/{id}/file/{fid}', [ProjectsController::class, 'fileDownload'])->name('projects.file.download');
        Route::delete('projects/{id}/file/delete/{fid}', [ProjectsController::class, 'fileDelete'])->name('projects.file.delete');

        Route::get('projects/{id}/taskboard', [ProjectsController::class, 'taskBoard'])->name('project.taskboard');
        Route::get('projects/{id}/taskboard/create', [ProjectsController::class, 'taskCreate'])->name('task.create');
        Route::post('projects/{id}/taskboard/store', [ProjectsController::class, 'taskStore'])->name('task.store');
        Route::get('projects/taskboard/{id}/edit', [ProjectsController::class, 'taskEdit'])->name('task.edit');
        Route::post('projects/taskboard/{id}/update', [ProjectsController::class, 'taskUpdate'])->name('task.update');
        Route::delete('projects/taskboard/{id}/delete', [ProjectsController::class, 'taskDestroy'])->name('task.destroy');
        Route::get('projects/taskboard/{id}/show', [ProjectsController::class, 'taskShow'])->name('task.show');
        Route::post('projects/order', [ProjectsController::class, 'order'])->name('taskboard.order');

        Route::post('projects/{id}/taskboard/{tid}/comment', [ProjectsController::class, 'commentStore'])->name('comment.store');
        Route::post('projects/taskboard/{id}/file', [ProjectsController::class, 'commentStoreFile'])->name('comment.file.store');
        Route::delete('projects/taskboard/comment/{id}', [ProjectsController::class, 'commentDestroy'])->name('comment.destroy');
        Route::delete('projects/taskboard/file/{id}', [ProjectsController::class, 'commentDestroyFile'])->name('comment.file.destroy');


        Route::post('projects/taskboard/{id}/checklist/store', [ProjectsController::class, 'checkListStore'])->name('task.checklist.store');
        Route::post('projects/taskboard/{id}/checklist/{cid}/update', [ProjectsController::class, 'checklistUpdate'])->name('task.checklist.update');
        Route::delete('projects/taskboard/{id}/checklist/{cid}', [ProjectsController::class, 'checklistDestroy'])->name('task.checklist.destroy');


        Route::get('projects/{id}/client/{cid}/permission', [ProjectsController::class, 'clientPermission'])->name('client.permission');
        Route::post('projects/{id}/client/{cid}/permission/store', [ProjectsController::class, 'storeClientPermission'])->name('client.store.permission');


        Route::get('timesheet', [ProjectsController::class, 'timeSheet'])->name('task.timesheetRecord');
        Route::get('team-timesheet', [ProjectsController::class, 'teamTimeSheet'])->name('task.team.timesheetRecord');
        Route::get('timesheet/create', [ProjectsController::class, 'timeSheetCreate'])->name('task.timesheet');
        Route::post('timesheet/create', [ProjectsController::class, 'timeSheetStore'])->name('task.timesheet.store');
        Route::get('timesheet/{tid}/edit', [ProjectsController::class, 'timeSheetEdit'])->name('task.timesheet.edit');
        Route::get('team-timesheet/{uid}/{tid}/edit', [ProjectsController::class, 'teamTimeSheetEdit'])->name('task.team.timesheet.edit');
        Route::post('timesheet/showmore', [ProjectsController::class, 'timeSheetShowMore'])->name('task.timesheet.showmore');
        Route::post('timesheet/teamshowmore', [ProjectsController::class, 'teamTimeSheetShowMore'])->name('task.timesheet.teamshowmore');
        Route::post('timesheet/{tid}/update', [ProjectsController::class, 'timeSheetUpdate'])->name('task.timesheet.update');
        Route::delete('timesheet/{tid}/destroy', [ProjectsController::class, 'timeSheetDestroy'])->name('task.timesheet.destroy');
        Route::post('timesheet/project/task', [ProjectsController::class, 'projectTask'])->name('timesheet.project.task');

        // my-time filter
        Route::post('timesheet/filter', [ProjectsController::class, 'timeSheetFilter'])->name('timesheet.entries.filter');
        // team timesheet filter
        Route::post('teamtimesheet/filter', [ProjectsController::class, 'teamTimeSheetFilter'])->name('teamtimesheet.entries.filter');

        Route::post('projects/bug/kanban/order', [ProjectsController::class, 'bugKanbanOrder'])->name('bug.kanban.order');
        Route::get('projects/{id}/bug/kanban', [ProjectsController::class, 'bugKanban'])->name('task.bug.kanban');
        Route::get('projects/{id}/bug', [ProjectsController::class, 'bug'])->name('task.bug');
        Route::get('projects/{id}/bug/create', [ProjectsController::class, 'bugCreate'])->name('task.bug.create');
        Route::post('projects/{id}/bug/store', [ProjectsController::class, 'bugStore'])->name('task.bug.store');
        Route::get('projects/{id}/bug/{bid}/edit', [ProjectsController::class, 'bugEdit'])->name('task.bug.edit');
        Route::post('projects/{id}/bug/{bid}/update', [ProjectsController::class, 'bugUpdate'])->name('task.bug.update');
        Route::delete('projects/{id}/bug/{bid}/destroy', [ProjectsController::class, 'bugDestroy'])->name('task.bug.destroy');


        Route::get('projects/{id}/bug/{bid}/show', [ProjectsController::class, 'bugShow'])->name('task.bug.show');
        Route::post('projects/{id}/bug/{bid}/comment', [ProjectsController::class, 'bugCommentStore'])->name('bug.comment.store');
        Route::post('projects/bug/{bid}/file', [ProjectsController::class, 'bugCommentStoreFile'])->name('bug.comment.file.store');
        Route::delete('projects/bug/comment/{id}', [ProjectsController::class, 'bugCommentDestroy'])->name('bug.comment.destroy');
        Route::delete('projects/bug/file/{id}', [ProjectsController::class, 'bugCommentDestroyFile'])->name('bug.comment.file.destroy');

        //Task Group routes
        Route::get('projects/{id}/taskgroup', [ProjectsController::class, 'taskGroup'])->name('project.taskgroup');
        Route::get('projects/{id}/taskgroup/create', [ProjectsController::class, 'taskgroupCreate'])->name('taskgroup.create');
        Route::post('projects/{id}/taskgroup/store', [ProjectsController::class, 'taskgroupStore'])->name('taskgroup.store');
        Route::get('projects/taskgroup/{id}/edit', [ProjectsController::class, 'taskgroupEdit'])->name('taskgroup.edit');
        Route::post('projects/taskgroup/{id}/update', [ProjectsController::class, 'taskgroupUpdate'])->name('taskgroup.update');
        Route::delete('projects/taskgroup/{id}/delete', [ProjectsController::class, 'taskgroupDestroy'])->name('taskgroup.destroy');
        Route::get('projects/taskgroup/{id}/show', [ProjectsController::class, 'taskgroupShow'])->name('taskgroup.show');
    }
);


Route::post('calender/event/date', [CalenderController::class, 'dropEventDate'])->name('calender.event.date');
Route::resource('calendar', CalenderController::class)->middleware(['auth', 'XSS']);


Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::resource('bugstatus', BugStatusController::class);
        Route::post('/bugstatus/order', [BugStatusController::class, 'order',])->name('bugstatus.order');
    }
);

Route::group(
    [
        'middleware' => [
            'auth',
            'XSS',
        ],
    ],
    function () {
        Route::resource('invoices', InvoiceController::class);
        Route::get('invoices/{id}/products', [InvoiceController::class, 'productAdd'])->name('invoices.products.add');
        Route::get('invoices/{id}/products/{pid}', [InvoiceController::class, 'productEdit'])->name('invoices.products.edit');
        Route::post('invoices/{id}/products', [InvoiceController::class, 'productStore'])->name('invoices.products.store');
        Route::post('invoices/{id}/products/{pid}/update', [InvoiceController::class, 'productUpdate'])->name('invoices.products.update');
        Route::delete('invoices/{id}/products/{pid}', [InvoiceController::class, 'productDelete'])->name('invoices.products.delete');
        Route::post('invoices/milestone/task', [InvoiceController::class, 'milestoneTask'])->name('invoices.milestone.task');


        Route::get('invoices-payments', [InvoiceController::class, 'payments'])->name('invoices.payments');
        Route::get('invoices/{id}/payments', [InvoiceController::class, 'paymentAdd'])->name('invoices.payments.create');
        Route::post('invoices/{id}/payments', [InvoiceController::class, 'paymentStore'])->name('invoices.payments.store');


        Route::get('invoice/{id}/payment/reminder', [InvoiceController::class, 'paymentReminder'])->name('invoice.payment.reminder');
        Route::get('invoice/{id}/sent', [InvoiceController::class, 'sent'])->name('invoice.sent');
        Route::get('invoice/{id}/custom-send', [InvoiceController::class, 'customMail'])->name('invoice.custom.send');
        Route::post('invoice/{id}/custom-mail', [InvoiceController::class, 'customMailSend'])->name('invoice.custom.mail');

        Route::get('/invoices/preview/{template}/{color}', [InvoiceController::class, 'previewInvoice'])->name('invoice.preview');
    }
);

Route::get('invoices/{id}/get_invoice', [InvoiceController::class, 'printInvoice'])->name('get.invoice');

Route::resource('taxes', TaxController::class)->middleware(['auth', 'XSS']);
Route::get('user/{id}/plan', [UserController::class, 'upgradePlan'])->name('plan.upgrade')->middleware(['auth', 'XSS']);
Route::get('user/{id}/plan/{pid}', [UserController::class, 'activePlan'])->name('plan.active')->middleware(['auth', 'XSS']);
Route::resource('plans', PlanController::class)->middleware(['auth', 'XSS']);
Route::post('/user-plans/', [PlanController::class, 'userPlan'])->name('update.user.plan')->middleware(['auth', 'XSS']);



// For Notification

Route::get('/{uid}/notification/seen', [UserController::class, 'notificationSeen'])->name('notification.seen');

// end for notification

Route::resource('products', ProductsController::class)->middleware(['auth', 'XSS']);
Route::resource('expenses', ExpenseController::class)->middleware(['auth', 'XSS']);
Route::resource('payments', PaymentController::class)->middleware(['auth', 'XSS']);
Route::resource('notes', NoteController::class)->middleware(['auth', 'XSS']);

Route::group(['middleware' => ['auth', 'XSS']], function () {
    Route::get('/orders', [StripePaymentController::class, 'index'])->name('order.index');
    Route::post('/stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');
    Route::get('/stripe-payment-status', [StripePaymentController::class, 'planGetStripePaymentStatus'])->name('stripe.payment.status');
});

// Estimation
Route::get('/estimations/{id}/products/{pid}', [EstimationController::class, 'productEdit'])->name('estimations.products.edit')->middleware(['auth', 'XSS']);
Route::post('/estimations/{id}/products/{pid}/update', [EstimationController::class, 'productUpdate'])->name('estimations.products.update')->middleware(['auth', 'XSS']);
Route::delete('/estimations/{id}/products/{pid}', [EstimationController::class, 'productDelete'])->name('estimations.products.delete')->middleware(['auth', 'XSS']);
Route::get('/estimations/{id}/products', [EstimationController::class, 'productAdd'])->name('estimations.products.add')->middleware(['auth', 'XSS']);
Route::post('/estimations/{id}/products', [EstimationController::class, 'productStore'])->name('estimations.products.store')->middleware(['auth', 'XSS']);
Route::get('estimations/{id}/get_estimation', [EstimationController::class, 'printEstimation'])->name('get.estimation')->middleware('XSS');


Route::get('/estimations/preview/{template}/{color}', [EstimationController::class, 'previewEstimation'])->name('estimations.preview');

// end Estimation

Route::get('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon')->middleware(['auth', 'XSS']);

Route::resource('coupons', CouponController::class)->middleware(['auth', 'XSS']);
Route::resource('estimations', EstimationController::class)->middleware(['auth', 'XSS']);

// Email Templates


Route::resource('email_template', EmailTemplateController::class)->middleware(['auth', 'XSS']);
Route::resource('email_template_lang', EmailTemplateLangController::class)->middleware(['auth', 'XSS']);
Route::get('email_template_lang/{id}/{lang?}', [EmailTemplateController::class, 'manageEmailLang'])->name('manage.email.language')->middleware(['auth', 'XSS']);
Route::post('email_template_store/{pid}', [EmailTemplateController::class, 'storeEmailLang'])->name('store.email.language')->middleware(['auth']);
Route::post('email_template_status/{id}', [EmailTemplateController::class, 'updateStatus'])->name('status.email.language')->middleware(['auth']);


// End Email Templates
Route::post('business-setting', [SystemController::class, 'saveBusinessSettings'])->name('business.setting');


//Route::post('business-setting', 'SystemController@saveBusinessSettings')->name('business.setting');

Route::post('/plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware('XSS');
Route::get('/{id}/plan-get-payment-status', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware('XSS');


Route::post('/planpayment', [PaymentWallController::class, 'planpay'])->name('paymentwall')->middleware(['auth', 'XSS']);
Route::post('/paymentwall-payment/{plan}', [PaymentWallController::class, 'planPayWithPaymentWall'])->name('paymentwall.payment')->middleware(['auth', 'XSS']);
Route::get('/plan/error/{flag}', [PaymentWallController::class, 'planerror'])->name('error.plan.show');


Route::post('/invoices/{id}/payment', [InvoiceController::class, 'addPayment'])->name('client.invoice.payment')->middleware(['auth', 'XSS']);
Route::post('/{id}/pay-with-paypal', [PaypalController::class, 'clientPayWithPaypal'])->name('client.pay.with.paypal')->middleware('XSS');
Route::get('/{id}/get-payment-status/{amount}', [PaypalController::class, 'clientGetPaymentStatus'])->name('client.get.payment.status')->middleware('XSS');


//===================================================== payment ========================================//

Route::get('/payment/{code}', [PlanController::class, 'payment'])->name('payment');
Route::post('/prepare-payment', [PlanController::class, 'preparePayment'])->name('prepare.payment')->middleware(['auth', 'XSS']);
Route::post('/avtivePlan', [PlanController::class, 'avtivePlan'])->name('avtivePlan')->middleware(['auth', 'XSS']);


Route::get('plan_request', [PlanRequestController::class, 'index'])->name('plan_request.index')->middleware(['auth', 'XSS',]);
Route::get('request_frequency/{id}', [PlanRequestController::class, 'requestView'])->name('request.view')->middleware(['auth', 'XSS',]);
Route::get('request_send/{id}', [PlanRequestController::class, 'userRequest'])->name('send.request')->middleware(['auth', 'XSS',]);
Route::get('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request')->middleware(['auth', 'XSS',]);
Route::get('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel')->middleware(['auth', 'XSS',]);


//================================= Plan Payment Gateways  ====================================//

Route::post('/plan-pay-with-paystack', [PaystackPaymentController::class, 'planPayWithPaystack'])->name('plan.pay.with.paystack')->middleware(['auth', 'XSS']);
Route::get('/plan/paystack/{pay_id}/{plan_id}', [PaystackPaymentController::class, 'getPaymentStatus'])->name('plan.paystack');


Route::post('/plan-pay-with-flaterwave', [FlutterwavePaymentController::class, 'planPayWithFlutterwave'])->name('plan.pay.with.flaterwave')->middleware(['auth', 'XSS']);
Route::get('/plan/flaterwave/{txref}/{plan_id}', [FlutterwavePaymentController::class, 'getPaymentStatus'])->name('plan.flaterwave');


Route::post('/plan-pay-with-razorpay', [RazorpayPaymentController::class, 'planPayWithRazorpay'])->name('plan.pay.with.razorpay')->middleware(['auth', 'XSS']);
Route::get('/plan/razorpay/{txref}/{plan_id}', [RazorpayPaymentController::class, 'getPaymentStatus'])->name('plan.razorpay');

Route::post('/plan-pay-with-paytm', [PaytmPaymentController::class, 'planPayWithPaytm'])->name('plan.pay.with.paytm')->middleware(['auth', 'XSS']);
Route::post('/plan/paytm/{plan}', [PaytmPaymentController::class, 'getPaymentStatus'])->name('plan.paytm')->middleware(['auth', 'XSS']);


Route::post('/plan-pay-with-mercado', [MercadoPaymentController::class, 'planPayWithMercado'])->name('plan.pay.with.mercado')->middleware(['auth', 'XSS']);
Route::get('/plan/mercado/{plan}', [MercadoPaymentController::class, 'getPaymentStatus'])->name('plan.mercado');


Route::post('/plan-pay-with-mollie', [MolliePaymentController::class, 'planPayWithMollie'])->name('plan.pay.with.mollie')->middleware(['auth', 'XSS']);
Route::get('/plan/mollie/{plan}', [MolliePaymentController::class, 'getPaymentStatus'])->name('plan.mollie');


Route::post('/plan-pay-with-skrill', [SkrillPaymentController::class, 'planPayWithSkrill'])->name('plan.pay.with.skrill')->middleware(['auth', 'XSS']);
Route::get('/plan/skrill/{plan}', [SkrillPaymentController::class, 'getPaymentStatus'])->name('plan.skrill');


Route::post('/plan-pay-with-coingate', [CoingatePaymentController::class, 'planPayWithCoingate'])->name('plan.pay.with.coingate')->middleware(['auth', 'XSS']);
Route::get('/plan/coingate/{plan}', [CoingatePaymentController::class, 'getPaymentStatus'])->name('plan.coingate');


//================================= Invoice Payment Gateways  ====================================//

Route::post('/invoice-pay-with-paystack', [PaystackPaymentController::class, 'invoicePayWithPaystack'])->name('invoice.pay.with.paystack')->middleware('XSS');
Route::get('/invoice/paystack/{pay_id}/{invoice_id}', [PaystackPaymentController::class, 'getInvociePaymentStatus'])->name('invoice.paystack');


Route::post('/invoice-pay-with-flaterwave', [FlutterwavePaymentController::class, 'invoicePayWithFlutterwave'])->name('invoice.pay.with.flaterwave')->middleware('XSS');
Route::get('/invoice/flaterwave/{txref}/{invoice_id}', [FlutterwavePaymentController::class, 'getInvociePaymentStatus'])->name('invoice.flaterwave');


Route::post('/invoice-pay-with-razorpay', [RazorpayPaymentController::class, 'invoicePayWithRazorpay'])->name('invoice.pay.with.razorpay')->middleware('XSS');
Route::get('/invoice/razorpay/{txref}/{invoice_id}', [RazorpayPaymentController::class, 'getInvociePaymentStatus'])->name('invoice.razorpay');

Route::post('/invoice-pay-with-paytm', [PaytmPaymentController::class, 'invoicePayWithPaytm'])->name('invoice.pay.with.paytm')->middleware('XSS');
Route::post('/invoice/paytm/{invoice}', [PaytmPaymentController::class, 'getInvociePaymentStatus'])->name('invoice.paytm');


Route::post('/invoice-pay-with-mercado', [MercadoPaymentController::class, 'invoicePayWithMercado'])->name('invoice.pay.with.mercado')->middleware('XSS');
Route::get('/invoice/mercado/{invoice}', [MercadoPaymentController::class, 'getInvoicePaymentStatus'])->name('invoice.mercado');


Route::post('/invoice-pay-with-mollie', [MolliePaymentController::class, 'invoicePayWithMollie'])->name('invoice.pay.with.mollie')->middleware('XSS');
Route::get('/invoice/mollie/{invoice}', [MolliePaymentController::class, 'getInvociePaymentStatus'])->name('invoice.mollie');


Route::post('/invoice-pay-with-skrill', [SkrillPaymentController::class, 'invoicePayWithSkrill'])->name('invoice.pay.with.skrill')->middleware('XSS');
Route::get('/invoice/skrill/{invoice}', [SkrillPaymentController::class, 'getInvociePaymentStatus'])->name('invoice.skrill');

Route::post('/invoice-pay-with-coingate', [CoingatePaymentController::class, 'invoicePayWithCoingate'])->name('invoice.pay.with.coingate')->middleware('XSS');
Route::get('/invoice/coingate/{invoice}', [CoingatePaymentController::class, 'getInvociePaymentStatus'])->name('invoice.coingate');


Route::post('/invoice-pay-with-stripe', [StripePaymentController::class, 'invoicePayWithStripe'])->name('invoice.pay.with.stripe')->middleware('XSS');
Route::get('/invoice/stripe/{invoice_id}', [StripePaymentController::class, 'getInvociePaymentStatus'])->name('invoice.stripe')->middleware('XSS');

Route::get('/invoice/error/{flag}/{invoice_id}', [PaymentWallController::class, 'invoiceerror'])->name('error.invoice.show');
Route::post('/invoicepayment', [PaymentWallController::class, 'invoicepay'])->name('paymentwall.invoice');
Route::post('/invoice-pay-with-paymentwall/{invoice}', [PaymentWallController::class, 'invoicePayWithPaymentWall'])->name('invoice-pay-with-paymentwall');


//--------------------------------------------------------Import/Export Data Route-----------------------------------------------------------------

Route::get('import/user/file', [UserController::class, 'importFile'])->name('user.file.import');
Route::post('import/user', [UserController::class, 'import'])->name('user.import');


Route::get('import/client/file', [ClientController::class, 'importFile'])->name('client.file.import');
Route::post('import/client', [ClientController::class, 'import'])->name('client.import');
Route::get('export/client', [ClientController::class, 'export'])->name('client.export');


Route::get('import/project/file', [ProjectsController::class, 'importFile'])->name('project.file.import');
Route::post('import/project', [ProjectsController::class, 'import'])->name('project.import');
Route::get('export/project', [ProjectsController::class, 'export'])->name('project.export');
Route::get('export/expense', [ExpenseController::class, 'export'])->name('expense.export');
Route::get('export/Estimation', [EstimationController::class, 'export'])->name('Estimation.export');
Route::get('export/timesheet', [ProjectsController::class, 'exporttimesheet'])->name('timesheet.export');
Route::get('export/invoice', [InvoiceController::class, 'export'])->name('invoice.export');

Route::get('{id}/bug/export', [ProjectsController::class, 'bugexport'])->name('bug.export');


//===========================================screenshort=========================================
Route::delete('tracker/{tid}/destroy', [TimeTrackerController::class, 'Destroy'])->name('tracker.destroy');
Route::get('time-tracker', [TimeTrackerController::class, 'index'])->name('time.tracker')->middleware(['auth', 'XSS']);
Route::post('tracker/image-view', [TimeTrackerController::class, 'getTrackerImages'])->name('tracker.image.view');
Route::delete('tracker/image-remove', [TimeTrackerController::class, 'removeTrackerImages'])->name('tracker.image.remove');
Route::get('projects/time-tracker/{id}', [ProjectsController::class, 'tracker'])->name('projecttime.tracker')->middleware(['auth', 'XSS']);


// ====================================Zoommeeting===============================================================//


Route::post('/company-setting/saveZoomSettings', [SystemController::class, 'saveZoomSettings'])->name('setting.ZoomSettings')->middleware(['auth', 'XSS']);
Route::resource('zoommeeting', ZoommeetingController::class)->middleware(['auth', 'XSS']);
Route::get('/zoom/project/select/{id}', [ZoommeetingController::class, 'projectwiseuser'])->name('zoom.project.select');
Route::get('/zoom/calender', [ZoommeetingController::class, 'calendar'])->name('zoommeeting.Calender')->middleware(['auth', 'XSS']);

// ====================================Slack===============================================================//

Route::post('setting/slack', [SystemController::class, 'slack'])->name('slack.setting');


// ====================================telegram===============================================================//

Route::post('setting/telegram', [SystemController::class, 'telegram'])->name('telegram.setting');

Route::any('user-reset-password/{id}', [UserController::class, 'employeePassword'])->name('user.reset');
Route::post('user-reset-password/{id}', [UserController::class, 'employeePasswordReset'])->name('user.password.update');

/*==================================Recaptcha====================================================*/


Route::post('/recaptcha-settings', [SystemController::class, 'recaptchaSettingStore'])->name('recaptcha.settings.store')->middleware(['auth', 'XSS']);
/*==========================================contracts=========================================*/

Route::resource('contract_type', ContractTypeController::class)->middleware(['auth', 'XSS']);
Route::resource('contracts', ContractsController::class)->middleware(['auth', 'XSS']);

Route::post('/contract/{id}/file', [ContractsController::class, 'fileUpload',])->name('contracts.file.upload')->middleware(['auth', 'XSS']);
Route::get('/contract/{id}/file/{fid}', [ContractsController::class, 'fileDownload',])->name('contracts.file.download')->middleware(['auth', 'XSS']);
Route::delete('/contract/{id}/file/delete/{file_id}', [ContractsController::class, 'fileDelete',])->name('contracts.file.delete')->middleware(['auth', 'XSS']);
Route::post('contract/{id}/contract_description', [ContractsController::class, 'contract_descriptionStore'])->name('contract.contract_description.store')->middleware(['auth']);


Route::post('/contract/{id}/comment', [ContractsController::class, 'commentStore',])->name('comment_store.store')->middleware(['auth']);
Route::get('/contract/{id}/comment', [ContractsController::class, 'commentDestroy',])->name('comment_store.destroy');


Route::post('/contract/{id}/notes', [ContractsController::class, 'noteStore',])->name('note_store.store')->middleware(['auth']);
Route::get('/contract/{id}/notes', [ContractsController::class, 'noteDestroy',])->name('note_store.destroy')->middleware(['auth']);


Route::get('/contract/{id}', [ContractsController::class, 'copycontract'])->name('contracts.copy')->middleware(['auth', 'XSS']);
Route::post('/contract/copy/store/', [ContractsController::class, 'copycontractstore'])->name('contracts.copy.store')->middleware(['auth', 'XSS']);


Route::get('get-projects/{client_id}', [ContractsController::class, 'clientByProject'])->name('project.by.user.id')->middleware(['auth', 'XSS']);

Route::get('contract/pdf/{id}', [ContractsController::class, 'pdffromcontract'])->name('contract.download.pdf');
Route::get('contract/{id}/get_contract', [ContractsController::class, 'printContract'])->name('get.contract');


Route::get('/signature/{id}', [ContractsController::class, 'signature'])->name('signature')->middleware(['auth', 'XSS']);
Route::post('/signaturestore', [ContractsController::class, 'signatureStore'])->name('signaturestore')->middleware(['auth', 'XSS']);


Route::get('/contract/{id}/mail', [ContractsController::class, 'sendmailContract',])->name('send.mail.contract');
Route::post('/contract_status_edit/{id}', [ContractsController::class, 'contract_status_edit'])->name('contract.status')->middleware(['auth', 'XSS']);


// ===================================== Project Reports =======================================================  //

Route::resource('/project_report', ProjectReportController::class)->middleware(['auth', 'XSS']);
Route::post('/project_report_data', [ProjectReportController::class, 'ajax_data'])->name('projects.ajax')->middleware(['auth', 'XSS']);
Route::post('/project_report/{id}', [ProjectReportController::class, 'show'])->name('project_report.show')->middleware(['auth', 'XSS']);
Route::post('/project_report/tasks/{id}', [ProjectReportController::class, 'ajax_tasks_report'])->name('tasks.report.ajaxdata')->middleware(['auth', 'XSS']);
Route::get('export/task_report/{id}', [ProjectReportController::class, 'export'])->name('project_report.export');

//logger
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
