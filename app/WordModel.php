<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordModel extends Model
{
    //
    protected $table = 'word';
    public $timestamps = false;
    protected $fillable = ['word','is_translation','info'];
}
