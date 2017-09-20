<?php

namespace app\commands;

use Yii;
use yii\base\Action;
use yii\console\Controller;
use yii\base\Exception;
use yii\helpers\Console;
use yii\helpers\Inflector;

/**
 * Class TaskController
 * @package console\controllers
 */
class TaskController extends AbstractController
{
    /*********************
     * BASE
     ********************/

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($isValid = parent::beforeAction($action)) {

            // base run
            $this->runDependence($action);
        }

        return $isValid;
    }

    /********************************
     * ACTIONS
     *
     * added instructions in crontab
     *
     *******************************/

    /**
     * Выполняется каждые полчаса в указанное сервером время
     */
    public function actionHalfHourly()
    {
        exit();
    }

    /**
     * Выполняется ежечасно в указанное сервером время
     */
    public function actionHourly()
    {
        exit();
    }

    /**
     * Выполняется ежедневно в указанное сервером время
     */
    public function actionDaily()
    {
        exit();
    }

    /**
     * Выполняется еженедельно в указанное сервером время (last day of the week)
     */
    public function actionWeekly()
    {
        exit();
    }

    /**
     * Выполняется ежемесячно в указанное сервером время (first day of the mounth)
     */
    public function actionMonthly()
    {
        exit();
    }

    /***********************
     * BUILDER
     **********************/

    /**
     * @return array
     */
    private function listDependence()
    {
        $list = [];
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '*TaskController.php') as $filename) {
            if (preg_match('#^(\w+)TaskController\.php$#', basename($filename), $match)) {
                $list[] = Inflector::camel2id($match[1] . 'Task');
            }
        }

        return $list;
    }

    /**
     * @param Action $action
     */
    private function runDependence($action)
    {
        $this->info(sprintf('%s run', $action->getUniqueId()));

        foreach ($this->listDependence() as $controllerId) {
            $this->runDependenceAction($controllerId, $action);
        }

        $this->info(sprintf('%s done', $action->getUniqueId()));
    }

    /**
     * @param string $controllerId
     * @param Action $action
     */
    private function runDependenceAction($controllerId, $action)
    {
        /** @var \yii\console\Controller $ctrl */
        $ctrl = Yii::$app->createControllerByID($controllerId);
        $methodName = 'action' . Inflector::id2camel($action->id);
        if ($ctrl instanceof Controller && method_exists($ctrl, $methodName)) {

            $params = [];
            foreach ($this->options($action->id) as $option) {
                $params[$option] = $this->{$option};
            }

            try {
                $ctrl->runAction($action->id, $params);
                $this->info(sprintf('%s/%s done', $ctrl->getUniqueId(), $action->id), Console::BOLD);
            } catch (Exception $e) {
                $this->error($e->getMessage());
            }
        }
    }
}