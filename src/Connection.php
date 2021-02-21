<?php

namespace Ejetar\LaravelFirebird;

use Ejetar\LaravelFirebird\Query\Builder as QueryBuilder;
use Ejetar\LaravelFirebird\Query\Grammars\Firebird15Grammar as QueryGrammar10;
use Ejetar\LaravelFirebird\Query\Grammars\Firebird25Grammar as QueryGrammar20;
use Ejetar\LaravelFirebird\Query\Grammars\Firebird30Grammar as QueryGrammar30;
use Ejetar\LaravelFirebird\Query\Processors\FirebirdProcessor as Processor;
use Ejetar\LaravelFirebird\Schema\Builder as SchemaBuilder;
use Ejetar\LaravelFirebird\Schema\Grammars\FirebirdGrammar as SchemaGrammar;
use PDO;

class Connection extends \Illuminate\Database\Connection
{

    /**
     * Firebird Engine version
     *
     * @var string
     */
    private $engine_version = null;

    /**
     * Get engine version
     *
     * @return string
     */
    protected function getEngineVersion()
    {
        if (!$this->engine_version) {
            $this->engine_version = isset($this->config['engine_version']) ? $this->config['engine_version'] : null;
        }
        if (!$this->engine_version) {
            $sql = "SELECT RDB\$GET_CONTEXT(?, ?) FROM RDB\$DATABASE";
            $sth = $this->getPdo()->prepare($sql);
            $sth->execute(['SYSTEM', 'ENGINE_VERSION']);
            $this->engine_version = $sth->fetchColumn();
            $sth->closeCursor();
        }
        return $this->engine_version;
    }

    /**
     * Get major engine version
     * It allows you to determine the features of the engine.
     *
     * @return int
     */
    protected function getMajorEngineVersion()
    {
        $version = $this->getEngineVersion();
        $parts = explode('.', $version);
        return (int)$parts[0];
    }

    /**
     * Get the default query grammar instance
     *
     * @return QueryGrammar10|QueryGrammar20|QueryGrammar30
     */
    protected function getDefaultQueryGrammar()
    {
        switch ($this->getMajorEngineVersion()){
            case 1:
                return new QueryGrammar10;
                break;
            case 3:
                return new QueryGrammar30;
                break;
            default:
                return new QueryGrammar20;
                break;
        }
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Ejetar\LaravelFirebird\Query\Processors\FirebirdProcessor
     */
    protected function getDefaultPostProcessor()
    {
        return new Processor;
    }

    /**
     * Get a schema builder instance for this connection.
     *
     * @return \Ejetar\LaravelFirebird\Schema\Builder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new SchemaBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new SchemaGrammar);
    }

    /**
     * Get query builder
     *
     * @return \Ejetar\LaravelFirebird\Query\Builder
     */
    protected function getQueryBuilder()
    {
        $processor = $this->getPostProcessor();
        $grammar = $this->getQueryGrammar();

        return new QueryBuilder($this, $grammar, $processor);
    }

    /**
     * Get a new query builder instance.
     *
     * @return \Ejetar\LaravelFirebird\Query\Builder
     */
    public function query()
    {
        return $this->getQueryBuilder();
    }

    /**
     * Execute stored function
     *
     * @param string $function
     * @param array $values
     * @return mixed
     */
    public function executeFunction($function, array $values = null)
    {
        $query = $this->getQueryBuilder();

        return $query->executeFunction($function, $values);
    }

    /**
     * Execute stored procedure
     *
     * @param string $procedure
     * @param array $values
     */
    public function executeProcedure($procedure, array $values = null)
    {
        $query = $this->getQueryBuilder();

        $query->executeProcedure($procedure, $values);
    }

    /**
     * Start a new database transaction.
     *
     * @return void
     * @throws \Exception
     */
    public function beginTransaction()
    {
        if ($this->transactions == 0 && $this->pdo->getAttribute(PDO::ATTR_AUTOCOMMIT) == 1) {
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
        }
        parent::beginTransaction();
    }

    /**
     * Commit the active database transaction.
     *
     * @return void
     */
    public function commit()
    {
        parent::commit();
        if ($this->transactions == 0 && $this->pdo->getAttribute(PDO::ATTR_AUTOCOMMIT) == 0) {
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        }
    }

    /**
     * Rollback the active database transaction.
     *
     * @param int|null $toLevel
     * @return void
     * @throws \Exception
     */
    public function rollBack($toLevel = null)
    {
        parent::rollBack($toLevel);
        if ($this->transactions == 0 && $this->pdo->getAttribute(PDO::ATTR_AUTOCOMMIT) == 0) {
            $this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
        }
    }

}
