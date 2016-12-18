<?php namespace ChaoticWave\SilentMovie\Contracts;

/**
 * Something that searches media APIs
 */
interface SearchesMediaApis extends MediaApiServiceLike
{
    /**
     * Find titles that contain $text
     *
     * @param string $text    The text to search for
     * @param array  $options Options for the call
     *
     * @return ApiResponseLike
     */
    public function searchTitle($text, $options = array());

    /**
     * Find people that contain $text
     *
     * @param string $text    The text to search for
     * @param array  $options Options for the call
     *
     * @return ApiResponseLike
     */
    public function searchPeople($text, $options = array());
}