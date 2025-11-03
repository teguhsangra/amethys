<?php

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

Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

// Start : Migration Controller
Route::get('migration_booking', 'BookingController@migration');
// End : Migration Controller

// Start : Default Controller
Route::get('home', 'ProfileController@index');
Route::resource('profile', 'ProfileController');
Route::resource('notification', 'NotificationController');
Route::get('updateNotification/{id}', 'NotificationController@update');
Route::get('datatables/notification/', 'NotificationController@datatables');
Route::get('dashboard', 'DashboardController@index');
Route::get('setup_periode', 'HomeController@setup_periode');
// End : Default Controller

// Start : Scheduller Controller
Route::get('test_log', 'SchedulerController@test_log');
// End : Scheduller Controller

// Start : Administrator Controller

Route::resource('access_group', 'Admin\AccessGroupController');
Route::resource('module', 'Admin\ModuleController');
Route::resource('location', 'Admin\LocationController');
Route::resource('parameter_setting', 'Admin\ParameterSettingController');
Route::resource('user', 'Admin\UserController');
Route::resource('company', 'Admin\CompanyController');

// Start : Other Route
Route::put('reset_password/user', 'Admin\UserController@reset_password');
Route::get('access_group/manage/{id}', 'Admin\AccessGroupController@manage');
Route::post('access_group/manage/module/{id}', 'Admin\AccessGroupController@assignModule');
Route::put('access_group/manage/module/{id}', 'Admin\AccessGroupController@editAGM');
Route::delete('access_group/manage/module/{id}', 'Admin\AccessGroupController@unassignModule');
Route::post('access_group/manage/user/{id}', 'Admin\AccessGroupController@assignUser');
Route::delete('access_group/manage/user/{id}', 'Admin\AccessGroupController@unassignUser');
// End : Other Route

// Start : DataTables Section
Route::get('datatables/user/', 'Admin\UserController@datatables');
Route::get('datatables/access_group/', 'Admin\AccessGroupController@datatables');
Route::get('datatables/module/', 'Admin\ModuleController@datatables');
Route::get('datatables/parameter_setting/', 'Admin\ParameterSettingController@datatables');
Route::get('datatables/access_group/manage/unassignUser/{id}', 'Admin\AccessGroupController@datatables_unassigned_user');
Route::get('datatables/access_group/manage/assignUser/{id}', 'Admin\AccessGroupController@datatables_assigned_user');
Route::get('datatables/access_group/manage/unassignModule/{id}', 'Admin\AccessGroupController@datatables_unassigned_module');
Route::get('datatables/access_group/manage/assignModule/{id}', 'Admin\AccessGroupController@datatables_assigned_module');
Route::get('datatables/location/', 'Admin\LocationController@datatables');
Route::get('datatables/parameter_setting/', 'Admin\ParameterSettingController@datatables');
Route::get('datatables/company/', 'Admin\CompanyController@datatables');
// End : DataTables Section


// End : Administrator Controller

// Start : Master Controller
Route::resource('agent_company', 'Master\AgentCompanyController');
Route::resource('agent', 'Master\AgentController');
Route::resource('bank_account', 'Master\BankAccountController');
Route::resource('customer', 'Master\CustomerController');
Route::resource('contact', 'Master\ContactController');
Route::resource('employee', 'Master\EmployeeController');
Route::resource('furniture', 'Master\FurnitureController');
Route::resource('product_category', 'Master\ProductCategoryController');
Route::resource('product', 'Master\ProductController');
Route::resource('package', 'Master\PackageController');
Route::resource('referral', 'Master\ReferralController');
Route::resource('room_type', 'Master\RoomTypeController');
Route::resource('room', 'Master\RoomController');
Route::resource('vendor_category', 'Master\VendorCategoryController');
Route::resource('vendor', 'Master\VendorController');
Route::resource('non_cash', 'Master\NonCashController');
Route::resource('nature_of_business', 'Master\NatureOfBusinessController');
Route::resource('complimentary', 'Master\ComplimentaryController');
Route::resource('dedicated_phone', 'Master\DedicatedPhoneController');
Route::resource('marketing_material', 'Master\MarketingMaterialController');
Route::resource('access_card', 'Master\AccessCardController');
Route::resource('ticketing_subject', 'Master\TicketingSubjectController');
Route::resource('task_subject', 'Master\TaskSubjectController');

