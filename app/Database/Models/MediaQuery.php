<?php namespace ChaoticWave\SilentMovie\Database\Models;

use ChaoticWave\SilentMovie\Database\SilentMovieModel;

/**
 * Media queries
 *
 * @property int    $id
 * @property int    $user_id
 * @property int    $source_nbr
 * @property string $query_text
 * @property string $response_type_text
 * @property string $response_text
 * @property string $response_date
 */
class MediaQuery extends SilentMovieModel
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $table = 'sm_query';
    /** @inheritdoc */
    protected $casts = [
        'response_text' => 'array',
        'source_nbr'    => 'integer',
        'user_id'       => 'integer',
    ];
}