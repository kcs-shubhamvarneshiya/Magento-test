<?php

namespace Capgemini\BloomreachThematic\Service;

use Capgemini\BloomreachThematic\Helper\Data as ModuleHelper;
use Capgemini\BloomreachThematic\Service\Thematic\Request;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Serialize\SerializerInterface;

class Thematic
{
    const HEADERS = [
        'Content-Type'    => 'application/json',
        'Connection'      => 'keep-alive',
        'Accept'          => '*/*',
        'Accept-Encoding' => 'gzip, deflate, br'
    ];

    private ClientFactory $clientFactory;
    private SerializerInterface $serializer;
    private ModuleHelper $moduleHelper;

    public function __construct(
        ClientFactory $clientFactory,
        SerializerInterface $serializer,
        ModuleHelper $moduleHelper
    ) {
        $this->clientFactory = $clientFactory;
        $this->serializer = $serializer;
        $this->moduleHelper = $moduleHelper;
    }

    /**
     * @param Request $request
     * @return array|bool|float|int|string|null
     * @throws GuzzleException
     */
    public function getThematicData(Request $request): float|int|bool|array|string|null
    {
        $client = $this->clientFactory->create([
            'config' => [
                'base_uri'        => $this->moduleHelper->getEndpointUrl(),
                'allow_redirects' => false,
                'timeout'         => 120
            ]
        ]);

        $options = [
            'headers' => self::HEADERS
        ];

        $params = $request->getParams();
        $needEncoding = $request->getNeedEncoding();
        $paramString = '?';

        foreach ($params as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $subValue) {
                    $subValue = !in_array($name, $needEncoding) ? $value : urlencode($subValue ?? '');
                    $paramString .= $name . '=' . $subValue . '&';
                }
            } else {
                $value = !in_array($name, $needEncoding) ? $value : urlencode($value ?? '');
                $paramString .= $name . '=' . $value . '&';
            }
        }

        $paramString = rtrim($paramString, '&');

        $response = $client->request('GET', $paramString , $options);

        if ($response->getStatusCode() === 200) {

            return $this->serializer->unserialize($response->getBody());
        }

        return [];
    }
}
