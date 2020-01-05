<?php

namespace App;


use App\Exceptions\ModelInternalException;
use App\YouDao\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WordModel extends Model
{
    //
    protected $table = 'word';
    public $timestamps = false;
    protected $fillable = ['word','is_translation','info'];



    public function setWordAttribute($val)
    {
        $this->attributes['word'] = strtolower($val);
    }

    public function getUsAudioAttribute($val)
    {
        return 'test';
    }


    public function translation()
    {
        $ts = new Translation($this->word);
        $info = $ts->getWordInfo();
        $deInfo = json_decode($info, true);

        if ($deInfo['errorCode'] == '0') {
            DB::transaction(function () use ($ts, $deInfo, $info) {
                $this->is_translation = 'yes';
                $this->ts_info = $info;
                if (!isset($deInfo['basic'])) {
                    throw new ModelInternalException('词义不存在，该单词可能不是一个正确的单词');
                } else {
                    list($ukSpeechUrl,$usSpeechUrl) =   $ts->downloadSpeakFile($deInfo['basic']);
                    $this->uk_audio = $ukSpeechUrl;
                    $this->us_audio = $usSpeechUrl;
                }
            });
        } else{
            throw new ModelInternalException('翻译失败');
        }

        return $this;
    }
}