// Start : Other Route
Route::get('customer/file/{id}', 'Master\CustomerController@file');
Route::post('customer/file/{id}', 'Master\CustomerController@addFile');
Route::delete('customer/file/{id}', 'Master\CustomerController@deleteFile');
Route::get('customer/get_by_id/{id}', 'Master\CustomerController@get_by_id');
Route::get('addCustomerContact/{customer_id}/{contact_id}', 'Master\CustomerController@addCustomerContact');
Route::get('editCustomerContact/{customer_id}/{contact_id}', 'Master\CustomerController@editCustomerContact');
Route::get('deleteCustomerContact/{customer_id}/{contact_id}', 'Master\CustomerController@deleteCustomerContact');
Route::get('contact/get_by_customer/{customer_id}', 'Master\ContactController@get_by_customer');
Route::get('contact/get_by_sales_activity/{sales_activity_id}', 'Master\ContactController@get_by_sales_activity');
Route::get('contact/get_by_prospect/{prospect_id}', 'Master\ContactController@get_by_prospect');
Route::get('furniture/photo/{id}', 'Master\FurnitureController@photo');
Route::get('furniture/photo/change_status/{id}', 'Master\FurnitureController@changeStatus');
Route::post('furniture/photo/{id}', 'Master\FurnitureController@addPhoto');
Route::delete('furniture/photo/{id}', 'Master\FurnitureController@deletePhoto');
Route::get('product/photo/{id}', 'Master\ProductController@photo');
Route::get('product/photo/change_status/{id}', 'Master\ProductController@changeStatus');
Route::post('product/photo/{id}', 'Master\ProductController@addPhoto');
Route::delete('product/photo/{id}', 'Master\ProductController@deletePhoto');
Route::get('product/get_by_id/{id}', 'Master\ProductController@get_by_id');
Route::get('room/get_by_location/{location_id}', 'Master\RoomController@get_by_location');
Route::get('room/photo/{id}', 'Master\RoomController@photo');
Route::get('room/photo/change_status/{id}', 'Master\RoomController@changeStatus');
Route::get('room/get_by_location_id/{location_id}', 'Master\RoomController@get_by_location_id');
Route::get('room/get_by_transaction/{location_id}', 'Master\RoomController@get_by_transaction');
Route::post('room/photo/{id}', 'Master\RoomController@addPhoto');
Route::delete('room/photo/{id}', 'Master\RoomController@deletePhoto');
Route::get('room/get_by_id/{id}', 'Master\RoomController@get_by_id');
Route::get('package/get_by_id/{id}', 'Master\PackageController@get_by_id');
Route::get('package/get_by_location_id/{location_id}', 'Master\PackageController@get_by_location_id');
Route::get('product/get_by_id/{id}', 'Master\ProductController@get_by_id');
Route::get('product/get_by_location_id/{location_id}', 'Master\ProductController@get_by_location_id');
Route::get('non_cash/get_by_id/{id}', 'Master\NonCashController@get_by_id');
Route::get('furniture/get_by_id/{id}', 'Master\FurnitureController@get_by_id');
Route::get('dedicated_phone/get_by_id/{id}', 'Master\DedicatedPhoneController@get_by_id');

// End : Other Route

