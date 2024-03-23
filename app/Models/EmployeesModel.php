<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_employees';
    protected $primaryKey = 'id';
    protected $fillable = ['employee_name', 'email', 'mobile_number', 'organization_id', 'address', 'image'];

    public function role()
    {
        return $this->belongsTo(RolesModel::class, 'role_id');
    }

    public function department()
    {
        return $this->belongsTo(DepartmentsModel::class, 'department_id');
    }

}
