<?php

namespace Capgemini\BloomreachThematic\Service\Thematic;

class Request
{
    private array $params;
    private array $needEncoding;

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function addParam(string $name, mixed $value): void
    {
        $this->params[$name] = $value;
    }


    public function addToNeedEncoding(string $paramName): void
    {
        $this->needEncoding[] = $paramName;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getNeedEncoding(): array
    {
        return $this->needEncoding;
    }
}