// Start : DataTables Section
Route::get('datatables/agent_company/', 'Master\AgentCompanyController@datatables');
Route::get('datatables/agent/', 'Master\AgentController@datatables');
Route::get('datatables/bank_account/', 'Master\BankAccountController@datatables');
Route::get('datatables/customer/', 'Master\CustomerController@datatables');
Route::get('datatables/customer/customer_contact/{id}', 'Master\CustomerController@datatables_assign_contact');
Route::get('datatables/customer/contact/{id}', 'Master\CustomerController@datatables_not_assign_contact');
Route::get('datatables/customer/file/{id}', 'Master\CustomerController@datatables_file');
Route::get('datatables/contact/', 'Master\ContactController@datatables');
Route::get('datatables/employee/', 'Master\EmployeeController@datatables');
Route::get('datatables/furniture/', 'Master\FurnitureController@datatables');
Route::get('datatables/furniture/photo/{id}', 'Master\FurnitureController@datatables_photo');
Route::get('datatables/product_category/', 'Master\ProductCategoryController@datatables');
Route::get('datatables/product/', 'Master\ProductController@datatables');
Route::get('datatables/product/photo/{id}', 'Master\ProductController@datatables_photo');
Route::get('datatables/package/', 'Master\PackageController@datatables');
Route::get('datatables/package/{location_id}', 'Master\PackageController@datatables_by_location');
Route::get('datatables/referral/', 'Master\ReferralController@datatables');
Route::get('datatables/room_type/', 'Master\RoomTypeController@datatables');
Route::get('datatables/room/', 'Master\RoomController@datatables');
Route::get('datatables/room/photo/{id}', 'Master\RoomController@datatables_photo');
Route::get('datatables/vendor_category/', 'Master\VendorCategoryController@datatables');
Route::get('datatables/vendor/', 'Master\VendorController@datatables');
Route::get('datatables/non_cash/', 'Master\NonCashController@datatables');
Route::get('datatables/nature_of_business/', 'Master\NatureOfBusinessController@datatables');
Route::get('datatables/complimentary', 'Master\ComplimentaryController@datatables');
Route::get('datatables/dedicated_phone', 'Master\DedicatedPhoneController@datatables');
Route::get('datatables/marketing_material', 'Master\MarketingMaterialController@datatables');
Route::get('datatables/access_card', 'Master\AccessCardController@datatables');
Route::get('datatables/ticketing_subject', 'Master\TicketingSubjectController@datatables');
Route::get('datatables/task_subject', 'Master\TaskSubjectController@datatables');
// End : DataTables Section

// End : Master Controller

// Start : Transaction Controller
Route::resource('sales_target', 'Transaction\SalesTargetController');
Route::resource('prospect', 'Transaction\ProspectController');
Route::resource('sales_activity', 'Transaction\SalesActivityController');
Route::resource('inquiry', 'Transaction\InquiryController');
Route::resource('booking', 'Transaction\MainAgreementController');
Route::resource('booking_package', 'Transaction\PackageController');
Route::resource('booking_contact', 'Transaction\BookingContactController');
Route::resource('serviced_office', 'Transaction\ServicedOfficeController');
Route::resource('virtual_office', 'Transaction\VirtualOfficeController');
Route::resource('regular_office', 'Transaction\RegularOfficeController');
Route::resource('meeting_room', 'Transaction\MeetingRoomController');
Route::resource('coworking', 'Transaction\CoworkingController');
Route::resource('hotel', 'Transaction\HotelController');
Route::resource('point_of_sales', 'Transaction\OrderController');
Route::resource('purchase_order', 'Transaction\PurchaseOrderController');
Route::resource('access_card_transaction', 'Transaction\AccessCardController');
Route::resource('dedicated_phone_transaction', 'Transaction\DedicatedPhoneController');
Route::resource('ticketing', 'Transaction\TicketingController');
Route::resource('task', 'Transaction\TaskController');
Route::resource('proforma', 'Transaction\ProformaController');
Route::resource('invoice', 'Transaction\InvoiceController');
Route::resource('payment', 'Transaction\PaymentController');
Route::resource('deposit', 'Transaction\DepositController');
Route::resource('booking_reminder', 'Transaction\BookingReminderController');
Route::resource('collection_reminder', 'Transaction\CollectionReminderController');
Route::resource('billing_reminder', 'Transaction\BillingReminderController');
Route::resource('dedicated_phone_reminder', 'Transaction\DedicatedReminderController');

