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

    public function getUkAudioAttribute($val){
        return url($val);
    }

    public function getUsAudioAttribute($val){
        return url($val);
    }

    public function translation()
    {

        $ts = new Translation($this->word);
        $info = $ts->getWordInfo();
        $deInfo = json_decode($info, true);

        if ($deInfo['errorCode'] == '0') {
            DB::transaction(function () use ($ts, $deInfo, $info) {
                $this->is_translation = 'yes';
                if (!isset($deInfo['basic'])) {
                    $deInfo['basic'] = [
                        'exam_type' => [],
                        'us-phonetic' => '无',
                        'uk-phonetic' => '无',
                        "phonetic" => "无",
                        "uk-speech" => $deInfo['speakUrl'],
                        "us-speech" => $deInfo['speakUrl'],
                        'explains' => $deInfo['translation']
                    ];
                }

                if (!isset($deInfo['web'])) {
                    $deInfo['web'] = [];
                }
                $this->ts_info = json_encode($deInfo);
                list($ukSpeechUrl,$usSpeechUrl) =   $ts->downloadSpeakFile($deInfo['basic']);
                $this->uk_audio = $ukSpeechUrl;
                $this->us_audio = $usSpeechUrl;
            });
        } else{
            throw new ModelInternalException('翻译失败');
        }

        return $this;
    }
}
