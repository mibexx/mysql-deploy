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

    use Mibexx\MysqlDeploy\Reader\PhpFileReader;

    $file = __DIR__ . '/sql/example1.sql';
    $fileReader = new PhpFileReader();

    echo "TO-DO: ";
    foreach ($fileReader->getSqlDo($file) as $index => $statement) {
        echo "Statement #" . $index . PHP_EOL;
        echo "-----------------------------------------------------------------------------" . PHP_EOL;
        echo $statement . PHP_EOL;
        echo "-----------------------------------------------------------------------------" . PHP_EOL;
        echo PHP_EOL . PHP_EOL;
    }

    echo "TO-UNDO: ";
    foreach ($fileReader->getSqlUndo($file) as $index => $statement) {
        echo "Statement #" . $index . PHP_EOL;
        echo "-----------------------------------------------------------------------------" . PHP_EOL;
        echo $statement . PHP_EOL;
        echo "-----------------------------------------------------------------------------" . PHP_EOL;
        echo PHP_EOL . PHP_EOL;
    }