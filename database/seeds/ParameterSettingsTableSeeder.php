<?php

use Illuminate\Database\Seeder;

class ParameterSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* DELETE DATA TO PARAMETER_SETTINGS TABLE */
        DB::table('parameter_settings')->delete();
        /* INSERT DATA TO PARAMETER_SETTINGS TABLE */
        DB::table('parameter_settings')->insert([
            'name' => 'company_name', 'string_value' => 'PT Ruang Kreasi Inovasi Teknologi'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'company_address_1', 'string_value' => 'MENARA 165, 4th Floor'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'company_address_2', 'string_value' => 'Jl TB. Simatupang, Kav.1 Cilandak, Jakarta 12560'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'company_phone', 'string_value' => '+62 21 8064 1029'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'company_fax', 'string_value' => '+62 21 8064 1000'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'holidays', 'string_value' => 'SAT'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'holidays', 'string_value' => 'SUN'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'default_password', 'string_value' => 'rakitek2018'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'office_hour_start', 'int_value' => 7
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'office_hour_end', 'int_value' => 17
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'after_office_hour_end', 'int_value' => 23
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'tax_percentage', 'double_value' => 0.1
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'service_charge', 'double_value' => 0.1
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'cancellation_fee', 'int_value' => 200000
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'add_days_to_due_date', 'int_value' => '10' // Y = Yes; N = No
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'default_currency', 'string_value' => 'IDR'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'reminder_default_month', 'int_value' => 3
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'director_name', 'string_value' => 'Mr. Ahmadi'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'default_stamp_duty', 'int_value' => 6000
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'penalty_invoice_total_per_day', 'double_value' => 0.0001
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'finance_controller', 'string_value' => 'John Doe'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'bypass_zero_transaction', 'string_value' => 'Y'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'main_path', 'string_value' => 'local'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'add_or_minus_day', 'int_value' => 1
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'date_format', 'string_value' => 'm/d/Y'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'time_format', 'string_value' => 'H:i'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'has_free_booking', 'string_value' => 'Y'
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'total_halfday_term', 'int_value' => 4
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'total_mod_rounding', 'int_value' => 100
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'hotel_start_time', 'int_value' => 13
        ]);
        DB::table('parameter_settings')->insert([
            'name' => 'hotel_end_time', 'int_value' => 12
        ]);
    }
}
