<?php

namespace Medz\Laravel\Notifications\TencentCloudSMS;

class ParamsContainer
{
    /**
     * Params.
     * @var array.
     */
    protected $params = [];

    /**
     * Create the params container.
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->params = array_values($params);
    }

    /**
     * Add a item to params.
     * @var mixed $item
     * @return $this
     */
    public function add($item)
    {
        array_push($this->params, $item);

        return $this;
    }

    /**
     * Get the params.
     * @return array
     */
    public function toArray(): array
    {
        return $this->params;
    }
}