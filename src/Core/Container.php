<?php

namespace UnionPay\Api\Core;


/**
 * Class Container
 * @package UnionPay\Api\Core
 */
class Container implements \ArrayAccess
{
    /**
     * @var array
     */
    private array $instances = array();
    /**
     * @var array
     */
    private array $values = array();

    /**
     * @param $provider
     * @return $this
     */
    public function serviceRegister($provider): Container
    {
        $provider->serviceProvider($this);
        return $this;
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (isset($this->instances[$offset])) {
            return $this->instances[$offset];
        }
        $raw = $this->values[$offset];
        $val = $this->values[$offset] = $raw($this);
        $this->instances[$offset] = $val;
        return $val;
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->values[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {

    }

    public function offsetExists($offset): bool
    {
        return !empty($this->values[$offset]);
    }
}
