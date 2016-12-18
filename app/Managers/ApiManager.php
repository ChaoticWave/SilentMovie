<?php namespace ChaoticWave\SilentMovie\Managers;

use ChaoticWave\BlueVelvet\Traits\HasApplication;
use ChaoticWave\SilentMovie\Contracts\MediaApiServiceLike;

class ApiManager
{
    //******************************************************************************
    //* Traits
    //******************************************************************************

    use HasApplication;

    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var MediaApiServiceLike[] array Array of APIs to use
     */
    protected $apis;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * Returns a service from a name
     *
     * @param string|null $service
     *
     * @return MediaApiServiceLike
     */
    public function resolve($service = null)
    {
        if (false === ($_config = $this->getConfig($service))) {
            throw new \InvalidArgumentException('The $service "' . $service . '" is not supported.');
        }

        return $this->apis[$service] = $this->app->make('sm.' . $_config['service'], $_config);
    }

    /**
     * Attempt to get the disk from the local cache.
     *
     * @param  string $service
     *
     * @return MediaApiServiceLike
     */
    protected function get($service)
    {
        return isset($this->apis[$service]) ? $this->apis[$service] : $this->resolve($service);
    }

    /**
     * Get an api configuration.
     *
     * @param  string $service
     *
     * @return array|bool
     */
    protected function getConfig($service)
    {
        return config('media.apis.' . $service, false);
    }

    /**
     * Get the default authentication driver name.
     *
     * @return string
     */
    public function getDefaultApi()
    {
        return config('media.default', 'imdb');
    }

    /**
     * Set the default api name.
     *
     * @param  string $service
     */
    public function setDefaultApi($service)
    {
        config(['media.default' => $service]);
    }
}
