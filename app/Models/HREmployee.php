<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HREmployee extends Model
{
    use HasFactory;

    protected $table = 'tbl_hr_employees';
    protected $primaryKey = 'id';

    // public function role()
    // {
    //     return $this->belongsTo(RolesModel::class, 'role_id');
    // }

    // public function department()
    // {
    //     return $this->belongsTo(DepartmentsModel::class, 'department_id');
    // }

}
