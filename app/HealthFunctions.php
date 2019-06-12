<?php


namespace App;


use Illuminate\Support\Facades\DB;
use PhpMyAdmin\Tests\HeaderTest;

class HealthFunctions
{

    protected $journal;

    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
        $this->health();
    }

    private function health(){





        $collectionUnique = DB::select(DB::raw('SELECT COUNT(DISTINCT `doi`) AS Count from outputs WHERE `journal_id` = '.$this->journal->id));
        $collectionUnique = $collectionUnique[0]->Count;

        $collectionTotal = DB::select(DB::raw('SELECT count(*) FROM `outputs` WHERE `journal_id` =' .$this->journal->id));
        $collectionTotal = $collectionTotal[0]->{'count(*)'};

        $collectionDupes = DB::select(DB::raw('Select count(*) - count(distinct doi, journal_id = '.$this->journal->id . ') as Dupes from outputs' ));
        $collectionDupes = $collectionDupes[0]->Dupes;

//       Health::updateOrCreate(
//            [
//                'journal_id' => $this->journal->id,
//                'created_at' => Health::where('created_at', '>', DB::raw('CURDATE()'))->first()->created_at ?? null
//            ],
//            [
//
//                // $table->datetime('journal_updater_ran')->nullable();
//                //            $table->datetime('output_updater_ran')->nullable();
//                //            $table->integer('new_outputs')->nullable();
//                           $table->integer('total_articles')->nullable();
//                //            $table->integer('missing_abstracts')->nullable();
//                //            $table->datetime('backup_ran')->nullable();
//                //            $table->string('backup_string')->nullable();
//                           $table->unsignedInteger('journal_id');
//              dd('hello')
//            ]
//        );
//
//        Health::updateorCreate('');

        dd('Total Articles '.$collectionTotal, 'Total Unique Articles ' . $collectionUnique,  'Total Duplicate Articles ' . $collectionDupes);

    }

}
