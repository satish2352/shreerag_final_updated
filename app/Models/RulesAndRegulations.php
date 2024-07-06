<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RulesAndRegulations extends Model
{
    use HasFactory;
    protected $table = 'tbl_rules_regulations';
    protected $primaryKey = 'id';
}
