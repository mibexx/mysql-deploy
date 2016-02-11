<?php

    namespace Mibexx\MysqlDeploy\FileList;

    /**
     * Manually filled file list
     * @package Mibexx\MysqlDeploy
     * @subpackage FileList
     */
    class ConfigFileList implements FileListInterface
    {
        private $fileList = array();

        /**
         * Get list of files
         * @return array
         */
        public function getFileList()
        {
            return $this->fileList;
        }

        /**
         * Add a new file to list
         * @param $file
         */
        public function add($file)
        {
            $this->fileList[] = $file;
            return $this;
        }
    }