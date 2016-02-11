<?php

    namespace Mibexx\MysqlDeploy\Importer;

    use Mibexx\MysqlDeploy\Db\ConnectionInterface;
    use Mibexx\MysqlDeploy\Deployer\DeploymentInterface;
    use Mibexx\MysqlDeploy\FileList\FileListInterface;

    /**
     * Import sql-files with php
     * @package Mibexx\MysqlDeploy
     * @subpackage Importer
     */
    class PhpImporter implements ImporterInterface
    {
        /**
         * @var FileListInterface
         */
        private $fileList;

        /**
         * @var DeploymentInterface
         */
        private $deployer;

        /**
         * PhpImporter constructor.
         * @param \Mibexx\MysqlDeploy\FileList\FileListInterface $fileList
         */
        public function __construct(
            FileListInterface $fileList,
            DeploymentInterface $deployer
        ) {
            $this->fileList = $fileList;
            $this->deployer = $deployer;
        }

        /**
         * Import all files to db
         */
        public function import()
        {
            foreach ($this->fileList->getFileList() as $file) {
                $this->importFile($file);
            }
        }

        /**
         * Undo all sql-files in db
         */
        public function undoImport()
        {
            $this->deployer->undeploy();
        }

        /**
         * Import one file to db
         * @param string $file
         */
        public function importFile($file)
        {
            $this->deployer->deployFile($file);
        }

        /**
         * Undo one file in db
         * @param string $file
         */
        public function undoFile($file)
        {
            $this->deployer->undeployFile($file);
        }
    }