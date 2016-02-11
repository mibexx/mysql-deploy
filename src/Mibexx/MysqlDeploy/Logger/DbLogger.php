<?php

    namespace Mibexx\MysqlDeploy\Logger;

    use Mibexx\MysqlDeploy\Db\ConnectionInterface;

    /**
     * DB Logger
     * @package Mibexx\MysqlDeploy
     * @subpackage Logger
     */
    class DbLogger implements LoggerInterface
    {
        /**
         * @var ConnectionInterface
         */
        private $connection;

        /**
         * DbLogger constructor.
         * @param ConnectionInterface $connection
         */
        public function __construct(ConnectionInterface $connection)
        {
            $this->connection = $connection;
        }

        /**
         * @param $message
         * @param string $additional_info
         * @param int $deploymentId
         * @throws \Exception
         */
        public function log($message, $additional_info = "", $deploymentId = 0)
        {
            if ($this->connection->getConnection()) {
                $stmt = $this->connection->getConnection()->prepare(
                    "INSERT INTO mbx_deployments_logs (deploymentId, message, additional_informations) " .
                    "VALUES(:deploymentId, :message, :additional)"
                );
                $stmt->execute(
                    array(
                        ':deploymentId' => $deploymentId,
                        ':message'      => $message,
                        ':additional'   => $additional_info
                    )
                );
            } else {
                throw new \Exception("No DB Connection found");
            }
        }
    }