// Start : Other Route
Route::get('redirect_inquiry_to_booking', 'BookingController@redirect_inquiry_to_booking');
Route::get('get_schedule_room', 'BookingController@get_schedule_room');
Route::get('booking/complimentary/get_by_customer/{id}', 'BookingController@complimentary');
Route::get('booking/customer_contact/{customer_id}/{contact_id}', 'BookingController@getCustomerContact');
Route::put('booking/customer_contact/{customer_id}/{contact_id}', 'BookingController@updateCustomerContact');
Route::get('inquiry/customer_contact/{customer_id}/{contact_id}', 'Transaction\InquiryController@getCustomerContact');
Route::put('inquiry/customer_contact/{customer_id}/{contact_id}', 'Transaction\InquiryController@updateCustomerContact');
Route::get('getDataBooking', 'DashboardController@getDataBooking');
Route::get('getTotalBookingPerMonth', 'DashboardController@getTotalBookingPerMonth');
Route::get('getOccupancyGraph', 'DashboardController@getOccupancyGraph');
Route::get('getTotalCustomer', 'DashboardController@getTotalCustomer');
Route::get('check_availability', 'HomeController@check_availability');
Route::get('getProformaInvoiceByBooking/{booking_id}', 'HomeController@getProformaInvoiceByBooking');
Route::get('getProformaInvoiceByOrder/{order_id}', 'HomeController@getProformaInvoiceByOrder');
Route::get('getProformaInvoiceByInquiry/{inquiry_id}', 'HomeController@getProformaInvoiceByInquiry');
Route::get('prospect/get_by_id/{id}', 'Transaction\ProspectController@get_by_id');
Route::get('inquiry/get_by_id/{id}', 'Transaction\InquiryController@get_by_id');
Route::get('inquiry/get_by_param/{location_id}/{customer_id}', 'Transaction\InquiryController@get_by_param');
Route::get('inquiry/print/{id}', 'Transaction\InquiryController@print');
Route::get('sales_activity/print/{id}', 'Transaction\SalesActivityController@print');
Route::get('sales_activity/email/{id}', 'Transaction\SalesActivityController@sendEmail');
Route::get('booking/print/{id}', 'Transaction\MainAgreementController@print');
Route::get('booking/get_by_id/{id}', 'Transaction\MainAgreementController@get_by_id');
Route::get('booking/get_by_param/{location_id}/{customer_id}', 'Transaction\MainAgreementController@get_by_param');
Route::get('booking/email/{id}', 'Transaction\MainAgreementController@sendEmail');
Route::get('booking/domicile/{id}', 'Transaction\MainAgreementController@domicile');
Route::get('booking/term_condition/{id}', 'Transaction\MainAgreementController@term_condition');
Route::get('purchase_order/print/{id}', 'Transaction\PurchaseOrderController@print');
Route::get('ticketing/print/{id}', 'Transaction\TicketingController@print');
Route::get('task/print/{id}', 'Transaction\TaskController@print');
Route::get('booking_package/print/{id}', 'Transaction\PackageController@print');
Route::get('virtual_office/print/{id}', 'Transaction\VirtualOfficeController@print');
Route::get('serviced_office/print/{id}', 'Transaction\ServicedOfficeController@print');
Route::get('coworking/print/{id}', 'Transaction\CoworkingController@print');
Route::get('meeting_room/print/{id}', 'Transaction\MeetingRoomController@print');
Route::get('hotel/print/{id}', 'Transaction\HotelController@print');
Route::get('regular_office/print/{id}', 'Transaction\RegularOfficeController@print');
Route::get('point_of_sales/print/{id}', 'Transaction\OrderController@print');
Route::get('point_of_sales/get_by_id/{id}', 'Transaction\OrderController@get_by_id');
Route::get('getProformaData', 'Transaction\ProformaController@getProformaData');
Route::get('deposit/print/{id}', 'Transaction\DepositController@print');
Route::get('invoice/get_by_param/{location_id}/{customer_id}/{payment_status}', 'Transaction\InvoiceController@get_by_param');
Route::get('deposit/get_by_param/{location_id}/{customer_id}/{payment_status}', 'Transaction\DepositController@get_by_param');
Route::get('deposit/get_by_id/{id}', 'Transaction\DepositController@get_by_id');
Route::get('getDataBookingReminder', 'Transaction\BookingReminderController@getDataBookingReminder');
Route::get('exportBookingReminder', 'Transaction\BookingReminderController@exportToExcel');
Route::get('getInvoiceData', 'Transaction\InvoiceController@getInvoiceData');
Route::get('proforma/print/{id}', 'Transaction\ProformaController@print');
Route::get('invoice/print/{id}', 'Transaction\InvoiceController@print');
Route::get('payment/print/{id}', 'Transaction\PaymentController@print');
Route::post('payment/complete/{id}', 'Transaction\PaymentController@complete');
Route::get('booking/print/mail/{id}', 'MailController@printMailAgreement');
Route::get('inquiry/agreement/{id}', 'Transaction\InquiryController@aggrement');
Route::get('booking/get_by_customer_id/{id}', 'BookingController@get_by_customer_id');
Route::get('order/get_by_customer_id/{id}', 'Transaction\OrderController@get_by_customer_id');
Route::post('ticketing/reply', 'Transaction\TicketingController@ticketing_reply');
Route::post('inquiry/create_proforma/{id}', 'Transaction\InquiryController@create_proforma');
Route::get('get_deposit_by_customer/{customer_id}', 'Transaction\DepositController@get_deposit_by_customer');
// End : Other Route

