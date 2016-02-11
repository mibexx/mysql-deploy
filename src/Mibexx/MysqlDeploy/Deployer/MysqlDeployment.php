<?php

    namespace Mibexx\MysqlDeploy\Deployer;

    use Mibexx\MysqlDeploy\Db\ConnectionInterface;
    use Mibexx\MysqlDeploy\Logger\LoggerInterface;
    use Mibexx\MysqlDeploy\Reader\FileReaderInterface;


    /**
     * Class MysqlDeployment
     * @package Mibexx\MysqlDeploy
     * @subpackage Deployer
     */
    class MysqlDeployment implements DeploymentInterface
    {
        /**
         * @var ConnectionInterface
         */
        private $connection;

        /**
         * @var LoggerInterface
         */
        private $logger;

        /**
         * @var FileReaderInterface
         */
        private $fileReader;

        /**
         * @var array
         */
        private $deploymentData = array();

        /**
         * Required tables in the database
         * @var array
         */
        private $requiredTables = array(
            'mbx_deployments',
            'mbx_deployments_files',
            'mbx_deployments_logs'
        );

        /**
         * MysqlDeployment constructor.
         * @param ConnectionInterface $connection
         */
        public function __construct(
            ConnectionInterface $connection,
            FileReaderInterface $fileReader,
            LoggerInterface $logger,
            $deploymentId = 0
        ) {
            $this->connection = $connection;
            $this->fileReader = $fileReader;
            $this->logger = $logger;

            $this->loadDeploymentData($deploymentId);
            $this->checkRequirements();
        }

        /**
         * Deploy one file
         * @param $file
         */
        public function deployFile($file)
        {
            if (!$this->isFileAlreadyDone($file)) {
                $this->logger->log("[DEPLOY] Import file", $file, $this->getDeploymentId());
                $this->sendQueryListToConnection($file, $this->fileReader->getSqlDo($file));
            } else {
                $this->logger->log("[DEPLOY] File already imported", $file, $this->getDeploymentId());
            }
        }

        /**
         * Undeploy one file
         * @param $file
         */
        public function undeployFile($file)
        {
            if ($this->isFileAlreadyDone($file)) {
                $this->logger->log("[UNDEPLOY] Undeploy file", $file, $this->getDeploymentId());
                $this->sendQueryListToConnection($file, $this->fileReader->getSqlUndo($file), true);
            } else {
                $this->logger->log("[UNDEPLOY] File never deployed", $file, $this->getDeploymentId());
            }
        }

        /**
         * Undo the full deployment
         * @param integer $deploymentID
         */
        public function undeploy()
        {
            if ($this->connection->getConnection()) {
                $stmt = $this->connection->getConnection()->prepare(
                    "SELECT * FROM mbx_deployments_files WHERE deploymentId = :deploymentId"
                );
                $stmt->bindParam('deploymentId', $this->getDeploymentId());
                $stmt->execute();
                while($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $this->undeployFile($row['sqlfile']);
                }
            }
        }

        /**
         * @param int $deploymentID
         */
        public function loadDeploymentData($deploymentID = 0)
        {
            $this->deploymentData = array();
            if ($this->connection->getConnection()) {
                if ($deploymentID == 0) {
                    $deploymentID = $this->getNewDeploymentData();
                }
                $this->deploymentData = $this->getDeploymentDataFromId($deploymentID);
            }
        }

        /**
         * @return integer
         */
        public function getDeploymentId()
        {
            return $this->deploymentData['ID'];
        }

        /**
         * @throws \Exception
         */
        private function checkRequirements()
        {
            if ($this->connection->getConnection()) {
                $this->checkVersionTables();
            }
            $this->checkDeploymentData();
        }

        /**
         * @throws \Exception
         */
        private function checkVersionTables()
        {
            $allTables = $this->getMbxTableList();
            if (!$allTables) {
                throw new \Exception("Tables not found");
            }

            foreach ($this->requiredTables as $table) {
                if (!in_array($table, $allTables)) {
                    throw new \Exception("Table not found: " . $table);
                }
            }
        }

        /**
         * @param array $statementList
         */
        private function sendQueryListToConnection($file, $statementList, $unDeploy = false)
        {
            try {
                $result = $this->connection->execute($statementList);
                $success = $this->checkAndSendToLogger($result);
                if ($unDeploy) {
                    $this->removeFileFromVersionTable($file);
                } else {
                    $this->writeFileToVersionTable($file, $success, count($result));
                }
            }
            catch (\Exception $e) {
                $this->logger->log($e->getMessage(), '', $this->getDeploymentId());
            }
        }

        /**
         * @param $responseList
         * @return int
         */
        private function checkAndSendToLogger($responseList)
        {
            $success = 0;
            foreach ($responseList as $response) {
                if ($response['success']) {
                    $this->logger->log('[SUCCESS] Statement executed', $response['statement'], $this->getDeploymentId());
                    $success++;
                }
                else {
                    $this->logger->log(
                        '[ERROR] Statement failed',
                        $response['statement'] . PHP_EOL . PHP_EOL . '[ERROR] Error-Message'
                        . PHP_EOL . $response['exception']->getMessage(),
                        $this->getDeploymentId()
                    );
                }
            }
            return $success;
        }

        private function getMbxTableList($filter = "%")
        {
            $stmt = $this->connection->getConnection()->query("SHOW TABLES LIKE '" . $filter . "'");
            $tableList = array();
            while ($table = $stmt->fetch(\PDO::FETCH_NUM)) {
                $tableList[] = $table[0];
            }
            return $tableList;
        }

        /**
         * @param $deploymentID
         * @return mixed
         * @throws \Exception
         */
        private function getDeploymentDataFromId($deploymentID)
        {
            $stmt = $this->connection->getConnection()->prepare(
                "SELECT * FROM mbx_deployments WHERE ID = ?"
            );
            $stmt->execute(
                array(
                    $deploymentID
                )
            );

            if ($stmt->rowCount() == 0) {
                throw new \Exception("Deployment not found (#".$deploymentID.")");
            }

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        /**
         * @return integer
         */
        private function getNewDeploymentData()
        {
            $stmt = $this->connection->getConnection()->prepare(
                "INSERT INTO mbx_deployments (timest) VALUES(:timest)"
            );

            $stmt->bindParam(':timest', date('Y-m-d H:i:s'));
            $stmt->execute();

            $deploymentID = $this->connection->getConnection()->lastInsertId();
            return $deploymentID;
        }

        /**
         * @throws \Exception
         */
        private function checkDeploymentData()
        {
            if (empty($this->deploymentData)) {
                throw new \Exception("No deployment data found");
            }
        }

        /**
         * @param $file
         */
        private function writeFileToVersionTable($file, $doneStatementCount, $allStatementCount)
        {
            if ($this->connection->getConnection()) {
                $stmt = $this->connection->getConnection()->prepare(
                    "INSERT INTO mbx_deployments_files (deploymentId, sqlfile, statements_done, statements_all) " .
                    "VALUES(:deploymentId, :sqlFile, :statements_done, :statements_all)"
                );

                $stmt->bindParam(':deploymentId', $this->deploymentData['ID']);
                $stmt->bindParam(':sqlFile', $file);
                $stmt->bindParam(':statements_done', $doneStatementCount);
                $stmt->bindParam(':statements_all', $allStatementCount);
                $stmt->execute();
            }
        }

        /**
         * @param $file
         * @return bool
         */
        private function isFileAlreadyDone($file)
        {
            $fileAlreadyDone = false;
            if ($this->connection->getConnection()) {
                $stmt = $this->connection->getConnection()->prepare(
                    "SELECT * FROM mbx_deployments_files WHERE sqlfile = :sqlfile"
                );
                $stmt->bindParam(':sqlfile', $file);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $fileAlreadyDone = true;
                    return $fileAlreadyDone;
                }
                return $fileAlreadyDone;
            }
            return $fileAlreadyDone;
        }

        /**
         * @param $file
         */
        private function removeFileFromVersionTable($file)
        {
            if ($this->connection->getConnection()) {
                $stmt = $this->connection->getConnection()->prepare(
                    "DELETE FROM mbx_deployments_files WHERE deploymentId = :deployId AND sqlfile = :sqlFile"
                );
                $stmt->bindParam(':deployId', $this->getDeploymentId());
                $stmt->bindParam(':sqlFile', $file);
                $stmt->execute();
            }
        }
    }