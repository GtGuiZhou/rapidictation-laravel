<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class FileModel extends Model
{
    //
    public $timestamps = false;
    protected $table = 'file';
    protected $fillable = ['url','md5'];

    public static function saveFile(UploadedFile $file)
    {
        $fileName = $file->hashName();
        $file->move(public_path('uploads'),$fileName);
        $url = '/uploads/'.$fileName;
        $md5 = md5($url);
        return self::create(compact('url','md5'));;
    }
}