// Start : DataTables Section
Route::get('datatables/sales_target/', 'Transaction\SalesTargetController@datatables');
Route::get('datatables/prospect/', 'Transaction\ProspectController@datatables');
Route::get('datatables/sales_activity/', 'Transaction\SalesActivityController@datatables');
Route::get('datatables/inquiry/', 'Transaction\InquiryController@datatables');
Route::get('datatables/booking/', 'Transaction\MainAgreementController@datatables');
Route::get('datatables/booking_package/', 'Transaction\PackageController@datatables');
Route::get('datatables/serviced_office/', 'Transaction\ServicedOfficeController@datatables');
Route::get('datatables/virtual_office/', 'Transaction\VirtualOfficeController@datatables');
Route::get('datatables/regular_office/', 'Transaction\RegularOfficeController@datatables');
Route::get('datatables/meeting_room/', 'Transaction\MeetingRoomController@datatables');
Route::get('datatables/coworking/', 'Transaction\CoworkingController@datatables');
Route::get('datatables/hotel/', 'Transaction\HotelController@datatables');
Route::get('datatables/point_of_sales/', 'Transaction\OrderController@datatables');
Route::get('datatables/purchase_order/', 'Transaction\PurchaseOrderController@datatables');
Route::get('datatables/ticketing/', 'Transaction\TicketingController@datatables');
Route::get('datatables/task/', 'Transaction\TaskController@datatables');
Route::get('datatables/proforma/', 'Transaction\ProformaController@datatables');
Route::get('datatables/invoice/', 'Transaction\InvoiceController@datatables');
Route::get('datatables/payment/', 'Transaction\PaymentController@datatables');
Route::get('datatables/deposit/', 'Transaction\DepositController@datatables');
Route::get('datatables/booking_reminder/', 'Transaction\BookingReminderController@datatables');
Route::get('datatables/collection_reminder/', 'Transaction\CollectionReminderController@datatables');
Route::get('datatables/billing_reminder/', 'Transaction\BillingReminderController@datatables');
Route::get('datatables/booking_contact/', 'Transaction\BookingContactController@datatables');
Route::get('datatables/ticketing', 'Transaction\TicketingController@datatables');
Route::get('datatables/task', 'Transaction\TaskController@datatables');
Route::get('datatables/access_card_transaction', 'Transaction\AccessCardController@datatables');
Route::get('datatables/dedicated_phone_transaction', 'Transaction\DedicatedPhoneController@datatables');
Route::get('datatables/dedicated_phone_reminder/', 'Transaction\DedicatedReminderController@datatables');
// End : DataTables Section

