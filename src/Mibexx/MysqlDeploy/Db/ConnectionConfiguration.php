<?php

    namespace Mibexx\MysqlDeploy\Db;

    /**
     * DB Connection Data
     * @package Mibexx\MysqlDeploy
     * @subpackage Db
     */
    class ConnectionConfiguration
    {
        /**
         * @var string
         */
        private $host;

        /**
         * @var string
         */
        private $user;

        /**
         * @var string
         */
        private $pass;

        /**
         * @var string
         */
        private $dbname;

        /**
         * ConnectionConfiguration constructor.
         * @param string $host
         * @param string $user
         * @param string $pass
         * @param string $dbname
         */
        public function __construct($host, $user, $pass, $dbname)
        {
            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->dbname = $dbname;
        }

        /**
         * @return string
         */
        public function getHost()
        {
            return $this->host;
        }

        /**
         * @param string $host
         * @return ConnectionConfiguration
         */
        public function setHost($host)
        {
            $this->host = $host;
            return $this;
        }

        /**
         * @return string
         */
        public function getUser()
        {
            return $this->user;
        }

        /**
         * @param string $user
         * @return ConnectionConfiguration
         */
        public function setUser($user)
        {
            $this->user = $user;
            return $this;
        }

        /**
         * @return string
         */
        public function getPass()
        {
            return $this->pass;
        }

        /**
         * @param string $pass
         * @return ConnectionConfiguration
         */
        public function setPass($pass)
        {
            $this->pass = $pass;
            return $this;
        }

        /**
         * @return string
         */
        public function getDbname()
        {
            return $this->dbname;
        }

        /**
         * @param string $dbname
         * @return ConnectionConfiguration
         */
        public function setDbname($dbname)
        {
            $this->dbname = $dbname;
            return $this;
        }
    }