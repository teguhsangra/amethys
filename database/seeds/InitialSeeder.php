<?php

use Illuminate\Database\Seeder;

class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* DELETE DATA TO COMPANIES TABLE */
        DB::table('companies')->delete();
        /* INSERT DATA TO COMPANIES TABLE */
        DB::table('companies')->insert([
            'id' => 1,
            'code' => 'RAKITEK',
            'booking_signatory' => 'Ahmadi',
            'proforma_signatory' => 'Ahmadi',
            'name' => 'Ruang Kreasi Inovasi Teknologi, PT',
            'address' => 'MENARA 165, 4th Floor, Jl TB. Simatupang, Kav.1 Cilandak, Jakarta 12560',
            'phone' => '+62 21 8064 1029',
            'fax' => '+62 21 8064 1000'
        ]);

        /* DELETE DATA TO MONTHS TABLE */
        DB::table('months')->delete();

        /* INSERT DATA TO MONTHS TABLE */
        DB::table('months')->insert([
            'id' => 1, 'code' => 'JAN', 'name' => 'January'
        ]);
        DB::table('months')->insert([
            'id' => 2, 'code' => 'FEB', 'name' => 'February'
        ]);
        DB::table('months')->insert([
            'id' => 3, 'code' => 'MAR', 'name' => 'March'
        ]);
        DB::table('months')->insert([
            'id' => 4, 'code' => 'APR', 'name' => 'April'
        ]);
        DB::table('months')->insert([
            'id' => 5, 'code' => 'MAY', 'name' => 'May'
        ]);
        DB::table('months')->insert([
            'id' => 6, 'code' => 'JUN', 'name' => 'June'
        ]);
        DB::table('months')->insert([
            'id' => 7, 'code' => 'JUL', 'name' => 'July'
        ]);
        DB::table('months')->insert([
            'id' => 8, 'code' => 'AUG', 'name' => 'August'
        ]);
        DB::table('months')->insert([
            'id' => 9, 'code' => 'SEP', 'name' => 'September'
        ]);
        DB::table('months')->insert([
            'id' => 10, 'code' => 'OCT', 'name' => 'October'
        ]);
        DB::table('months')->insert([
            'id' => 11, 'code' => 'NOV', 'name' => 'November'
        ]);
        DB::table('months')->insert([
            'id' => 12, 'code' => 'DEC', 'name' => 'December'
        ]);

        /* DELETE DATA TO ACCESS_GROUPS TABLE */
        DB::table('access_groups')->delete();
        /* INSERT DATA TO ACCESS_GROUPS TABLE */
        DB::table('access_groups')->insert([
            'id' => 1,
            'code' => 'ADM',
            'name' => 'Administrator'
        ]);
        /* DELETE DATA TO USERS TABLE */
        DB::table('users')->delete();

        /* INSERT DATA TO USERS TABLE */
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'admin',
            'type' => 'admin',
            'email' => 'admin@rakomsis.com',
            'password' => bcrypt('rakomsis2018'),
            'access_group_id' => 1
        ]);

        DB::table('employees')->insert([
            'user_id' => 1,
            'code' => 'EMP-01',
            'name' => 'admin',
            'email' => 'admin@rakomsis.com',
            'role' => 'admin'
        ]);

        /* DELETE DATA TO LOCATIONS TABLE */
        DB::table('locations')->delete();

        /* INSERT DATA TO LOCATIONS TABLE */
        DB::table('locations')->insert([
            'id' => 1, 'code' => 'JKT', 'name' => 'Jakarta', 'phone' => '123456', 'fax' => '123456', 'address' => 'JL TB Simatupang'
        ]);
        DB::table('locations')->insert([
            'id' => 2, 'code' => 'PYK', 'name' => 'Payakumbuh', 'phone' => '123456', 'fax' => '123456', 'address' => 'JL TB Simatupang'
        ]);

        /* DELETE DATA TO USERS_AND_LOCATIONS TABLE */
        DB::table('user_and_location')->delete();

        /* INSERT DATA TO USERS_AND_LOCATIONS TABLE */
        DB::table('user_and_location')->insert([
            'user_id' => 1, 'location_id' => 1
        ]);
        DB::table('user_and_location')->insert([
            'user_id' => 1, 'location_id' => 2
        ]);

        /* DELETE DATA TO MODULES TABLE */
        DB::table('dashboards')->delete();

        /* INSERT DATA TO MODULES TABLE */
        DB::table('dashboards')->insert(['id' => 1, 'tag' => 'sales_growth']);
        DB::table('dashboards')->insert(['id' => 2, 'tag' => 'occupancy']);
        DB::table('dashboards')->insert(['id' => 3, 'tag' => 'meeting_room_availability']);
        DB::table('dashboards')->insert(['id' => 4, 'tag' => 'coworking_availability']);
        DB::table('dashboards')->insert(['id' => 5, 'tag' => 'booking_active']);
        DB::table('dashboards')->insert(['id' => 6, 'tag' => 'top_product']);
        /* DELETE DATA TO MODULES TABLE */
        DB::table('a_g_and_dashboard')->delete();

        /* INSERT DATA TO MODULES TABLE */
        DB::table('a_g_and_dashboard')->insert(['access_group_id' => 1, 'dashboard_id' => 1]);
        DB::table('a_g_and_dashboard')->insert(['access_group_id' => 1, 'dashboard_id' => 2]);
        DB::table('a_g_and_dashboard')->insert(['access_group_id' => 1, 'dashboard_id' => 3]);
        DB::table('a_g_and_dashboard')->insert(['access_group_id' => 1, 'dashboard_id' => 4]);
        DB::table('a_g_and_dashboard')->insert(['access_group_id' => 1, 'dashboard_id' => 5]);
        DB::table('a_g_and_dashboard')->insert(['access_group_id' => 1, 'dashboard_id' => 6]);

        /* DELETE DATA TO MODULES TABLE */
        DB::table('modules')->delete();

        /* INSERT DATA TO MODULES TABLE */
        DB::table('modules')->insert(['id' => 1, 'name' => 'Dashboard', 'icon' => 'dashboard', 'link' => 'dashboard', 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 2, 'name' => 'Master Data', 'icon' => 'apps', 'link' => null, 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 3, 'name' => 'Marketing Tools', 'icon' => 'free_breakfast', 'link' => null, 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 4, 'name' => 'Booking Tools', 'icon' => 'book', 'link' => null, 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 5, 'name' => 'Operational Tools', 'icon' => 'build', 'link' => null, 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 6, 'name' => 'Financial Tools', 'icon' => 'bookmarks', 'link' => null, 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 7, 'name' => 'Reminders', 'icon' => 'assignment_late', 'link' => null, 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 8, 'name' => 'Reports', 'icon' => 'view_headline', 'link' => null, 'parent_id' => null]);
        DB::table('modules')->insert(['id' => 9, 'name' => 'Access Card', 'icon' => 'AC', 'link' => 'access_card', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 10, 'name' => 'Agent Companies', 'icon' => 'AgCo', 'link' => 'agent_company', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 11, 'name' => 'Agents', 'icon' => 'Ag', 'link' => 'agent', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 12, 'name' => 'Bank Accounts', 'icon' => 'BA', 'link' => 'bank_account', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 13, 'name' => 'Complimentary', 'icon' => 'COM', 'link' => 'complimentary', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 14, 'name' => 'Contact', 'icon' => 'CON', 'link' => 'contact', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 15, 'name' => 'Customers', 'icon' => 'CUST', 'link' => 'customer', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 16, 'name' => 'Dedicated Phone', 'icon' => 'DeP', 'link' => 'dedicated_phone', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 17, 'name' => 'Employee', 'icon' => 'EMP', 'link' => 'employee', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 18, 'name' => 'Furnitures', 'icon' => 'FUR', 'link' => 'furniture', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 19, 'name' => 'Marketing Materials', 'icon' => 'MM', 'link' => 'marketing_material', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 20, 'name' => 'Nature Of Business', 'icon' => 'NoB', 'link' => 'nature_of_business', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 21, 'name' => 'Non Cash', 'icon' => 'NC', 'link' => 'non_cash', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 22, 'name' => 'Product Category', 'icon' => 'PC', 'link' => 'product_category', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 23, 'name' => 'Product and Services', 'icon' => 'P&S', 'link' => 'product', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 24, 'name' => 'Packages', 'icon' => 'PAC', 'link' => 'package', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 25, 'name' => 'Referrals', 'icon' => 'REF', 'link' => 'referral', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 26, 'name' => 'Room Types', 'icon' => 'RT', 'link' => 'room_type', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 27, 'name' => 'Rooms', 'icon' => 'R', 'link' => 'room', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 28, 'name' => 'Task Subject', 'icon' => 'TaSu', 'link' => 'task_subject', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 29, 'name' => 'Ticketing Subject', 'icon' => 'TiSu', 'link' => 'ticketing_subject', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 30, 'name' => 'Vendor Category', 'icon' => 'VC', 'link' => 'vendor_category', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 31, 'name' => 'Vendor', 'icon' => 'V', 'link' => 'vendor', 'parent_id' => 2]);
        DB::table('modules')->insert(['id' => 32, 'name' => 'Sales Target', 'icon' => 'ST', 'link' => 'sales_target', 'parent_id' => 3]);
        DB::table('modules')->insert(['id' => 33, 'name' => 'Prospect Management', 'icon' => 'PM', 'link' => 'prospect', 'parent_id' => 3]);
        DB::table('modules')->insert(['id' => 34, 'name' => 'Sales Activity', 'icon' => 'SA', 'link' => 'sales_activity', 'parent_id' => 3]);
        DB::table('modules')->insert(['id' => 35, 'name' => 'Inquiries', 'icon' => 'Inq', 'link' => 'inquiry', 'parent_id' => 3]);
        DB::table('modules')->insert(['id' => 36, 'name' => 'Package', 'icon' => 'PAC', 'link' => 'booking_package', 'parent_id' => 4]);
        DB::table('modules')->insert(['id' => 37, 'name' => 'Serviced Office', 'icon' => 'SO', 'link' => 'serviced_office', 'parent_id' => 4]);
        DB::table('modules')->insert(['id' => 38, 'name' => 'Virtual Office', 'icon' => 'VO', 'link' => 'virtual_office', 'parent_id' => 4]);
        DB::table('modules')->insert(['id' => 39, 'name' => 'Regular Office', 'icon' => 'RO', 'link' => 'regular_office', 'parent_id' => 4]);
        DB::table('modules')->insert(['id' => 40, 'name' => 'Meeting Room', 'icon' => 'MR', 'link' => 'meeting_room', 'parent_id' => 4]);
        DB::table('modules')->insert(['id' => 41, 'name' => 'Co-Working', 'icon' => 'CW', 'link' => 'coworking', 'parent_id' => 4]);
        DB::table('modules')->insert(['id' => 42, 'name' => 'Hotel', 'icon' => 'HR', 'link' => 'hotel', 'parent_id' => 4]);
        DB::table('modules')->insert(['id' => 43, 'name' => 'Point Of Sales', 'icon' => 'POS', 'link' => 'point_of_sales', 'parent_id' => 5]);
        DB::table('modules')->insert(['id' => 44, 'name' => 'Purchase Order', 'icon' => 'PO', 'link' => 'purchase_order', 'parent_id' => 5]);
        DB::table('modules')->insert(['id' => 45, 'name' => 'Access Card', 'icon' => 'AC', 'link' => 'access_card_transaction', 'parent_id' => 5]);
        DB::table('modules')->insert(['id' => 46, 'name' => 'Dedicated Phone', 'icon' => 'DP', 'link' => 'dedicated_phone_transaction', 'parent_id' => 5]);
        DB::table('modules')->insert(['id' => 47, 'name' => 'Ticketing System', 'icon' => 'TS', 'link' => 'ticketing', 'parent_id' => 5]);
        DB::table('modules')->insert(['id' => 48, 'name' => 'Tasklist', 'icon' => 'TASK', 'link' => 'task', 'parent_id' => 5]);
        DB::table('modules')->insert(['id' => 49, 'name' => 'Proforma', 'icon' => 'Pro', 'link' => 'proforma', 'parent_id' => 6]);
        DB::table('modules')->insert(['id' => 50, 'name' => 'Invoices', 'icon' => 'Inv', 'link' => 'invoice', 'parent_id' => 6]);
        DB::table('modules')->insert(['id' => 51, 'name' => 'Deposits', 'icon' => 'Dep', 'link' => 'deposit', 'parent_id' => 6]);
        DB::table('modules')->insert(['id' => 52, 'name' => 'Payments', 'icon' => 'Pay', 'link' => 'payment', 'parent_id' => 6]);
        DB::table('modules')->insert(['id' => 53, 'name' => 'Booking Reminder', 'icon' => 'BoR', 'link' => 'booking_reminder', 'parent_id' => 7]);
        DB::table('modules')->insert(['id' => 54, 'name' => 'Billing Reminder', 'icon' => 'BiR', 'link' => 'billing_reminder', 'parent_id' => 7]);
        DB::table('modules')->insert(['id' => 55, 'name' => 'Collection Reminder', 'icon' => 'CoR', 'link' => 'collection_reminder', 'parent_id' => 7]);
        DB::table('modules')->insert(['id' => 56, 'name' => 'Marketing Report', 'icon' => 'MaR', 'link' => 'marketing_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 57, 'name' => 'Referrentor Report', 'icon' => 'ReR', 'link' => 'referentor_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 58, 'name' => 'Inquiry Report', 'icon' => 'INR', 'link' => 'inquiry_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 59, 'name' => 'Booking Report', 'icon' => 'BR', 'link' => 'booking_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 60, 'name' => 'Room Occupancy Report', 'icon' => 'ROR', 'link' => 'room_occupancy_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 61, 'name' => 'Virtual Office Report', 'icon' => 'VOR', 'link' => 'product_occupancy_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 62, 'name' => 'Complimentary Usage Report', 'icon' => 'CUR', 'link' => 'complimentary_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 63, 'name' => 'Invoice Report', 'icon' => 'IVR', 'link' => 'invoice_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 64, 'name' => 'Aging Invoice Report', 'icon' => 'AIR', 'link' => 'aging_invoice_report', 'parent_id' => 8]);
        DB::table('modules')->insert(['id' => 65, 'name' => 'Payment Report', 'icon' => 'PYR', 'link' => 'payment_report', 'parent_id' => 8]);

        /* DELETE DATA TO MODULES TABLE */
        DB::table('a_g_and_module')->delete();

        /* INSERT DATA TO MODULES TABLE */
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 1, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 2, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 3, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 4, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 5, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 6, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 7, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 8, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 9, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 10, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 11, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 12, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 13, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 14, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 15, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 16, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 17, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 18, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 19, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 20, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 21, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 22, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 23, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 24, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 25, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 26, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 27, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 28, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 29, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 30, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 31, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 32, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 33, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 34, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 35, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 36, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 37, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 38, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 39, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 40, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 41, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 42, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 43, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 44, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 45, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 46, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 47, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 48, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 49, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 50, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 51, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 52, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 53, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 54, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 55, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 56, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 57, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 58, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 59, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 60, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 61, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 62, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 63, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 64, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);
        DB::table('a_g_and_module')->insert(['access_group_id' => 1, 'module_id' => 65, 'read' => 1, 'create' => 1, 'update' => 1, 'delete' => 1, 'isExec' => 1]);


        /* DELETE DATA TO AGENT COMPANY TABLE */
        DB::table('agent_companies')->delete();
        /* INSERT DATA TO AGENT TABLE */
        DB::table('agent_companies')->insert([
            'id' => 1,
            'code' => 'RKIT',
            'name' => 'PT. Ruang Kreasi Teknologi Informasi',
            'email' => 'info@rakitek.com',
            'phone' => '01234567890',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'zipcode' => '12345',
            'address' => 'JL. TB Simatupang'
        ]);

        /* DELETE DATA TO NATURE OF BUSINESS TABLE */
        DB::table('nature_of_businesses')->delete();
        /* INSERT DATA TO NATURE OF BUSINESS TABLE */
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-001', 'name' => 'Extermination/PestControl']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-002', 'name' => 'Farming(Animal Production)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-003', 'name' => 'Farming(Crop Production)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-004', 'name' => 'Fishing/Hunting']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-005', 'name' => 'Landscape Service']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-006', 'name' => 'Lawn care Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-007', 'name' => 'Other (Agriculture & Forestry/Wildlife)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-008', 'name' => 'Consultant']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-009', 'name' => 'Employment Office']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-010', 'name' => 'Fundraisers']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-012', 'name' => 'Marketing/Advertising']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-013', 'name' => 'Non Profit Organization']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-014', 'name' => 'Notary Public']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-015', 'name' => 'Online Business']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-016', 'name' => 'Other (Business & Information)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-017', 'name' => 'Publishing Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-018', 'name' => 'Record Business']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-019', 'name' => 'Retail Sales']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-020', 'name' => 'Technology Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-021', 'name' => 'Telemarketing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-022', 'name' => 'Travel Agency']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-023', 'name' => 'Video Production']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-024', 'name' => 'AC & Heating']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-025', 'name' => 'Architect']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-026', 'name' => 'Building Construction']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-027', 'name' => 'Building Inspection']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-028', 'name' => 'Concrete Manufacturing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-029', 'name' => 'Contractor']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-030', 'name' => 'Engineering/Drafting']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-031', 'name' => 'Equipment Rental']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-032', 'name' => 'Other (Construction/Utilities/Contracting)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-033', 'name' => 'Plumbing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-034', 'name' => 'Remodeling']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-035', 'name' => 'Repair/Maintenance']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-036', 'name' => 'Child Care Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-037', 'name' => 'College/Universities']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-038', 'name' => 'Cosmetology School']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-039', 'name' => 'Elementary & Secondary Education']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-040', 'name' => 'GED Certification']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-041', 'name' => 'Other (Education)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-042', 'name' => 'Private School']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-043', 'name' => 'Real Estate School']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-044', 'name' => 'Technical School']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-045', 'name' => 'Trade School']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-046', 'name' => 'Tutoring Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-047', 'name' => 'Vocational School']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-048', 'name' => 'Accountant']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-049', 'name' => 'Auditing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-050', 'name' => 'Bank/Credit Union']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-051', 'name' => 'Bookkeeping']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-052', 'name' => 'Cash Advances']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-053', 'name' => 'Collection Agency']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-054', 'name' => 'Insurance']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-055', 'name' => 'Investor']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-056', 'name' => 'Other (Finance & Insurance)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-057', 'name' => 'Pawn Brokers']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-058', 'name' => 'Tax Preparation']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-059', 'name' => 'Alcohol/Tobacco Sales']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-060', 'name' => 'Alcoholic Beverage Manufacturing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-061', 'name' => 'Bakery']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-062', 'name' => 'Caterer']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-063', 'name' => 'Food/Beverage Manufacturing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-064', 'name' => 'Grocery/Convenience Store(Gas Station)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-065', 'name' => 'Grocery/Convenience Store(No Gas Station)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-066', 'name' => 'Hotels/Motels(Casino)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-067', 'name' => 'Hotels/Motels(No Casino)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-068', 'name' => 'Mobile Food Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-069', 'name' => 'Other (Food & Hospitality)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-070', 'name' => 'Restaurant/Bar']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-071', 'name' => 'Specialty Food(Fruit/Vegetables)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-072', 'name' => 'Specialty Food(Meat)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-073', 'name' => 'Specialty Food(Seafood)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-074', 'name' => 'Tobacco Product Manufacturing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-075', 'name' => 'Truck Stop']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-076', 'name' => 'Vending Machine']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-077', 'name' => 'Auctioneer']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-078', 'name' => 'Boxing/Wrestling']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-079', 'name' => 'Casino/Video Gaming']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-080', 'name' => 'Other (Gaming)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-081', 'name' => 'Racetrack']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-082', 'name' => 'Sports Agent']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-083', 'name' => 'Acupuncturist']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-084', 'name' => 'Athletic Trainer']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-085', 'name' => 'Child/Youth Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-086', 'name' => 'Chiropractic Office']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-087', 'name' => 'Dentistry']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-088', 'name' => 'Electrolysis']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-089', 'name' => 'Embalmer']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-090', 'name' => 'Emergency Medical Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-091', 'name' => 'Emergency Medical Transportation']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-092', 'name' => 'Hearing Aid Dealers']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-093', 'name' => 'Home Health Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-094', 'name' => 'Hospital']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-095', 'name' => 'Massage Therapy']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-096', 'name' => 'Medical Office']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-097', 'name' => 'Mental Health Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-098', 'name' => 'Non Emergency Medical Transportation']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-099', 'name' => 'Optometry']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-100', 'name' => 'Other (Health Services)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-101', 'name' => 'Pharmacy']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-102', 'name' => 'Physical Therapy']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-103', 'name' => 'Physicians Office']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-104', 'name' => 'Radiology']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-105', 'name' => 'Residential Care Facility']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-106', 'name' => 'Speech/Occupational Therapy']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-107', 'name' => 'Substance Abuse Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-108', 'name' => 'Veterinary Medicine']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-109', 'name' => 'Vocational Rehabilitation']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-110', 'name' => 'Wholesale Drug Distribution']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-111', 'name' => 'Automotive Part Sales']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-112', 'name' => 'Car Wash/Detailing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-113', 'name' => 'Motor Vehicle Rental']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-114', 'name' => 'Motor Vehicle Repair']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-115', 'name' => 'New Motor Vehicle Sales']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-116', 'name' => 'Other (Motor Vehicle)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-117', 'name' => 'Recreational Vehicle Sales']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-118', 'name' => 'Used Motor Vehicle Sales']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-119', 'name' => 'Conservation Organizations']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-120', 'name' => 'Environmental Health']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-121', 'name' => 'Land Surveying']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-122', 'name' => 'Oil & Gas Distribution']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-123', 'name' => 'Oil & Gas Extraction/Production']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-124', 'name' => 'Other (Natural Resources/Environmental)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-125', 'name' => 'Pipeline']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-126', 'name' => 'Water Well Drilling']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-127', 'name' => 'Other(Business Type Not Listed)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-128', 'name' => 'Animal Boarding']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-129', 'name' => 'Barber Shop']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-130', 'name' => 'Beauty Salon']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-131', 'name' => 'Cemetery']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-132', 'name' => 'Diet Center']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-133', 'name' => 'Dry cleaning/Laundry']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-134', 'name' => 'Entertainment/Party Rentals']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-135', 'name' => 'Event Planning']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-136', 'name' => 'Fitness Center']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-137', 'name' => 'Florist']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-138', 'name' => 'Funeral Director']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-139', 'name' => 'Janitorial/Cleaning Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-140', 'name' => 'Massage/Day Spa']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-141', 'name' => 'Nail Salon']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-142', 'name' => 'Other (Personal Services)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-143', 'name' => 'Personal Assistant']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-144', 'name' => 'Photography']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-145', 'name' => 'Tanning Salon']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-146', 'name' => 'Home Inspection']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-147', 'name' => 'Interior Design']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-148', 'name' => 'Manufactured Housing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-149', 'name' => 'Mortgage Company']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-150', 'name' => 'Other (Real Estate & Housing)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-151', 'name' => 'Property Management']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-152', 'name' => 'Real Estate Broker/Agent']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-153', 'name' => 'Warehouse/Storage']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-154', 'name' => 'Attorney']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-155', 'name' => 'Bail Bonds']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-156', 'name' => 'Court Reporter']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-157', 'name' => 'Drug Screening']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-158', 'name' => 'Locksmith']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-159', 'name' => 'Other (Safety/Security & Legal)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-160', 'name' => 'Private Investigator']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-161', 'name' => 'Security Guard']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-162', 'name' => 'Security System Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-163', 'name' => 'Air Transportation']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-164', 'name' => 'Boat Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-165', 'name' => 'Limousine Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-166', 'name' => 'Other (Transportation)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-167', 'name' => 'Taxi Services']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-168', 'name' => 'Towing']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-169', 'name' => 'Truck Transportation(Fuel)']);
        DB::table('nature_of_businesses')->insert(['code' => 'NoB-170', 'name' => 'Truck Transportation(Non Fuel)']);

        /* DELETE DATA TO AGENT TABLE */
        DB::table('agents')->delete();
        /* INSERT DATA TO AGENT TABLE */
        DB::table('agents')->insert([
            'id' => 1,
            'agent_company_id' => 1,
            'code' => 'AGT',
            'job_title' => 'Manager',
            'name' => 'Agent TS',
            'email' => 'agent.ts@rakitek.com',
            'phone' => '01234567890',
            'mobile_phone' => '081234567890',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'zipcode' => '12345',
            'address' => 'Alamat TS'
        ]);
        DB::table('agents')->insert([
            'id' => 2,
            'agent_company_id' => 1,
            'code' => '002',
            'job_title' => 'Staff',
            'name' => 'James Bond',
            'email' => 'james.bond@rakitek.com',
            'phone' => '01234567890',
            'mobile_phone' => '081234567890',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'zipcode' => '12345',
            'address' => 'M6'
        ]);
        DB::table('agents')->insert([
            'id' => 3,
            'agent_company_id' => null,
            'code' => '003',
            'job_title' => 'Freelancer Marketing',
            'name' => 'John',
            'email' => 'john@email.com',
            'phone' => '01234567890',
            'mobile_phone' => '081234567890',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'zipcode' => '12345',
            'address' => 'Monas'
        ]);

        /* DELETE DATA TO BANK ACCOUNT TABLE */
        DB::table('bank_accounts')->delete();
        /* INSERT DATA TO BANK ACCOUNT TABLE */
        DB::table('bank_accounts')->insert([
            'id' => 1,
            'account_no' => '123 45 67890',
            'account_name' => 'PT. Ruang Kreasi Teknologi Informasi',
            'bank_name' => 'Bank Mandiri',
            'branch_code' => 'The Manhattan Square',
            'swift_code' => '00112233',
            'currency_code' => 'IDR',
            'default' => 'Y'
        ]);
        DB::table('bank_accounts')->insert([
            'id' => 2,
            'account_no' => '120 00 12345',
            'account_name' => 'PT. Ruang Kreasi Teknologi Informasi',
            'bank_name' => 'Bank Mandiri Syariah',
            'branch_code' => '165 Tower',
            'swift_code' => '00223344',
            'currency_code' => 'IDR',
            'default' => 'N'
        ]);

        /* DELETE DATA TO CUSTOMER TABLE */
        DB::table('customers')->delete();

        /* INSERT DATA TO CUSTOMER TABLE */
        DB::table('customers')->insert([
            'id' => 1,
            'user_id' => null,
            'code' => '001',
            'customer_type' => 'COM',
            'name' => 'Sukamaju, PT',
            'brand_name' => 'SMJ',
            'email' => 'customer.sm@rakitek.com',
            'phone' => '01234567890',
            'mobile_phone' => '081234567890',
            'fax' => '01234567890',
            'address' => 'Alamat TS',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'zipcode' => '12345',
            'tax_number' => '555-123-321',
            'customer_status' => 'customer'
        ]);

        DB::table('customers')->insert([
            'id' => 2,
            'user_id' => null,
            'code' => '002',
            'customer_type' => 'COM',
            'name' => 'Majuterus, PT',
            'brand_name' => 'NJT',
            'email' => 'customer.mj@rakitek.com',
            'phone' => '01234567890',
            'mobile_phone' => '081234567890',
            'fax' => '01234567890',
            'address' => 'Alamat TS',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'zipcode' => '12345',
            'tax_number' => '555-123-322',
            'customer_status' => 'customer'
        ]);

        DB::table('customers')->insert([
            'id' => 3,
            'user_id' => null,
            'code' => '003',
            'customer_type' => 'IND',
            'name' => 'Michael Noob',
            'brand_name' => 'NOOB',
            'email' => 'michael.noob@rakitek.com',
            'phone' => '01234567890',
            'mobile_phone' => '081234567890',
            'fax' => '01234567890',
            'address' => 'Alamat TS',
            'country' => 'Indonesia',
            'city' => 'Jakarta',
            'zipcode' => '12345',
            'tax_number' => '555-123-321',
            'customer_status' => 'customer'
        ]);


        /* DELETE DATA TO CONTACT TABLE */
        DB::table('contacts')->delete();
        /* INSERT DATA TO CONTACT TABLE */
        DB::table('contacts')->insert([
            'code' => 'CONT-001',
            'id' => 1,
            'name' => 'Mr sukro'
        ]);
        DB::table('contacts')->insert([
            'code' => 'CONT-002',
            'id' => 2,
            'name' => 'Mr Maju'
        ]);
        DB::table('contacts')->insert([
            'code' => 'CONT-003',
            'id' => 3,
            'name' => 'Mr Terus'
        ]);

        /* INSERT DATA TO CUSTOMER CONTACT TABLE */
        DB::table('customer_and_contact')->insert([
            'customer_id' => 1,
            'contact_id' => 1,
            'default_status' => 'Y'
        ]);
        DB::table('customer_and_contact')->insert([
            'customer_id' => 2,
            'contact_id' => 2,
            'default_status' => 'Y'
        ]);
        DB::table('customer_and_contact')->insert([
            'customer_id' => 2,
            'contact_id' => 3
        ]);

        /* DELETE DATA TO REFERRALS TABLE */
        DB::table('referrals')->delete();
        /* INSERT DATA TO REFERRALS TABLE */
        DB::table('referrals')->insert([
            'code' => 'WI', 'name' => 'Walk In'
        ]);
        DB::table('referrals')->insert([
            'code' => 'FB', 'name' => 'Facebook'
        ]);
        DB::table('referrals')->insert([
            'code' => 'IG', 'name' => 'Instagram'
        ]);
        DB::table('referrals')->insert([
            'code' => 'TW', 'name' => 'Twitter'
        ]);
        DB::table('referrals')->insert([
            'code' => 'BRO', 'name' => 'Brochure'
        ]);
        DB::table('referrals')->insert([
            'code' => 'WEB', 'name' => 'Website'
        ]);

        /* DELETE DATA TO ROOM CATEGORY TABLE */
        DB::table('room_categories')->delete();
        /* INSERT DATA TO ROOM CATEGORY TABLE */
        DB::table('room_categories')->insert([
            'id' => 1,
            'code' => 'SO',
            'name' => 'Serviced Office',
            'main_status' => 'Y'
        ]);
        DB::table('room_categories')->insert([
            'id' => 2,
            'code' => 'MR',
            'name' => 'Meeting Room',
        ]);
        DB::table('room_categories')->insert([
            'id' => 3,
            'code' => 'CW',
            'name' => 'Co Working',
            'main_status' => 'Y'
        ]);
        DB::table('room_categories')->insert([
            'id' => 4,
            'code' => 'LO',
            'name' => 'Lodgement',
        ]);
        DB::table('room_categories')->insert([
            'id' => 5,
            'code' => 'RO',
            'name' => 'Regular Office',
        ]);

        /* DELETE DATA TO FURNITURES TABLE */
        DB::table('furniture')->delete();
        /* INSERT DATA TO FURNITURES TABLE */
        DB::table('furniture')->insert([
            'id' => 1,
            'code' => 'Executive Chairs',
            'name' => 'Executive Chairs'
        ]);
        DB::table('furniture')->insert([
            'id' => 2,
            'code' => 'Executive Desks',
            'name' => 'Executive Desks'
        ]);
        DB::table('furniture')->insert([
            'id' => 3,
            'code' => 'Cabinets',
            'name' => 'Cabinets'
        ]);
        DB::table('furniture')->insert([
            'id' => 4,
            'code' => 'Round Tables',
            'name' => 'Round Tables'
        ]);
        DB::table('furniture')->insert([
            'id' => 5,
            'code' => 'Pedestals',
            'name' => 'Pedestals'
        ]);
        DB::table('furniture')->insert([
            'id' => 6,
            'code' => 'Telephone Unit',
            'name' => 'Telephone Unit'
        ]);
        DB::table('furniture')->insert([
            'id' => 7,
            'code' => 'Waste Bins',
            'name' => 'Waste Bins'
        ]);

        /* DELETE DATA TO NON CASHES TABLE */
        DB::table('non_cashes')->delete();
        /* INSERT DATA TO NON CASHES TABLE */
        DB::table('non_cashes')->insert([
            'code' => 'TRF',
            'name' => 'Transfer',
            'has_bank' => 'Y',
            'has_card' => 'N'
        ]);
        DB::table('non_cashes')->insert([
            'code' => 'EDC',
            'name' => 'EDC',
            'has_bank' => 'Y',
            'has_card' => 'Y'
        ]);
        /* DELETE DATA TO STATUSES TABLE */
        DB::table('dedicated_phones')->delete();
        /* INSERT DATA TO STATUSES TABLE */
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-7812345'
        ]);
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-7823455'
        ]);
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-7823456'
        ]);
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-7823457'
        ]);
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-5412345'
        ]);
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-5412346'
        ]);
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-5412347'
        ]);
        DB::table('dedicated_phones')->insert([ 
            'number' => '021-5412348'
        ]);

        /* DELETE DATA TO STATUSES TABLE */
        DB::table('marketing_materials')->delete();
        /* INSERT DATA TO STATUSES TABLE */
        DB::table('marketing_materials')->insert([ 
            'code' => '01',
            'name' => 'cover',
            'file_type' => 'jpg',
            'file_path' => '/uploads/marketing_material/01.jpg'
        ]);
        DB::table('marketing_materials')->insert([ 
            'code' => '02',
            'name' => 'VO & SO',
            'file_type' => 'jpg',
            'file_path' => '/uploads/marketing_material/02.jpg'
        ]);

        /* DELETE DATA TO TICKETING SUBJECT TABLE */
        DB::table('ticketing_subjects')->delete();
        /* INSERT DATA TO TICKETING SUBJECT TABLE */
        DB::table('ticketing_subjects')->insert([ 
            'code' => '01',
            'name' => 'Room Trouble'
        ]);
        DB::table('ticketing_subjects')->insert([ 
            'code' => '02',
            'name' => 'Services'
        ]);

        /* DELETE DATA TO TASK SUBJECT TABLE */
        DB::table('task_subjects')->delete();
        /* INSERT DATA TO TASK SUBJECT TABLE */
        DB::table('task_subjects')->insert([ 
            'code' => '01',
            'name' => 'PABX Installation'
        ]);
        DB::table('task_subjects')->insert([ 
            'code' => '02',
            'name' => 'PABX Removing'
        ]);
        DB::table('task_subjects')->insert([ 
            'code' => '03',
            'name' => 'Clearing Room'
        ]);
        DB::table('task_subjects')->insert([ 
            'code' => '04',
            'name' => 'Clearing Room'
        ]);

        /* DELETE DATA TO STATUSES TABLE */
        DB::table('statuses')->delete();
        /* INSERT DATA TO STATUSES TABLE */
        DB::table('statuses')->insert([ // To Access This status need read, create,
            'name' => 'open',
            'action' => 'draft'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, update
            'name' => 'posted',
            'action' => 'posting'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, delete
            'name' => 'discard',
            'action' => 'discard'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, update, isExec
            'name' => 'complete',
            'action' => 'complete'
        ]);
        DB::table('statuses')->insert([ // To Access This status need read, create, de;ete, isExec
            'name' => 'void',
            'action' => 'cancel'
        ]);
    }
}
