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
                // 'u_uname' => 'admin@gmail.com',
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
            ]);
                    

            User::create(
                [
                    'u_email' => 'owner@gmail.com',
                    // 'u_uname' => 'admin@gmail.com',
                    'u_password' => bcrypt('owner@gmail.com'),
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
                ]);
                    
                
                User::create(
                    [
                        'u_email' => 'purchase@gmail.com',
                        'u_password' => bcrypt('purchase@gmail.com'),
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
                    ]);
                    
                    
                    User::create(
                        [
                            'u_email' => 'design@gmail.com',
                            // 'u_uname' => 'design@gmail.com',
                            'u_password' => bcrypt('design@gmail.com'),
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
                        ]);

                        User::create(
                            [
                                'u_email' => 'prod@gmail.com',
                                // 'u_uname' => 'prod@gmail.com',
                                'u_password' => bcrypt('prod@gmail.com'),
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
                            ]);


                            User::create(
                                [
                                    'u_email' => 'store@gmail.com',
                                    // 'u_uname' => 'store@gmail.com',
                                    'u_password' => bcrypt('store@gmail.com'),
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
                                ]);
                                
    }
}