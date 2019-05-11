<?php

use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Journal::class, 10)->create()->each(function ($journal) {
            $journal->outputs()->saveMany(factory(App\Output::class, 100)->make());
        });
    }
}
