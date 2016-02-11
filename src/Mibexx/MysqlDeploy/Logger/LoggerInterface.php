<?php

    namespace Mibexx\MysqlDeploy\Logger;


    /**
     * @package Mibexx\MysqlDeploy
     * @subpackage Logger
     */
    interface LoggerInterface
    {
        /**
         * @param $message
         * @param string $additional_info
         * @param int $deploymentId
         * @return mixed
         */
        public function log($message, $additional_info = "", $deploymentId = 0);
    }