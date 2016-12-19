<?php namespace ChaoticWave\SilentMovie\Console\Commands;

use ChaoticWave\SilentMovie\Facades\ImdbApi;

class Lookup extends SilentMovieCommand
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $signature = 'sm:lookup {term : a search term} {--title : search for "term" in titles instead of people} {--format= : the output format}';
    /** @inheritdoc */
    protected $description = 'Looks up data';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function handle()
    {
        $_term = trim($this->argument('term'));

        if (empty($_term)) {
            throw new \InvalidArgumentException('The "term" argument cannot be blank.');
        }

        $_results = $this->option('title') ? ImdbApi::searchTitle($_term) : ImdbApi::searchPeople($_term);

        $this->writeln($_results->toJson(JSON_PRETTY_PRINT));
    }
}
