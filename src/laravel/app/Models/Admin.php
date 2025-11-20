<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Exceptions\Handler;

class Admin extends Model
{
    //
    protected $guarded = [
        'id', 'created_at', 'updated_at', 
    ];
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
