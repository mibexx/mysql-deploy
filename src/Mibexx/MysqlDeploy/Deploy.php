<?php

    namespace Mibexx\MysqlDeploy;

    use Mibexx\MysqlDeploy\Deployer\DeploymentInterface;
    use Mibexx\MysqlDeploy\FileList\FileListInterface;
    use Mibexx\MysqlDeploy\FileList\DirectoryFileList;
    use Mibexx\MysqlDeploy\Importer\ImporterInterface;
    use Mibexx\MysqlDeploy\Importer\PhpImporter;

    /**
     * Main class for mysql deployment
     * @package Mibexx\MysqlDeploy
     */
    class Deploy
    {
        /**
         * @var ImporterInterface
         */
        private $importer;

        /**
         * @var string
         */
        private $directory;

        /**
         * @var FileListInterface
         */
        private $fileList;

        /**
         * @var DeploymentInterface
         */
        private $deployer;

        /**
         * Deploy constructor.
         * @param ImporterInterface $importer
         */
        public function __construct(ImporterInterface $importer = null)
        {
            $this->importer = $importer;
            $this->directory = '';
            $this->logging = false;
        }

        public function init()
        {
            if (!$this->directory) {
                throw new \Exception("No directory setted");
            }

            if (!$this->deployer) {
                throw new \Exception("Deployer not given");
            }

            if (!$this->fileList) {
                $this->fileList = new DirectoryFileList($this->directory);
            }

            $this->importer = new PhpImporter(
                $this->fileList,
                $this->deployer
            );
        }

        public function import()
        {
            $this->importer->import();
        }

        public function undoImport()
        {
            $this->importer->undoImport();
        }

        /**
         * @param string $directory
         * @return Deploy
         */
        public function setDirectory($directory)
        {
            $this->directory = $directory;
            return $this;
        }

        /**
         * @param FileListInterface $fileList
         * @return Deploy
         */
        public function setFileList(FileListInterface $fileList)
        {
            $this->fileList = $fileList;
            return $this;
        }

        /**
         * @param DeploymentInterface $deployer
         */
        public function setDeployer($deployer)
        {
            $this->deployer = $deployer;
        }
    }