<?php

namespace App\Models\Alf;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginuserModel extends Model
{
    use HasFactory;
    protected $table = 'loginuser';
    protected $primaryKey = 'id';
}
