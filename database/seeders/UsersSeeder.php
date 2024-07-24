<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create(
            [
                'u_email' => 'admin@gmail.com',
                'u_password' => bcrypt('admin@gmail.com'),
                'role_id' => 111,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );


        User::create(
            [
                'u_email' => 'owner@gmail.com',
                'u_password' => bcrypt('FfAtgNQorb'),
                'role_id' => 1,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );
        User::create(
            [
                'u_email' => 'design@gmail.com',
                'u_password' => bcrypt('TetmUsgjDf'),
                'role_id' => 3,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );

        User::create(
            [
                'u_email' => 'prod@gmail.com',
                'u_password' => bcrypt('YeGClqIloN'),
                'role_id' => 4,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );


        User::create(
            [
                'u_email' => 'store@gmail.com',
                'u_password' => bcrypt('SoQUNvApdw'),
                'role_id' => 7,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );

        User::create(
            [
                'u_email' => 'purchase@gmail.com',
                'u_password' => bcrypt('gyGkQFxefV'),
                'role_id' => 2,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );

        User::create(
            [
                'u_email' => 'security@gmail.com',
                'u_password' => bcrypt('YoErlSVVzu'),
                'role_id' => 5,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );


        User::create(
            [
                'u_email' => 'quality@gmail.com',
                'u_password' => bcrypt('QaxFFsOdRR'),
                'role_id' => 6,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );

        User::create(
            [
                'u_email' => 'finance@gmail.com',
                'u_password' => bcrypt('ordIoEffAh'),
                'role_id' => 8,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );


        User::create(
            [
                'u_email' => 'hr@gmail.com',
                'u_password' => bcrypt('zaEAYMmBCa'),
                'role_id' => 9,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );

        
        User::create(
            [
                'u_email' => 'logistics@gmail.com',
                'u_password' => bcrypt('logistics@123'),
                'role_id' => 10,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );
        User::create(
            [
                'u_email' => 'dispatch@gmail.com',
                'u_password' => bcrypt('dispatch@123'),
                'role_id' => 11,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );

        User::create(
            [
                'u_email' => 'cms@gmail.com',
                'u_password' => bcrypt('cms@123'),
                'role_id' => 12,
                'org_id' => 0,
                'f_name' => 'fname',
                'm_name' => 'mname',
                'l_name' => 'lname',
                'number' => 'number',
                'designation' => 'designation',
                'address' => 'address',
                'state' => 'state',
                'city' => 'city',
                'pincode' => 'pincode',
                'ip_address' => '192.168.1.32',
            ]
        );
        
    }


}