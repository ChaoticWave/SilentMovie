<?php namespace ChaoticWave\SilentMovie\Console\Commands;

use ChaoticWave\SilentMovie\Database\Models\MediaEntity;
use ChaoticWave\SilentMovie\Documents\Entity;
use ChaoticWave\SilentMovie\Facades\ImdbApi;
use ChaoticWave\SilentMovie\Responses\PeopleResponse;

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

        /** @var Entity $_entity */
        foreach ($_results->getMapping() as $_mapping) {
            $_list = $_results->{'get' . $_mapping}();

            if (!empty($_list)) {
                foreach ($_list as $_index => $_entity) {
                    try {
                        MediaEntity::upsertFromEntity($_entity, $_results instanceof PeopleResponse ? 'people' : 'title');
                    } catch (\Exception $_ex) {
                        \Log::error('Exception saving entity', $_entity->toArray());
                    }
                }
            }
        }

        $this->writeln($_results->toJson(JSON_PRETTY_PRINT));
    }
}
