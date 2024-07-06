<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedChalan extends Model
{
    use HasFactory;
    protected $table = 'tbl_rejected_chalan';
    protected $primaryKey = 'id';
}
