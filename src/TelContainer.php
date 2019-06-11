<?php

namespace Medz\Laravel\Notifications\TencentCloudSMS;

class TelContainer
{
    /**
     * Tel list.
     * @var array
     */
    protected $list = [];

    /**
     * Has the tel container is multi tel.
     * @return bool
     */
    public function hasMulti(): bool
    {
        return count($this->list) === 1;
    }

    /**
     * get the container tel.
     */
    public function toArray(): array
    {
        if ($this->hasMulti()) {
            return current($this->list);
        }

        return $this->list;
    }

    /**
     * add a tel in the container.
     * @param string $phoneNumber
     * @param string $nationcode
     * @return $this
     */
    public function addTel(string $phoneNumber, string $nationcode = '86')
    {
        array_push($this->list, [
            'mobile' => (string) $phoneNumber,
            'nationcode' => (string) $nationcode
        ]);

        return $this;
    }
}
