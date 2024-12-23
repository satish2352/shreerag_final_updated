<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnableChalan extends Model
{
    use HasFactory;
    protected $table = 'tbl_returnable_chalan';
    protected $primaryKey = 'id';

    public function returnableChalan()
{
    return $this->belongsTo(Business::class, 'business_id');
}
}
