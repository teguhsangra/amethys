<?php

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // this is seeder based on every project

        // this is GRHA 165 Project

        /* DELETE DATA TO VENDOR CATEGORY TABLE */
        DB::table('vendor_categories')->delete();
        /* INSERT DATA TO VENDOR CATEGORY TABLE */
    	DB::table('vendor_categories')->insert([
            'id' => 1,
            'code' => 'CAT',
    		'name' => 'Catering'
        ]);
    	DB::table('vendor_categories')->insert([
            'id' => 2,
            'code' => 'ELC',
    		'name' => 'Electricity'
        ]);
    	DB::table('vendor_categories')->insert([
            'id' => 3,
            'code' => 'SS',
    		'name' => 'Sound System'
        ]);
    	DB::table('vendor_categories')->insert([
            'id' => 4,
            'code' => 'STG',
    		'name' => 'Stage Decoration'
        ]);

        /* DELETE DATA TO VENDOR TABLE */
        DB::table('vendors')->delete();
        /* INSERT DATA TO VENDOR TABLE */
    	DB::table('vendors')->insert([
            'id' => 1,
            'code' => 'V-001',
    		'name' => 'Rumah Makan Gitu Dech'
        ]);
        DB::table('v_c_and_vendor')->insert(['vendor_category_id' => 1, 'vendor_id' => 1]);

    	DB::table('vendors')->insert([
            'id' => 2,
            'code' => 'V-002',
    		'name' => 'Catering Papa mama'
        ]);
        DB::table('v_c_and_vendor')->insert(['vendor_category_id' => 1, 'vendor_id' => 2]);

    	DB::table('vendors')->insert([
            'id' => 3,
            'code' => 'V-003',
    		'name' => 'Toko Listrik ABC'
        ]);
        DB::table('v_c_and_vendor')->insert(['vendor_category_id' => 2, 'vendor_id' => 3]);

    	DB::table('vendors')->insert([
            'id' => 4,
            'code' => 'V-004',
    		'name' => 'Sound System'
        ]);
        DB::table('v_c_and_vendor')->insert(['vendor_category_id' => 3, 'vendor_id' => 4]);

        DB::table('vendors')->insert([
            'id' => 5,
            'code' => 'V-005',
    		'name' => 'Stage Creative'
        ]);
        DB::table('v_c_and_vendor')->insert(['vendor_category_id' => 4, 'vendor_id' => 5]);

    	/* DELETE DATA TO ROOM TABLE */
        DB::table('rooms')->delete();
    	/* INSERT DATA TO ROOM TABLE */
    	DB::table('rooms')->insert([
            'id' => 1,
            'location_id' => 1,
    		'code' => 'Ro-001',
            'room_number' => 'SO-101',
            'daily_price' => 500000,
            'halfday_price' => 300000,
            'monthly_price' => 5000000,
            'sqm' => 5,
            'number_of_workstation' => 2,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 2,
            'location_id' => 1,
    		'code' => 'Ro-002',
            'room_number' => 'SO-102',
            'daily_price' => 600000,
            'halfday_price' => 400000,
            'monthly_price' => 6000000,
            'sqm' => 7,
            'number_of_workstation' => 3,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 3,
            'location_id' => 1,
    		'code' => 'Ro-003',
            'room_number' => 'SO-103',
            'daily_price' => 1000000,
            'halfday_price' => 600000,
            'monthly_price' => 10000000,
            'sqm' => 12,
            'number_of_workstation' => 5,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 4,
            'location_id' => 2,
    		'code' => 'Ro-004',
            'room_number' => 'SO-201',
            'daily_price' => 400000,
            'halfday_price' => 250000,
            'monthly_price' => 4000000,
            'sqm' => 5,
            'number_of_workstation' => 2,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 5,
            'location_id' => 2,
    		'code' => 'Ro-005',
            'room_number' => 'SO-202',
            'daily_price' => 500000,
            'halfday_price' => 300000,
            'monthly_price' => 5000000,
            'sqm' => 7,
            'number_of_workstation' => 3,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 6,
            'location_id' => 2,
    		'code' => 'Ro-006',
            'room_number' => 'SO-203',
            'daily_price' => 800000,
            'halfday_price' => 500000,
            'monthly_price' => 8000000,
            'sqm' => 12,
            'number_of_workstation' => 5,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 7,
            'location_id' => 1,
    		'code' => 'Ro-007',
            'room_number' => 'MR-JKT-01',
            'holiday_hourly_price' => 400000,
            'after_office_hourly_price' => 300000,
            'hourly_price' => 200000,
            'halfday_price' => 600000,
            'daily_price' => 1000000,
            'sqm' => 20,
            'number_of_workstation' => 10,
            'has_service_charge' => 'Y',
            'is_editable_price' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 8,
            'location_id' => 1,
    		'code' => 'Ro-008',
            'room_number' => 'MR-JKT-02',
            'holiday_hourly_price' => 400000,
            'after_office_hourly_price' => 300000,
            'hourly_price' => 150000,
            'halfday_price' => 400000,
            'daily_price' => 750000,
            'sqm' => 14,
            'number_of_workstation' => 6,
            'has_service_charge' => 'Y',
            'is_editable_price' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 9,
            'location_id' => 2,
    		'code' => 'Ro-009',
            'room_number' => 'MR-PYK-01',
            'holiday_hourly_price' => 400000,
            'after_office_hourly_price' => 300000,
            'hourly_price' => 200000,
            'halfday_price' => 600000,
            'daily_price' => 1000000,
            'sqm' => 20,
            'number_of_workstation' => 10,
            'has_service_charge' => 'Y',
            'is_editable_price' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 10,
            'location_id' => 2,
    		'code' => 'Ro-010',
            'room_number' => 'MR-PYK-02',
            'holiday_hourly_price' => 400000,
            'after_office_hourly_price' => 300000,
            'hourly_price' => 150000,
            'halfday_price' => 400000,
            'daily_price' => 1000000,
            'sqm' => 20,
            'number_of_workstation' => 10,
            'has_service_charge' => 'Y',
            'is_editable_price' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 11,
            'location_id' => 1,
    		'code' => 'Ro-011',
            'room_number' => 'CW-JKT-01',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 25000,
            'halfday_price' => 100000,
            'daily_price' => 150000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 12,
            'location_id' => 1,
    		'code' => 'Ro-012',
            'room_number' => 'CW-JKT-02',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 25000,
            'halfday_price' => 100000,
            'daily_price' => 150000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 13,
            'location_id' => 1,
    		'code' => 'Ro-013',
            'room_number' => 'CW-JKT-03',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 25000,
            'halfday_price' => 100000,
            'daily_price' => 150000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 14,
            'location_id' => 1,
    		'code' => 'Ro-014',
            'room_number' => 'CW-JKT-04',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 25000,
            'halfday_price' => 100000,
            'daily_price' => 150000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 15,
            'location_id' => 2,
    		'code' => 'Ro-015',
            'room_number' => 'CW-PYK-01',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 20000,
            'halfday_price' => 80000,
            'daily_price' => 120000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 16,
            'location_id' => 2,
    		'code' => 'Ro-016',
            'room_number' => 'CW-PYK-02',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 20000,
            'halfday_price' => 80000,
            'daily_price' => 120000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 17,
            'location_id' => 2,
    		'code' => 'Ro-017',
            'room_number' => 'CW-PYK-03',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 20000,
            'halfday_price' => 80000,
            'daily_price' => 120000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'N'
        ]);
    	DB::table('rooms')->insert([
            'id' => 18,
            'location_id' => 2,
    		'code' => 'Ro-018',
            'room_number' => 'CW-PYK-04',
            'holiday_hourly_price' => 40000,
            'after_office_hourly_price' => 30000,
            'hourly_price' => 20000,
            'halfday_price' => 80000,
            'daily_price' => 120000,
            'sqm' => 1,
            'number_of_workstation' => 1,
            'has_service_charge' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 19,
            'location_id' => 1,
    		'code' => 'Ro-019',
            'room_number' => 'HOT-JKT-01',
            'daily_price' => 250000,
            'daily_exclude_breakfast_price' => 200000,
            'sqm' => 12,
            'number_of_workstation' => 2,
            'has_service_charge' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 20,
            'location_id' => 1,
    		'code' => 'Ro-020',
            'room_number' => 'HOT-JKT-02',
            'daily_price' => 250000,
            'daily_exclude_breakfast_price' => 200000,
            'sqm' => 12,
            'number_of_workstation' => 2,
            'has_service_charge' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 21,
            'location_id' => 2,
    		'code' => 'Ro-021',
            'room_number' => 'HOT-PYK-01',
            'daily_price' => 180000,
            'daily_exclude_breakfast_price' => 150000,
            'sqm' => 12,
            'number_of_workstation' => 2,
            'has_service_charge' => 'Y'
        ]);
    	DB::table('rooms')->insert([
            'id' => 22,
            'location_id' => 2,
    		'code' => 'Ro-022',
            'room_number' => 'HOT-PYK-02',
            'daily_price' => 180000,
            'daily_exclude_breakfast_price' => 150000,
            'sqm' => 12,
            'number_of_workstation' => 2,
            'has_service_charge' => 'Y'
        ]);

        DB::table('r_c_and_room')->insert([
            'room_category_id' => 1,
            'room_id' => 1
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 1,
            'room_id' => 2
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 1,
            'room_id' => 3
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 1,
            'room_id' => 4
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 1,
            'room_id' => 5
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 1,
            'room_id' => 6
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 2,
            'room_id' => 7
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 2,
            'room_id' => 8
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 2,
            'room_id' => 9
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 2,
            'room_id' => 10
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 11
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 12
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 13
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 14
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 15
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 16
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 17
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 3,
            'room_id' => 18
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 4,
            'room_id' => 19
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 4,
            'room_id' => 20
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 4,
            'room_id' => 21
        ]);
        DB::table('r_c_and_room')->insert([
            'room_category_id' => 4,
            'room_id' => 22
        ]);

    	/* DELETE DATA TO PRODUCT TABLE */
        DB::table('product_categories')->delete();
    	/* INSERT DATA TO PRODUCT TABLE */
        DB::table('product_categories')->insert([ // To Access This status need read, create,
            'id' => 1,
            'code' => 'VO',
            'name' => 'Virtual Office'
        ]);

    	/* DELETE DATA TO PRODUCT TABLE */
        DB::table('products')->delete();
    	/* INSERT DATA TO PRODUCT TABLE */
    	DB::table('products')->insert([
            'id' => 1,
    		'code' => 'BA',
            'name' => 'Business Address',
            'price_type' => 'monthly',
            'type' => 'service',
            'price' => 500000,
            'main_status' => 'Y',
            'quantity_status' => 'N',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'N'
        ]);
    	DB::table('products')->insert([
            'id' => 2,
    		'code' => 'VO',
            'name' => 'Virtual Office',
            'price_type' => 'monthly',
            'type' => 'service',
            'price' => 800000,
            'main_status' => 'Y',
            'quantity_status' => 'N',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'N'
        ]);
    	DB::table('products')->insert([
            'id' => 3,
    		'code' => 'PRM',
            'name' => 'Premium Virtual Office',
            'price_type' => 'monthly',
            'type' => 'service',
            'price' => 1000000,
            'main_status' => 'Y',
            'quantity_status' => 'N',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'N'
        ]);
        DB::table('p_c_and_product')->insert([
            'product_category_id' => 1,
            'product_id' => 1
        ]);
        DB::table('p_c_and_product')->insert([
            'product_category_id' => 1,
            'product_id' => 2
        ]);
        DB::table('p_c_and_product')->insert([
            'product_category_id' => 1,
            'product_id' => 3
        ]);

    	DB::table('products')->insert([
            'id' => 4,
    		'code' => 'TELP',
            'name' => 'Telephone',
            'has_service_charge' => 'Y',
            'price_type' => 'monthly',
            'type' => 'service',
            'price' => 250000,
            'main_status' => 'N',
            'quantity_status' => 'Y',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('products')->insert([
            'id' => 5,
    		'code' => 'INET',
            'name' => 'Internet',
            'has_service_charge' => 'Y',
            'price_type' => 'monthly',
            'type' => 'service',
            'price' => 400000,
            'main_status' => 'N',
            'quantity_status' => 'N',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('products')->insert([
            'id' => 6,
    		'code' => 'PARK',
            'name' => 'Parking',
            'has_service_charge' => 'Y',
            'price_type' => 'monthly',
            'type' => 'service',
            'price' => 200000,
            'main_status' => 'N',
            'quantity_status' => 'N',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('products')->insert([
            'id' => 7,
    		'code' => 'HT',
            'name' => 'Hot Tea',
            'has_service_charge' => 'Y',
            'price_type' => 'single',
            'type' => 'goods',
            'price' => 5000,
            'main_status' => 'N',
            'quantity_status' => 'Y',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('products')->insert([
            'id' => 8,
    		'code' => 'BC',
            'name' => 'Black Coffee',
            'has_service_charge' => 'Y',
            'price_type' => 'single',
            'type' => 'goods',
            'price' => 10000,
            'main_status' => 'N',
            'quantity_status' => 'Y',
            'is_editable_price' => 'N',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('products')->insert([
            'id' => 9,
    		'code' => 'PP',
            'name' => 'Printing Paper',
            'has_service_charge' => 'Y',
            'price_type' => 'single',
            'type' => 'goods',
            'price' => 1000,
            'main_status' => 'N',
            'quantity_status' => 'Y',
            'is_editable_price' => 'N',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('products')->insert([
            'id' => 10,
    		'code' => 'SD',
            'name' => 'Softdrink',
            'has_service_charge' => 'Y',
            'price_type' => 'single',
            'type' => 'goods',
            'price' => 8000,
            'main_status' => 'N',
            'quantity_status' => 'Y',
            'is_editable_price' => 'N',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('products')->insert([
            'id' => 11,
    		'code' => 'SIGN',
            'name' => 'Company Signage',
            'has_service_charge' => 'Y',
            'price_type' => 'yearly',
            'type' => 'service',
            'price' => 1000000,
            'main_status' => 'N',
            'quantity_status' => 'N',
            'is_editable_price' => 'Y',
            'has_service_charge' => 'Y'
        ]);

    	/* DELETE DATA TO COMPLIMENTARY TABLE */
        DB::table('complimentarys')->delete();
    	/* INSERT DATA TO COMPLIMENTARY TABLE */
    	DB::table('complimentarys')->insert([
            'room_category_id' => 2,
            'code' => 'COM-001',
            'used_for_url' => 'meeting_room',
            'name' => 'Free Meeting Room In Hour(s)',
            'price_type' => 'hourly'
        ]);
    	/* INSERT DATA TO COMPLIMENTARY TABLE */
    	DB::table('complimentarys')->insert([
            'room_category_id' => 3,
            'code' => 'COM-002',
            'used_for_url' => 'coworking',
            'name' => 'Free Coworking Room In Day(s)',
            'price_type' => 'daily'
        ]);


    	/* DELETE DATA TO PACKAGE TABLE */
        DB::table('packages')->delete();
    	/* INSERT DATA TO COMPLIMENTARY TABLE */
    	DB::table('packages')->insert([
            'id' => 1,
            'location_id' => 1,
            'code' => 'PKG-001',
            'name' => 'Meeting Room Package JKT - 01',
            'price' => 100000,
            'price_type' => 'daily',
            'quantity_status' => 'Y',
            'has_service_charge' => 'Y'
        ]);
    	DB::table('packages')->insert([
            'id' => 2,
            'location_id' => 1,
            'code' => 'PKG-002',
            'name' => 'Meeting Room Package JKT - 02',
            'price' => 100000,
            'price_type' => 'daily',
            'quantity_status' => 'Y',
            'has_service_charge' => 'Y'
        ]);

        DB::table('package_and_room')->insert([
            'package_id' => 1,
            'room_id' => 8
        ]);
    }
}
