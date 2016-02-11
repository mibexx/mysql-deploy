<?php

    namespace Mibexx\MysqlDeploy\Reader;

    /**
     * Parse the sql files
     * @package Mibexx\MysqlDeploy
     * @subpackage Reader
     */
    class SqlParser implements ParserInterface
    {
        /**
         * SQL content
         * @var string
         */
        private $content;

        /**
         * Random split sign
         * @var string
         */
        private $splitSign;

        private $sqlToken = array(
            'SELECT',
            'UPDATE',
            'DELETE',
            'REPLACE',
            'CREATE',
            'INSERT',
            'ALTER',
            'CALL',
            'DELIMITER',
            'DROP'
        );

        public function __construct($content)
        {
            $this->content = $content;
            $this->splitSign = md5(rand(111111, 999999));
        }

        /**
         * Get cleared statements from sql-content
         * @return array
         */
        public function getStatements()
        {
            $this->removeComments();
            return $this->getStatementsFromContent();
        }

        private function getStatementsFromContent()
        {
            $content = $this->content;
            foreach ($this->sqlToken as $token) {
                $content = str_replace($token, $this->splitSign . $token, $content);
            }
            $statements = array();
            foreach (array_slice(explode($this->splitSign, $content), 1) as $statement) {
                $statements[] = trim($statement);
            }
            return $statements;
        }

        /**
         * Remove all comments
         */
        private function removeComments()
        {
            $this->content = preg_replace("@\/\*(.*)\*\/@s", "", $this->content);
            $this->content = preg_replace("@\-\-(.*)$@m", "", $this->content);
        }
    }