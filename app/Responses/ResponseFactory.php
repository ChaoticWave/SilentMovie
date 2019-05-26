<?php namespace ChaoticWave\SilentMovie\Responses;

use Illuminate\Support\Arr;

class ResponseFactory
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var array
     */
    protected static $classMap = [
        'title'  => TitleResponse::class,
        'person' => PeopleResponse::class,
    ];

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Make a response based on the result
     *
     * @param array $response
     *
     * @return PeopleResponse|TitleResponse|array
     */
    public static function make(array $response = [])
    {
        //  Try the mapping if it's a search response
        if (null !== ($_type = Arr::get($response, 'type', Arr::get($response, 'details.endpoint')))) {
            if (null !== ($_class = Arr::get(static::$classMap, $_type))) {
                return new $_class($response);
            }
        }

        //  Try the id for individual entity responses
        switch (substr(Arr::get($response, 'id'), 0, 2)) {
            case 'nm':
                return new PeopleResponse($response);

            case 'tt':
                return new TitleResponse($response);
        }

        return null;
    }
}
