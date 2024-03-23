<?php

namespace Database\Seeders;
use App\Models\RolesModel;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolesModel::create(
            [
                'id'=> 1,
                'role_name' => 'Head',
            ]);
        RolesModel::create(
            [
                'id'=> 2,
                'role_name' => 'Cleark',
            ]);
        
    }
}
