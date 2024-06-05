<?php

namespace Robot\Controllers;

use Monolog\Logger;
use Robot\Models\RobotModel;
use Robot\Navigation\Navigator;

class EscapeController
{
    private RobotModel $robotModel;
    private Navigator $navigator;
    private Logger $logger;

    public function __construct(RobotModel $robotModel, Navigator $navigator, Logger $logger)
    {
        $this->robotModel = $robotModel;
        $this->navigator = $navigator;
        $this->logger = $logger;
    }

    public function actionEscape(string $email, int $salary): bool
    {
        $this->logger->info('Creating new robot');

        $robotId = $this->robotModel->create($email);
        $this->logger->info('Robot created', ['robotId' => $robotId]);

        $this->navigator->findEscape($robotId);

        $status = $this->robotModel->escape($robotId, $salary);

        $this->logger->info('Robot escaped', [
            'robotId' => $robotId,
            'status' => $status,
        ]);

        return $status;
    }
}