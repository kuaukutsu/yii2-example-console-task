<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\FileHelper;
use yii\helpers\Console;

/**
 * Class AbstractController
 * @package console\controllers
 */
abstract class AbstractController extends Controller
{
    const LEVEL_INFO = 'info';
    const LEVEL_ERROR = 'error';

    /**
     * @var bool
     */
    public $color = true;

    /**
     * @var string
     */
    public $filelog;

    /**
     * @var string
     */
    public $template = '{datetime} [{level}] {message}{eol}';

    /***********
     * BASE
     **********/

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['filelog']);
    }

    /**
     * @inheritdoc
     */
    public function optionAliases()
    {
        return array_merge(parent::optionAliases(), ['o' => 'filelog']);
    }

    /********************
     * HELPER ( LOGGER )
     *******************/

    /**
     * @param string $message
     */
    protected function info($message)
    {
        $args = func_get_args();
        array_shift($args);

        $this->writeLog($message, self::LEVEL_INFO, $args);
    }

    /**
     * @param string $message
     */
    protected function error($message)
    {
        $args = func_get_args();
        array_shift($args);

        $this->writeLog($message, self::LEVEL_ERROR, $args);
    }

    /**
     * @param string $message
     * @param string $level
     * @param array $args
     */
    private function writeLog($message, $level = self::LEVEL_INFO, array $args = [])
    {
        $data = strtr($this->template, [
            '{datetime}' => date('Y-m-d H:i:s'),
            '{level}' => strtoupper($level),
            '{message}' => $message,
            '{eol}' => PHP_EOL
        ]);

        if ($this->filelog === null) {

            if ($level === self::LEVEL_ERROR) {
                $args = [Console::FG_RED];
            }

            if (count($args) > 0) {
                $data = Console::ansiFormat($data, $args);
            }

            // std
            $level === self::LEVEL_ERROR ? $this->stderr($data) : $this->stdout($data);

        } elseif (FileHelper::createDirectory(dirname($this->filelog))) {

            file_put_contents($this->filelog, $data, FILE_APPEND);
        }
    }
}