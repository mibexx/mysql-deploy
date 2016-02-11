<?php

    namespace Mibexx\MysqlDeploy\Deployer;

    /**
     * @package Mibexx\MysqlDeploy
     * @subpackage Deployer
     */
    interface DeploymentInterface
    {
        /**
         * Deploy one file
         * @param $file
         */
        public function deployFile($file);

        /**
         * Undo the full deployment
         */
        public function undeploy();

        /**
         * Undeploy one file
         * @param $file
         */
        public function undeployFile($file);
    }