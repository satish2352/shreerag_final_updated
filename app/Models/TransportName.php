<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportName extends Model
{
    use HasFactory;
    protected $table = 'tbl_transport_name';
    protected $primaryKey = 'id';
    
}
