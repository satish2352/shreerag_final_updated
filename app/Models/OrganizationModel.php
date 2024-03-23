<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_organizations';
    protected $primaryKey = 'id';
    protected $fillable = ['company_name','email', 'mobile_number'];
}
