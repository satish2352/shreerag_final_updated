<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_roles';
    protected $primaryKey = 'id';
    protected $fillable = ['id','role_name'];
}
