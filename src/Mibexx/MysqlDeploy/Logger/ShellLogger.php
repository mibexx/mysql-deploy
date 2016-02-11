<?php

    namespace Mibexx\MysqlDeploy\Logger;

    /**
     * Shell Logger
     * @package Mibexx\MysqlDeploy
     * @subpackage Logger
     */
    class ShellLogger implements LoggerInterface
    {
        /**
         * @param $message
         * @param string $additional_info
         * @param int $deploymentId
         */
        public function log($message, $additional_info = "", $deploymentId = 0)
        {
            echo '['.date('d.m.Y H:i:s').']' . $message . PHP_EOL;
            echo '-----------------------------------------------' . PHP_EOL;
            echo $additional_info . PHP_EOL . PHP_EOL;
        }
    }