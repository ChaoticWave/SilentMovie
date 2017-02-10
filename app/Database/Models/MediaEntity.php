<?php namespace ChaoticWave\SilentMovie\Database\Models;

use ChaoticWave\SilentMovie\Database\SilentMovieModel;
use ChaoticWave\SilentMovie\Responses\Entity;
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
class MediaEntity extends SilentMovieModel
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /** @inheritdoc */
    protected $table = 'sm_entity';
    /** @inheritdoc */
    protected $casts = [
        'extra_text' => 'array',
        'source_nbr' => 'integer',
    ];

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * @param \ChaoticWave\SilentMovie\Responses\Entity $entity
     * @param string                                    $responseType
     *
     * @return MediaEntity|\Illuminate\Database\Eloquent\Model
     */
    public static function createFromEntity(Entity $entity, $responseType)
    {
        static $mapping = [
            'id'               => 'source_id_text',
            'name'             => 'name_text',
            'episodeTitle'     => 'episode_title_text',
            'title'            => 'title_text',
            'description'      => 'desc_text',
            'titleDescription' => 'title_desc_text',
            'extra'            => 'extra_text',
            'source'           => 'source_nbr',
        ];

        $_data = ['response_type_text' => $responseType];

        foreach ($mapping as $_prop => $_column) {
            $_method = 'get' . $_prop;
            if (method_exists($entity, $_method)) {
                $_data[$_column] = $entity->{$_method}();
            }
        }

        return static::query()->create($_data);
    }

    /**
     * @param \ChaoticWave\SilentMovie\Responses\Entity $entity
     * @param string                                    $responseType
     *
     * @return MediaEntity|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public static function upsertFromEntity(Entity $entity, $responseType)
    {
        try {
            /** @var MediaEntity $_model */
            if (null === ($_model = static::where('source_nbr', $entity->getSource())->where('source_id_text', $entity->getId())->first())) {
                return static::query()->create(static::mapEntity($entity, $responseType));
            }

            $_model->update(static::mapEntity($entity, $responseType));

            return $_model;
        } catch (QueryException $_ex) {
            app('log')->error('Exception creating entity: ' . $_ex->getMessage());
            throw $_ex;
        }
    }

    /**
     * @param \ChaoticWave\SilentMovie\Responses\Entity $entity
     * @param string                                    $responseType
     *
     * @return array
     */
    public static function mapEntity(Entity $entity, $responseType)
    {
        static $mapping = [
            'name'             => 'name_text',
            'title'            => 'title_text',
            'titleDescription' => 'title_desc_text',
            'episodeTitle'     => 'episode_title_text',
            'description'      => 'desc_text',
            'extra'            => 'extra_text',
        ];

        $_data = ['response_type_text' => $responseType, 'source_nbr' => $entity->getSource(), 'source_id_text' => $entity->getId()];

        foreach ($mapping as $_prop => $_column) {
            if (method_exists($entity, $_method = 'get' . $_prop)) {
                $_data[$_column] = call_user_func([$entity, $_method]);

                if (empty($_data[$_column])) {
                    $_data[$_column] = null;
                }
            }
        }

        return $_data;
    }
}