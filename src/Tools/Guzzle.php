<?php

declare(strict_types=1);

namespace UnionPay\Api\Tools;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Hyperf\Codec\Json;
use Hyperf\Guzzle\CoroutineHandler;
use Psr\Http\Message\ResponseInterface;
use UnionPay\Api\Constants\UnionErrorCode;
use UnionPay\Api\Exception\PayException;

class Guzzle
{
    private Client $client;

    protected array $headers = [
        'Content-Type' => 'application/json;charset=UTF-8',
    ];

    /**
     * @param array $options
     * @return $this
     */
    public function setHttpHandle(array $options = []): static
    {
        $options['handler'] = HandlerStack::create(new CoroutineHandler());

        $options['headers'] = array_merge($this->headers, $options['headers']);

        $this->client = new Client($options);

        return $this;
    }

    /**
     * @throws GuzzleException
     */
    public function sendGet(string $url, array $params): array
    {
        $result = $this->client->get($url, ['query' => $params]);

        return $this->getResult($result);
    }

    /**
     * @param string $url
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function sendPost(string $url, array $params): array
    {
        logger()->info('UnionPay POST', ['url' => $url, 'params' => $params]);

        $result = $this->client->post($url, ['json' => $params]);

        return $this->getResult($result);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    private function getResult(ResponseInterface $response): array
    {
        $result = $response->getBody()->getContents();

        logger()->info('UnionPay RESULT', Json::decode($result));

        $result = Json::decode($result);

        if (!is_array($result)) {
            throw new PayException(UnionErrorCode::SERVER_ERROR, 'UnionPay response error');
        }

        return $result;
    }

}
