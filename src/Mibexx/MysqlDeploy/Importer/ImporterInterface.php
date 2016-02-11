<?php

    namespace Mibexx\MysqlDeploy\Importer;

    /**
     * @package Mibexx\MysqlDeploy
     * @subpackage Importer
     */
    interface ImporterInterface
    {
        /**
         * Import all files to db
         */
        public function import();

        /**
         * Import one file to db
         * @param string $file
         */
        public function importFile($file);

        /**
         * Undo all sql-files in db
         * @param $deploymentID
         */
        public function undoImport();

        /**
         * Undo one file in db
         * @param string $file
         */
        public function undoFile($file);
    }