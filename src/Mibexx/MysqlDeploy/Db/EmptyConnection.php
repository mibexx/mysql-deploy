<?php

    namespace Mibexx\MysqlDeploy\Db;

    /**
     * Class EmptyConnection
     * @package Mibexx\MysqlDeploy
     * @subpackage Db
     */
    class EmptyConnection implements ConnectionInterface
    {
        /**
         * @param array $sqlList
         * @param bool $stopAtError
         * @return array
         */
        public function execute(array $sqlList, $stopAtError = false)
        {
            $response = array();
            foreach ($sqlList as $index => $statement) {
                $response[$index] = array(
                    'success'   => true,
                    'result'    => 'test',
                    'statement' => $statement
                );
            }
            return $response;
        }

        /**
         * @return bool
         */
        public function getConnection()
        {
            return false;
        }
    }