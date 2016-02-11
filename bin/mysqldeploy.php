<?php
    /**
     * Run MySQL Deployment
     */

    $autoloadFiles = array(
        __DIR__ . '/../vendor/autoload.php',
        __DIR__ . '/../../../autoload.php'
    );

    foreach ($autoloadFiles as $autoloadFile) {
        if (file_exists($autoloadFile)) {
            require_once $autoloadFile;
            break;
        }
    }

    function printLn($message, $type = 'mysql-deploy')
    {
        echo '[' . $type . '] ' . $message . PHP_EOL;
    }

    if ($argc != 3 && $argc != 4 && $argc != 7 && $argc != 8) {
        printLn("Wrong argument count");
        printLn("");
        printLn("Example Config-File Deploy");
        printLn("./mysqldeploy path/to/config.json do");
        printLn("");
        printLn("Example Config-File Undeploy");
        printLn("./mysqldeploy path/to/config.json undo <deployment-ID>");
        printLn("");
        printLn("Example Arguments Deploy");
        printLn("./mysqldeploy <host> <user> <pass> <dbname> do path/to/sql/files");
        printLn("");
        printLn("Example Arguments Undeploy");
        printLn("./mysqldeploy <host> <user> <pass> <dbname> undo <deployment-ID> path/to/sql/files");
        printLn("");
        printLn("");
    }

    if ($argc == 3 || $argc == 4) {
        $pathToConfig = $argv[1];
        $type = $argv[2];
        $deploymentID = 0;
        if ($argc == 4) {
            $deploymentID = $argv[3];
        }
        if (!file_exists($pathToConfig)) {
            printLn("Error: Config file doesn't exist");
            exit;
        }
        printLn("Read config file \"" . $pathToConfig . "\"");
        $config = file_get_contents($pathToConfig);
        $config = json_decode($config);
        $dbhost = $config->dbhost;
        $dbuser = $config->dbuser;
        $dbpass = $config->dbpass;
        $dbname = $config->dbname;
        $sqlpath = $config->path;
    }
    else if ($argc == 7 || $argc == 8) {
        $dbhost = $argv[1];
        $dbuser = $argv[2];
        $dbpass = $argv[3];
        $dbname = $argv[4];
        $type = $argv[5];
        if ($type == 'do') {
            $sqlpath = $argv[6];
            $deploymentID = 0;
        }
        elseif ($type == 'undo') {
            $deploymentID = $argv[6];
            $sqlpath = $argv[7];
        }
    }

    if ($type !== 'do' && $type !== 'undo') {
        printLn("ERROR: Type is not known (" . $type . ")");
        exit;
    }

    if ($type === 'undo' && !$deploymentID) {
        printLn("ERROR: No deployment id found for undeploy");
        exit;
    }

    if (
        empty($dbhost)
        || empty($dbhost)
        || empty($dbuser)
        || empty($dbname)
        || (
            empty($sqlpath)
            && $type == 'do'
        )
        || (
            empty($deploymentID)
            && $type == 'undo'
        )
    ) {
        printLn('ERROR: Not all informations given');
        printLn('Host: ' . $dbhost);
        printLn('User: ' . $dbuser);
        printLn('Name: ' . $dbname);
        printLn('Type: ' . $type);
        printLn('Path: ' . $sqlpath);
        printLn('Dpl : ' . $deploymentID);
        exit;
    }


    use Mibexx\MysqlDeploy\Db\ConnectionConfiguration;
    use Mibexx\MysqlDeploy\Db\PdoConnection;
    use Mibexx\MysqlDeploy\Deployer\MysqlDeployment;
    use Mibexx\MysqlDeploy\Reader\PhpFileReader;
    use Mibexx\MysqlDeploy\Logger\DbLogger;
    use Mibexx\MysqlDeploy\Deploy;

    try {
        $mysqlConfig = new ConnectionConfiguration($dbhost, $dbuser, $dbpass, $dbname);
        $connection = new PdoConnection($mysqlConfig);
        $reader = new PhpFileReader();
        $logger = new DbLogger($connection);

        $deployer = new MysqlDeployment($connection, $reader, $logger, $deploymentID);

        $deployment = new Deploy();
        $deployment->setDirectory($sqlpath);
        $deployment->setDeployer($deployer);
        $deployment->init();

        if ($type == 'do') {
            printLn("Start deployment");
            printLn("Path: " . $sqlpath);
            $deployment->import();
        }
        else {
            printLn("Start undeployment");
            printLn("Deployment: " . $deployer->getDeploymentId());
            $deployment->undoImport();
        }

    }
    catch (\Exception $e) {
        printLn("ERROR: " . $e->getMessage());
    }