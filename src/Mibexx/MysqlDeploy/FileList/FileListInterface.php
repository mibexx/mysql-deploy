<?php

    namespace Mibexx\MysqlDeploy\FileList;

    interface FileListInterface
    {
        /**
         * Get the list of sql-files
         * @return array
         */
        public function getFileList();
    }