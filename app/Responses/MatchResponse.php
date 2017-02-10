<?php namespace ChaoticWave\SilentMovie\Responses;


class MatchResponse extends BaseApiResponse
{
    //******************************************************************************
    //* Members
    //******************************************************************************

    /**
     * @var Entity[]
     */
    protected $exact;
    /**
     * @var Entity[]
     */
    protected $popular;
    /**
     * @var Entity[]
     */
    protected $substring;
    /**
     * @var Entity[]
     */
    protected $approx;
    /**
     * @var array The top level groups
     */
    protected $mapping = ['exact', 'approx', 'substring', 'popular'];
    /**
     * @var string The property prefix in the response to remove (name_ or title_, etc)
     */
    protected $prefix;

    //******************************************************************************
    //* Methods
    //******************************************************************************

    /**
     * PeopleResponse constructor.
     *
     * @param array $response
     */
    public function __construct(array $response = [])
    {
        parent::__construct($response);

        $this->loadEntities();
    }

    /**
     * Dynamically load entities into properties based on $mapping
     */
    protected function loadEntities()
    {
        foreach ($this->response as $_key => $_value) {
            if (in_array($_prop = str_replace($this->prefix, null, $_key), $this->getMapping())) {
                $this->{$_prop} = $_hits = array_pull($this->response, $_key);

                if (!is_array($_hits) && !($_hits instanceof \Traversable)) {
                    continue;
                }

                $_entities = null;

                foreach ($_hits as $_index => $_hit) {
                    $_entity = new Entity($_hit);
                    $_entities[$_entity->getId()] = $_entity;
                }

                $this->{$_prop} = $_entities;
                unset($_entities);
            }
        }
    }

    /**
     * @return \ChaoticWave\SilentMovie\Responses\Entity[]
     */
    public function getExact()
    {
        return $this->exact;
    }

    /**
     * @return \ChaoticWave\SilentMovie\Responses\Entity[]
     */
    public function getPopular()
    {
        return $this->popular;
    }

    /**
     * @return \ChaoticWave\SilentMovie\Responses\Entity[]
     */
    public function getSubstring()
    {
        return $this->substring;
    }

    /**
     * @return \ChaoticWave\SilentMovie\Responses\Entity[]
     */
    public function getApprox()
    {
        return $this->approx;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}
