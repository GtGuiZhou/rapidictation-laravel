<?php

namespace App\Observers;

use App\Exceptions\InvalidRequestException;
use App\WordModel;
use App\YouDao\Translation;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class WordObserve
{
    /**
     * Handle the word model "created" event.
     *
     * @param \App\WordModel $wordModel
     * @return void
     */
    public function created(WordModel $wordModel)
    {
        //
    }

    /**
     * Handle the word model "updated" event.
     *
     * @param \App\WordModel $wordModel
     * @return void
     */
    public function updated(WordModel $wordModel)
    {
        //
    }

    public function saving(WordModel $wordModel)
    {
        $ts = new Translation($wordModel->word);
        $info = $ts->getWordInfo();
        $deInfo = json_decode($info, true);

        if ($deInfo['errorCode'] == '0') {
            DB::transaction(function () use ($wordModel,$ts, $deInfo, $info) {
                $wordModel->is_translation = 'yes';
                $wordModel->ts_info = $info;
                if (!isset($deInfo['basic'])) {
                    throw new InvalidRequestException('词义不存在，该单词可能不是一个正确的单词');
                } else {
                    list($ukSpeechUrl,$usSpeechUrl) =   $ts->downloadSpeakFile($deInfo['basic']);
                    $wordModel->uk_audio = $ukSpeechUrl;
                    $wordModel->us_audio = $usSpeechUrl;
                }
            });
        } else{
            throw new InvalidRequestException('翻译失败');
        }
    }

    /**
     * Handle the word model "deleted" event.
     *
     * @param \App\WordModel $wordModel
     * @return void
     */
    public function deleted(WordModel $wordModel)
    {
        //
    }

    /**
     * Handle the word model "restored" event.
     *
     * @param \App\WordModel $wordModel
     * @return void
     */
    public function restored(WordModel $wordModel)
    {
        //
    }

    /**
     * Handle the word model "force deleted" event.
     *
     * @param \App\WordModel $wordModel
     * @return void
     */
    public function forceDeleted(WordModel $wordModel)
    {
        //
    }
}
