<?php

namespace App\Observers;

use App\Journal;
use App\Notifications\JournalAdded;
use App\User;

class JournalObserver
{
    /**
     * Handle the journal "created" event.
     *
     * @param  \App\Journal  $journal
     * @return void
     */
    public function created(Journal $journal)
    {

        //TODO check to see if the user actually wanted notifying etc
        $users = User::all();
        foreach($users as $user){
            $user->notify(new JournalAdded($journal));
        }

    }

    /**
     * Handle the journal "updated" event.
     *
     * @param  \App\Journal  $journal
     * @return void
     */
    public function updated(Journal $journal)
    {
        //
    }

    /**
     * Handle the journal "deleted" event.
     *
     * @param  \App\Journal  $journal
     * @return void
     */
    public function deleted(Journal $journal)
    {
        //
    }

    /**
     * Handle the journal "restored" event.
     *
     * @param  \App\Journal  $journal
     * @return void
     */
    public function restored(Journal $journal)
    {
        //
    }

    /**
     * Handle the journal "force deleted" event.
     *
     * @param  \App\Journal  $journal
     * @return void
     */
    public function forceDeleted(Journal $journal)
    {
        //
    }
}
