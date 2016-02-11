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

    use Mibexx\MysqlDeploy\Reader\SqlParser;

    $content = file_get_contents(__DIR__ . '/sql/example1.sql');
    $sqlParser = new SqlParser($content);

    foreach ($sqlParser->getStatements() as $index => $statement) {
        echo "Statement #" . $index . PHP_EOL;
        echo "-----------------------------------------------------------------------------" . PHP_EOL;
        echo $statement . PHP_EOL;
        echo "-----------------------------------------------------------------------------" . PHP_EOL;
        echo PHP_EOL . PHP_EOL;
    }