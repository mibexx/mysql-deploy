<?php
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

    use Mibexx\MysqlDeploy\Deployer\MysqlDeployment;
    use Mibexx\MysqlDeploy\Importer\PhpImporter;
    use Mibexx\MysqlDeploy\FileList\DirectoryFileList;
    use Mibexx\MysqlDeploy\Reader\PhpFileReader;
    use Mibexx\MysqlDeploy\Db\EmptyConnection;
    use Mibexx\MysqlDeploy\Logger\ShellLogger;

    $sqlDirectory = __DIR__ . '/sql';

    $fileReader = new PhpFileReader();
    $dbConnection = new EmptyConnection();
    $logger = new ShellLogger();

    $fileList = new DirectoryFileList($sqlDirectory);
    $deployer = new MysqlDeployment($dbConnection, $fileReader, $logger);


    $phpImporter = new PhpImporter(
        $fileList,
        $deployer
    );

    system('clear');
    $phpImporter->import();