<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignModel extends Model
{
    use HasFactory;
    protected $table = 'designs';
    protected $primaryKey = 'id';
}
