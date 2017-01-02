<?php namespace ChaoticWave\SilentMovie\Documents;

use ChaoticWave\SilentMovie\Contracts\DocumentLike;

class DocumentFactory
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var array
     */
    protected static $classMap = [
        TitleDocument::DOCUMENT_TYPE  => TitleDocument::class,
        PersonDocument::DOCUMENT_TYPE => PersonDocument::class,
    ];

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Make a response based on the result
     *
     * @param array $response
     *
     * @return PersonDocument|TitleDocument
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
                return new PersonDocument($response);

            case 'tt':
                return new TitleDocument($response);
        }

        return null;
    }
}