<?php

namespace App;

use Illuminate\Support\Facades\Hash;

class AdminModel extends \Illuminate\Foundation\Auth\User
{
    //
    public $timestamps = false;
    protected $table = 'admin';
    protected $fillable = ['username','password'];


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

}
