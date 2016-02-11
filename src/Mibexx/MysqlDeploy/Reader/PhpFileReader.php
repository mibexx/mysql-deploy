<?php

    namespace Mibexx\MysqlDeploy\Reader;

    /**
     * PHP reader for mysql files
     * @package Mibexx\MysqlDeploy
     * @subpackage Reader
     */
    class PhpFileReader implements FileReaderInterface
    {
        /**
         * PARSER: Default
         */
        const PARSER_DEFAULT = 'Mibexx\MysqlDeploy\Reader\SqlParser';

        /**
         * PART: Do
         */
        const PART_DO = 0;

        /**
         * PART: Undo
         */
        const PART_UNDO = 1;

        /**
         * SQL-File break for undo-statements
         * @var string
         */
        private $undoBreaker = '-- DEPLOYMENT/UNDO';

        /**
         * Sql parser-class
         * @var string
         */
        private $sqlParserClass;

        public function __construct($parser = self::PARSER_DEFAULT)
        {
            $this->sqlParserClass = $parser;
        }

        /**
         * @param $file
         * @return array
         * @throws \Exception
         */
        public function getSqlDo($file)
        {
            $content = $this->getContentPartFromFile($file, self::PART_DO);
            return $this->getParser($content)->getStatements();
        }

        /**
         * @param $file
         * @return array
         * @throws \Exception
         */
        public function getSqlUndo($file)
        {
            $content = $this->getContentPartFromFile($file, self::PART_UNDO);
            return $this->getParser($content)->getStatements();
        }

        /**
         * Get the sql parser
         * @param $content
         * @return ParserInterface
         */
        private function getParser($content)
        {
            return new $this->sqlParserClass($content);
        }

        /**
         * @param $file
         * @return string
         * @throws \Exception
         */
        private function getFileContent($file)
        {
            if (!file_exists($file)) {
                throw new \Exception("File not exist (" . $file . ")");
            }
            return file_get_contents($file);
        }

        /**
         * Get Content-Part from file
         * @param $file
         * @param $part
         * @throws \Exception
         * @return string
         */
        protected function getContentPartFromFile($file, $part = self::PART_DO)
        {
            $content = $this->getFileContent($file);
            if (strpos($content, $this->undoBreaker) === false) {
                throw new \Exception("UNDO-Comment not found (" . $this->undoBreaker . ")");
            }

            $contentParts = explode($this->undoBreaker, $content);
            return $contentParts[$part];
        }


    }