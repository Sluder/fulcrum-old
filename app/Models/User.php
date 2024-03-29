<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * Attributes that are not mass assignable
     */
    protected $guarded = [];

    /**
     * Remove updated/created columns
     */
    public $timestamps = false;
}
