<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
   protected $fillable = [
       'name', 'region_code',
    ];
    use HasFactory;
}
