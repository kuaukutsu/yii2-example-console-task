<?php

namespace app\commands;

/**
 * Class ExampleTaskController
 * @package app\commands
 */
class ExampleTaskController extends AbstractController
{
    /**
     * ежедневно
     */
    public function actionDaily()
    {
        $this->info('run action daily');
    }
}