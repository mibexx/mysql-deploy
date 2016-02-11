<?php

    namespace Mibexx\MysqlDeploy\Db;

    /**
     * Class PdoConnection
     * @package Mibexx\MysqlDeploy
     * @subpackage Db
     */
    class PdoConnection implements ConnectionInterface
    {
        /**
         * @var ConnectionConfiguration
         */
        private $configuration;

        /**
         * @var \PDO
         */
        private $connection;

        /**
         * PdoConnection constructor.
         * @param ConnectionConfiguration $configuration
         */
        public function __construct(ConnectionConfiguration $configuration)
        {
            $this->configuration = $configuration;
        }

        /**
         * @param array $sqlList
         * @param bool $stopAtError
         * @return array
         * @throws \Exception
         */
        public function execute(array $sqlList, $stopAtError = false)
        {
            if (!$this->connect()) {
                throw new \Exception("No connection established");
            }

            $response = array();
            foreach ($sqlList as $index => $statement) {
                try {
                    $result = $this->connection->query($statement);
                    $response[$index] = array(
                        'success'   => true,
                        'result'    => $result,
                        'statement' => $statement
                    );
                }
                catch (\PDOException $e) {
                    $response[$index] = array(
                        'success'   => false,
                        'result'    => '',
                        'exception' => $e,
                        'statement' => $statement
                    );
                    if ($stopAtError) {
                        break;
                    }
                }
            }
            return $response;
        }

        /**
         * @return \PDO
         */
        public function getConnection()
        {
            return $this->connect();
        }

        /**
         * @return \PDO
         */
        private function connect()
        {
            if (!$this->connection) {
                $this->connection = new \PDO(
                    $this->getServer(),
                    $this->configuration->getUser(),
                    $this->configuration->getPass()
                );
            }
            return $this->connection;
        }

        /**
         * Get the PDO Server string
         * @return string
         */
        private function getServer()
        {
            return 'mysql:host=' . $this->configuration->getHost() . ';dbname=' . $this->configuration->getDbname();
        }
    }