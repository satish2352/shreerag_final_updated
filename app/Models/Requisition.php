<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;
    protected $table = 'requisition';
    protected $primaryKey = 'id';

    public function requisition()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
}
