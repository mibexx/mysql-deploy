<?php

    namespace Mibexx\MysqlDeploy\FileList;

    /**
     * Get sql-files from directory
     * @package Mibexx\MysqlDeploy
     * @subpackage FileList
     */
    class DirectoryFileList implements FileListInterface
    {
        /**
         * @var active directory
         */
        private $directory;

        /**
         * DirectoryFileList constructor.
         * @param $dir
         */
        public function __construct($dir)
        {
            $this->directory = $dir;
        }

        /**
         * Get all sql-files from the given directory
         * @return array
         */
        public function getFileList()
        {
            return glob($this->directory . '/*.sql');
        }
    }