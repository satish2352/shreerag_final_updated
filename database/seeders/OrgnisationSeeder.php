<?php

namespace Database\Seeders;
use App\Models\OrganizationModel;

use Illuminate\Database\Seeder;

class OrgnisationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrganizationModel::create(
            [
                'id'=> 1,
                'role_id'=> '1',
                'company_name'=> 'Share',
                'email'=> 'shree@gmail.com',
                'mobile_number'=> '1234567890',
                'address'=> 'Nasik',
                'image'=> 'null'   ,
                'employee_count'=> '1',
                'founding_date' => '2024-03-16',
                'instagram_link' => 'null',
                'facebook_link' => 'null',
                'twitter_link' => 'null',
                'website' => 'null',

            ]);
             
    }
}
