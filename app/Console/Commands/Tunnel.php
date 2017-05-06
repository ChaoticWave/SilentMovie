<?php namespace ChaoticWave\SilentMovie\Console\Commands;

use ChaoticWave\SilentMovie\Database\Models\TmdbEntity;
use Tmdb\Laravel\Facades\Tmdb;

class Tunnel extends SilentMovieCommand
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $signature = 'sm:tunnel {name : the name of the person}';
    /** @inheritdoc */
    protected $description = 'Dig into one person and map their existence';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function handle()
    {
        $_choice = 0;
        $_name = trim($this->argument('name'));

        if (empty($_name)) {
            throw new \InvalidArgumentException('The "name" argument cannot be blank.');
        }

        /** @noinspection PhpUndefinedMethodInspection */
        $_response = Tmdb::getSearchApi()->searchPersons($_name);

        if (empty($_response['results']) || count($_response['results']) > 1) {
            $_names = [0 => 'Quit'];

            foreach ($_response['results'] as $_index => $_item) {
                $_names[] = ($_index + 1) . ' - ' . array_get($_item, 'name');
            }

            $_choice = $this->output->choice('More than one match was found. Please choose a single individual:' . PHP_EOL, implode(', ', $_names), 0);

            if (0 === $_choice) {
                exit(1);
            }
        }

        $_results = $_response['results'][$_choice];

        TmdbEntity::upsert($_results);

        $this->writeln(json_encode($_results, JSON_PRETTY_PRINT));
    }
}
