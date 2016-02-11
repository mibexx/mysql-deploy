<?php
    namespace Mibexx\MysqlDeploy\Reader;


    /**
     * @package Mibexx\MysqlDeploy
     * @subpackage Reader
     */
    interface FileReaderInterface
    {
        /**
         * @param $file
         * @return array
         * @throws \Exception
         */
        public function getSqlDo($file);

        /**
         * @param $file
         * @return array
         * @throws \Exception
         */
        public function getSqlUndo($file);
    }