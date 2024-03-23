<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentsModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_departments';
    protected $primaryKey = 'id';
    protected $fillable = ['id','department_name'];
}
