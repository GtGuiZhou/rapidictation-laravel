<?php

namespace App;

use App\Exceptions\InvalidRequestException;
use Illuminate\Database\Eloquent\Model;

class ActiveCodeModel extends Model
{
    //

    protected $table = 'active_code';
    public $timestamps = false;
    protected $fillable = ['code','status','machine_code'];


    public function release()
    {
        if ($this->status != 'normal'){
            throw new InvalidRequestException('该激活码已被使用');
        }
        $this->status = 'release';
        $this->save();
    }

}
