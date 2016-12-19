<?php namespace ChaoticWave\SilentMovie\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

/**
 * Something that looks like an API response
 */
interface ApiResponseLike extends Jsonable, Arrayable
{
}