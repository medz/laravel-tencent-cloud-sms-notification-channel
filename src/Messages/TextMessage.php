<?php

namespace Medz\Laravel\Notifications\TencentCloudSMS\Messages;

use Medz\Laravel\Notifications\TencentCloudSMS\TelContainer;
use Medz\Laravel\Notifications\TencentCloudSMS\ParamsContainer;

class TextMessage
{
    /**
     * Set message
     * @var array
     */
    protected $message = [];

    /**
     * Create the message.
     * @param int $templateId
     * @param string $sign
     */
    public function __construct(int $templateId, string $sign = '')
    {
        $this->message['tpl_id'] = $templateId;
        $this->message['sign'] = $sign;
    }

    /**
     * set `ext`.
     * @param string $ext
     * @return $this
     */
    public function setExt(string $ext)
    {
        $this->message['ext'] = $ext;

        return $this;
    }

    /**
     * set `extend`
     * @param string $extend
     * @return $this
     */
    public function setExtend(string $extend)
    {
        $this->message['extend'] = $extend;

        return $this;
    }

    /**
     * set `params`
     * @param \Medz\Laravel\Notifications\TencentCloudSMS\ParamsContainer $params
     * @return $this
     */
    public function setParams(ParamsContainer $params)
    {
        $this->message['params'] = $params;

        return $this;
    }

    /**
     * set `sig`
     * @param string $sig
     * @return $this
     */
    public function setSig(string $sig)
    {
        $this->message['sig'] = $sig;

        return $this;
    }

    /**
     * set `tel`
     * @param \Medz\Laravel\Notifications\TencentCloudSMS\TelContainer $tel
     * @return $this
     */
    public function setTel(TelContainer $tel)
    {
        $this->message['tel'] = $tel;

        return $this;
    }

    /**
     * get tel.
     * @return null|Medz\Laravel\Notifications\TencentCloudSMS\TelContainer
     */
    public function getTel(): ?TelContainer
    {
        return $this->message['tel'] ?? null;
    }

    /**
     * get params.
     * @return null|\Medz\Laravel\Notifications\TencentCloudSMS\ParamsContainer
     */
    public function getParams(): ?ParamsContainer
    {
        return $this->message['params'] ?? null;
    }

    /**
     * The message to array.
     * @return array
     */
    public function toArray(): array
    {
        $message = $this->message;

        // if tel is TelContainer.
        if ($this->getTel() instanceof TelContainer) {
            $message['tel'] = $this->getTel()->toArray();
        }

        // if params is ParamsContainer.
        if ($this->getParams() instanceof ParamsContainer) {
            $message['params'] = $this->getParams()->toArray();
        }

        return $message;
    }

    /**
     * The message json string.
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
