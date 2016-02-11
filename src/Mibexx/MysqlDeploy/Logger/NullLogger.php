<?php

    namespace Mibexx\MysqlDeploy\Logger;

    /**
     * Null Logger
     * @package Mibexx\MysqlDeploy
     * @subpackage Logger
     */
    class NullLogger implements LoggerInterface
    {
        /**
         * @param $message
         * @param string $additional_info
         * @param int $deploymentId
         */
        public function log($message, $additional_info = "", $deploymentId = 0)
        {

        }
    }