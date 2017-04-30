<?php namespace ChaoticWave\SilentMovie\Support;

class BaseEntity
{
    /**
     * @var string The base url to retrieve this entity
     */
    protected $baseUrl;

	public function __construct($url = null)
	{
		if (null !== $url) {
			// removing trash from url
			$url = preg_replace('/\?.+$/', '', $url);
			$url = rtrim($url, '/');

			if (substr($url, 0, 1) == '/') {
				$url = $this->root() . $url;
			}
		}
	}
}