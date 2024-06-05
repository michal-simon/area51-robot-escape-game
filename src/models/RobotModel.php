<?php

namespace Robot\Models;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class RobotModel
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private function sendRequest(string $method, string $url, array $payload = []): ResponseInterface
    {
        $options = [
            'body' => json_encode($payload),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ];

        return $this->client->request($method, $url, $options);
    }

    public function create(string $email): string
    {
        $result = $this->sendRequest('POST', 'robot/', [
            'email' => $email,
        ]);

        $body = json_decode($result->getBody());

        assert(property_exists($body, 'id'), 'Body is missing property "id".');

        return $body->id;
    }

    public function move(string $robotId, string $direction, int $distance): int
    {
        $result = $this->sendRequest('PUT', 'robot/' . $robotId . '/move', [
            'direction' => $direction,
            'distance' => $distance,
        ]);

        $body = json_decode($result->getBody());

        assert(property_exists($body, 'distance'), 'Body is missing property "distance".');

        return $body->distance;
    }

    public function escape(string $robotId, int $salary): bool
    {
        $result = $this->sendRequest('PUT', 'robot/' . $robotId . '/escape', [
            'salary' => $salary,
        ]);

        $body = json_decode($result->getBody());

        assert(property_exists($body, 'success'), 'Body is missing property "success".');

        return $body->success;
    }
}