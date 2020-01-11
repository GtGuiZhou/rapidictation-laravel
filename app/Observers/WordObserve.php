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
       $wordModel->translation();
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
