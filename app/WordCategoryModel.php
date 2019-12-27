<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WordCategoryModel extends Model
{
    //
    protected $table = 'word_category';
    public $timestamps = false;
    protected $fillable = ['name','pid','rank'];

    public function children()
    {
        return $this->hasMany(WordCategoryModel::class,'pid','id');
    }

    public function words()
    {
        return $this->belongsToMany(WordModel::class,'word_word_category','word_id','word_category_id');
    }
}
