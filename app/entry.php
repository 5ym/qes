<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class entry extends Model
{
    protected $fillable = ['name', 'contact', 'address', 'randum', 'status']; //書き込み可能カラム
}
