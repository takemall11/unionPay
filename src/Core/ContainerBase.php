<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace UnionPay\Api\Core;

/**
 * Class ContainerBase.
 */
class ContainerBase extends Container
{
    public string $mchId = '';
    public string $appId = '';
    public string $userId = '';
    public string $appKey = '';
    public string $publicKey = '';
    public string $privateKey = '';
    public string $serverPublicKey = '';
    public string $service = '';
    public array $baseParams = [];
    protected array $provider = [];

    /**
     * ContainerBase constructor.
     */
    public function __construct(array $params = [])
    {
        if (! empty($params)) {
            $this->baseParams = array_merge($this->baseParams, $params);
        }

        $providerCallback = function ($provider) {
            $obj = new $provider();
            $this->serviceRegister($obj);
        };

        array_walk($this->provider, $providerCallback); // 注册
    }

    /**
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * @return ContainerBase
     */
    public function setMchId(string $mchId): static
    {
        $this->mchId = $mchId;
        return $this;
    }

    /**
     * @return ContainerBase
     */
    public function setAppId(string $appId): static
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @return ContainerBase
     */
    public function setUserId(string $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return ContainerBase
     */
    public function setAppKey(string $appKey): static
    {
        $this->appKey = $appKey;
        return $this;
    }

    /**
     * @param string $appKey
     * @return ContainerBase
     */
    public function setPublicKey(string $appKey): static
    {
        $this->publicKey = $appKey;
        return $this;
    }

    /**
     * @param string $appKey
     * @return ContainerBase
     */
    public function setPrivateKey(string $appKey): static
    {
        $this->privateKey = $appKey;
        return $this;
    }

    /**
     * @param string $appKey
     * @return ContainerBase
     */
    public function setServerPublicKey(string $appKey): static
    {
        $this->serverPublicKey = $appKey;
        return $this;
    }
}
