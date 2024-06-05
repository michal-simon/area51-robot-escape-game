<?php

namespace Robot\Factories;

use GuzzleHttp;
use GuzzleHttp\HandlerStack;
use GuzzleRetry\GuzzleRetryMiddleware;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientFactory
{
    public static function createInstance(Logger $logger, string $baseUri): GuzzleHttp\Client
    {
        $stack = HandlerStack::create();
        $stack->push(GuzzleRetryMiddleware::factory([
            'max_retry_attempts' => 5,
            'default_retry_multiplier' => 0, // Retry immediately because the robot has quite a short life
            'retry_on_status' => [500],
            'on_retry_callback' => function (int $attemptNumber, float $delay, RequestInterface &$request, array &$options, ?ResponseInterface $response) use ($logger) {
                $logger->warning(sprintf(
                    "Retrying request: Server responded with %s. Will wait %s seconds.",
                    $response->getStatusCode(),
                    number_format($delay, 2),
                ), [
                    'path' => $request->getUri()->getPath(),
                    'status' => $response->getStatusCode(),
                    'attemptNumber' => $attemptNumber,
                    'delay' => $delay,
                ]);
            },
        ]));

        return new GuzzleHttp\Client([
            'handler' => $stack,
            'base_uri' => $baseUri,
        ]);
    }
}