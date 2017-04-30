<?php namespace ChaoticWave\SilentMovie\Console\Commands;

use ChaoticWave\SilentMovie\Database\Models\MediaEntity;
use ChaoticWave\SilentMovie\Facades\ImdbApi;
use ChaoticWave\SilentMovie\Responses\PeopleResponse;

class Tunnel extends SilentMovieCommand
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $signature = 'sm:tunnel {name : the name of the person}';
    /** @inheritdoc */
    protected $description = 'Dig into one person and map their existance';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function handle()
    {
        $_name = trim($this->argument('name'));

        if (empty($_name)) {
            throw new \InvalidArgumentException('The "name" argument cannot be blank.');
        }

        $_results = ImdbApi::searchPeople($_name);

        $_exact = $_results->getExact();

        if (!empty($_exact)) {
            $_choice = $this->output->choice('More than one match was found. Please choose an individual to tunnel:',
                array_values($_exact),
                0);

            if ($_choice === 0) {
                exit(1);
            }
        }

        /** @var \ChaoticWave\SilentMovie\Responses\Entity $_entity */
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
