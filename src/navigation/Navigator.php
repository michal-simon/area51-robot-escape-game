<?php

namespace Robot\Navigation;

use Monolog\Logger;
use Robot\Models\RobotModel;

const MAX_DISTANCE = 5;

class Navigator
{
    private RobotModel $robotModel;
    private Logger $logger;

    public function __construct(RobotModel $robotModel, Logger $logger)
    {
        $this->robotModel = $robotModel;
        $this->logger = $logger;
    }

    public function findEscape(string $robotId): void
    {
        $this->logger->info('Robot is finding escape', ['robotId' => $robotId]);

        $this->moveToRightWall($robotId);
        $this->moveToBottomWall($robotId);
        $this->logger->info('Robot is in bottom right corner', ['robotId' => $robotId]);

        $width = $this->measureWall('left', $robotId);
        $this->logger->info('The room is ' . $width . 'm wide', ['robotId' => $robotId]);

        $height = $this->measureWall('up', $robotId);
        $this->logger->info('The room is ' . $height . 'm high', ['robotId' => $robotId]);

        $this->moveToCenter($robotId, $width, $height);

        $this->logger->info('Robot is ready to escape', ['robotId' => $robotId]);
    }

    private function moveToRightWall(string $robotId): void
    {
        $this->logger->info('Moving to right wall', ['robotId' => $robotId]);

        do {
            $moved = $this->robotModel->move($robotId, 'right', MAX_DISTANCE);
            $this->logger->debug('Moved ' . $moved . 'm right', ['robotId' => $robotId]);
        } while ($moved == MAX_DISTANCE);
    }

    private function moveToBottomWall(string $robotId): void
    {
        $this->logger->info('Moving to bottom wall', ['robotId' => $robotId]);

        do {
            $moved = $this->robotModel->move($robotId, 'down', MAX_DISTANCE);
            $this->logger->debug('Moved ' . $moved . 'm down', ['robotId' => $robotId]);
        } while ($moved == MAX_DISTANCE);
    }

    private function measureWall(string $direction, string $robotId): int
    {
        $this->logger->info('Measuring ' . $direction . ' wall', ['robotId' => $robotId]);

        $distance = 0;

        do {
            $moved = $this->robotModel->move($robotId, $direction, MAX_DISTANCE);
            $this->logger->debug('Moved ' . $moved . 'm ' . $direction, ['robotId' => $robotId]);
            $distance += $moved;
        } while ($moved == MAX_DISTANCE);

        return $distance;
    }

    private function moveToCenter(string $robotId, int $width, int $height): void
    {
        $widthCenter = floor($width / 2);
        $heightCenter = floor($height / 2);

        $this->logger->info('Moving to the center [' . $widthCenter . ',' . $heightCenter . ']', ['robotId' => $robotId]);

        for ($remainingDistance = $widthCenter; $remainingDistance > 0; $remainingDistance -= MAX_DISTANCE) {
            $moved = $this->robotModel->move($robotId, 'right', min($remainingDistance, MAX_DISTANCE));
            $this->logger->debug('Moved ' . $moved . 'm right', ['robotId' => $robotId]);
        }

        for ($remainingDistance = $heightCenter; $remainingDistance > 0; $remainingDistance -= MAX_DISTANCE) {
            $moved = $this->robotModel->move($robotId, 'down', min($remainingDistance, MAX_DISTANCE));
            $this->logger->debug('Moved ' . $moved . 'm down', ['robotId' => $robotId]);
        }
    }
}
