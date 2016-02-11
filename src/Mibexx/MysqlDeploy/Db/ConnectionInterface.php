<?php

    namespace Mibexx\MysqlDeploy\Db;


    /**
     * @package Mibexx\MysqlDeploy
     * @subpackage Db
     */
    interface ConnectionInterface
    {
        /**
         * @param array $sqlList
         * @param bool $stopAtError
         * @return array
         * @throws \Exception
         */
        public function execute(array $sqlList, $stopAtError = false);

        /**
         * @return mixed
         */
        public function getConnection();
    }