<?php

    namespace Mibexx\MysqlDeploy\Reader;

    /**
     * @package Mibexx\MysqlDeploy
     * @subpackage Reader
     */
    interface ParserInterface
    {
        /**
         * Get cleared statements from sql-content
         * @return array
         */
        public function getStatements();
    }