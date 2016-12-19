<?php namespace ChaoticWave\SilentMovie\Console\Commands;

use ChaoticWave\BlueVelvet\Traits\ConsoleHelper;

class Dump extends SilentMovieCommand
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use ConsoleHelper;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $signature = 'sm:dump {variable} {--format=}';
    /** @inheritdoc */
    protected $description = 'Dumps a variable';

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /** @inheritdoc */
    public function handle()
    {
        $_variable = $this->argument('variable');
        $_value = null;

        switch ($this->format) {
            case 'json':
                $this->writeln(json_encode($_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                break;

            default:
                $this->writeln(print_r($_value, true));
                break;
        }
    }
}