// End : Transaction Controller

// Start :  Report Controller
Route::resource('marketing_report', 'Report\MarketingController');
Route::resource('referentor_report', 'Report\ReferentorController');
Route::resource('inquiry_report', 'Report\InquiryController');
Route::resource('booking_report', 'Report\BookingController');
Route::resource('room_occupancy_report', 'Report\RoomOccupancyController');
Route::resource('product_occupancy_report', 'Report\ProductOccupancyController');
Route::resource('package_occupancy_report', 'Report\PackageOccupancyController');
Route::resource('invoice_report', 'Report\InvoiceController');
Route::resource('aging_invoice_report', 'Report\AgingInvoiceController');
Route::resource('complimentary_report', 'Report\ComplimentaryController');
Route::resource('payment_report', 'Report\PaymentController');

// Start : Other Route
Route::get('chart/kpi/marketing_report', 'Report\MarketingController@chart_kpi');
Route::get('export/marketing_report', 'Report\MarketingController@exportToExcel');
Route::get('exportAgingInvoice', 'Report\AgingInvoiceController@exportToExcel');
Route::get('exportInquiry', 'Report\InquiryController@exportToExcel');
Route::get('exportBooking', 'Report\BookingController@exportToExcel');
Route::get('exportRoomOccupancy', 'Report\RoomOccupancyController@exportToExcel');
Route::get('exportInvoice', 'Report\InvoiceController@exportToExcel');
Route::get('exportPayment', 'Report\PaymentController@exportToExcel');
Route::get('exportProductOccupancy', 'Report\ProductOccupancyController@exportToExcel');
Route::get('exportReferentor', 'Report\ReferentorController@exportToExcel');
Route::get('dedicated_phone_transaction/booking/get_by_id/{id}', 'Transaction\DedicatedPhoneController@get_by_id');
Route::get('dedicated_phone_transaction/booking/get_by_booking/{id}', 'Transaction\DedicatedPhoneController@get_by_booking');
Route::get('dedicated_phone_transaction/getDedicated/{id}', 'Transaction\DedicatedPhoneController@getDedicated');
Route::get('export/complimentary_report', 'Report\ComplimentaryController@exportToExcel');
Route::get('getBookingDedicated', 'BookingController@getDedicated');
Route::get('dedicated_phone/get_by_location_id/{id}', 'Master\DedicatedPhoneController@get_by_location_id');
Route::get('exportContact', 'Master\ContactController@exportToExcel');
Route::get('exportCustomer', 'Master\CustomerController@exportToExcel');
// End : Other Route

// Start : DataTables Section
Route::get('datatables/kpi/marketing_report', 'Report\MarketingController@datatables_kpi');
Route::get('datatables/achievement/marketing_report', 'Report\MarketingController@datatables_achievement');
Route::get('datatables/inquiry_report', 'Report\InquiryController@datatables');
Route::get('datatables/booking_report', 'Report\BookingController@datatables');
Route::get('datatables/invoice_report/', 'Report\InvoiceController@datatables');
Route::get('datatables/complimentary_report', 'Report\ComplimentaryController@datatables');
// End : DataTables Section

// End : Report Controller
