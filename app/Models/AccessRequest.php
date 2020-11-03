<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRequest extends Model
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
