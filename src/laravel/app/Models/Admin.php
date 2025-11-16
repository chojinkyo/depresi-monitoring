<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Exceptions\Handler;

class Admin extends Model
{
    //
    protected $guarded = [
        'id', 'created_at', 'updated_at', 
    ];
}
