<?php namespace ChaoticWave\SilentMovie\Responses;

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
        'people' => PeopleResponse::class,
    ];

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Make a response based on the result
     *
     * @param array $response
     *
     * @return PeopleResponse|TitleResponse
     */
    public static function make(array $response = [])
    {
        //  Try the map
        if (null !== ($_type = array_get($response, 'type'))) {
            if (null !== ($_class = array_get(static::$classMap, $_type))) {
                return new $_class($response);
            }
        }

        //  Try the id
        switch (substr(array_get($response, 'id'), 0, 2)) {
            case 'nm':
                return new PeopleResponse($response);

            case 'tt':
                return new TitleResponse($response);
        }

        return null;
    }
}
