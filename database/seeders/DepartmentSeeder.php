<?php

namespace Database\Seeders;
use App\Models\DepartmentsModel;

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DepartmentsModel::create(
            [
                'id'=> 1,
                'department_name' => 'Owner',
            ]);
        DepartmentsModel::create(
            [
                'id'=> 2,
                'department_name' => 'Purchase',
            ]);
        DepartmentsModel::create(
            [
                'id'=>3,
                'department_name' => 'Designer',
            ]);
        DepartmentsModel::create(
            [
                'id'=> 4,
                'department_name' => 'Production',
            ]);
        DepartmentsModel::create(
            [
                'id'=>5,
                'department_name' => 'Store',
            ]);
        DepartmentsModel::create(
            [
                'id'=> 6,
                'department_name' => 'Security',
            ]);
        DepartmentsModel::create(
            [
                'id'=> 7,
                'department_name' => 'Quality',
            ]);
        DepartmentsModel::create(
            [
                'id'=> 8,
                'department_name' => 'Finance',
            ]);
        DepartmentsModel::create(
            [
                'id'=> 9,
                'department_name' => 'HR',
            ]);  
      
    }
}
