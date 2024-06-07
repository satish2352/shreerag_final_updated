<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYearLeaveRecord extends Model
{
    use HasFactory;
    protected $table = 'tbl_financial_year_leave_record';
    protected $primaryKey = 'id';
}
