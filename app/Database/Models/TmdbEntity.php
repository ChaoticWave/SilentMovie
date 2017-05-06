<?php namespace ChaoticWave\SilentMovie\Database\Models;

use ChaoticWave\SilentMovie\Database\SilentMovieModel;
use Illuminate\Database\QueryException;

/**
 * Media folks
 *
 * @property int    $id
 * @property string $source_id_text
 * @property int    $source_nbr
 * @property string $name_text
 * @property string $desc_text
 * @property string $episode_title_text
 * @property string $title_text
 * @property string $title_desc_text
 * @property string $extra_text
 * @property string $mapping_text
 */
class TmdbEntity extends SilentMovieModel
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $table = 'tmdb_entity';
    /** @inheritdoc */
    protected $casts = [
        'known_for'   => 'array',
        'source_nbr'  => 'integer',
        'adult'       => 'boolean',
        'response'    => 'array',
        'ingested_at' => 'datetime',
    ];

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param array $data TMDB API single person result
     *
     * @return \ChaoticWave\SilentMovie\Database\Models\MediaEntity|\Illuminate\Database\Eloquent\Model
     */
    public static function upsert($data)
    {
        $_sourceId = null;
        $_fill = ['response' => $data];

        foreach ($data as $_key => $_value) {
            switch ($_key) {
                //  Ignored data-points
                case 'popularity':
                    break;

                /** @noinspection PhpMissingBreakStatementInspection */
                //  We need to move the ID to the tmdb_id column
                case 'id':
                    $_sourceId = $_value;
                    $_key = 'tmdb_id';

                default:
                    $_fill[$_key] = $_value;
                    break;
            }
        }

        try {
            $_model = static::firstOrCreate(['tmdb_id' => $_sourceId ?: 0]);
            $_model->update($_fill);

            return $_model;
        } catch (QueryException $_ex) {
            app('log')->error('Exception creating entity: ' . $_ex->getMessage());
            throw $_ex;
        }
    }
}