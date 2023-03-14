<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService;

use Gianfriaur\LaravelDynamicRelation\Describer\Table\ColumnDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\ForeignKeyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\IndexDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\TableDescriber;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MysqlDynamicRelationDriverService implements DynamicRelationDriverServiceInterface
{
    private ?array $tables = null;
    private ?string $current_database = null;

    public function __construct(protected Application $app, protected DynamicRelationRegisterServiceInterface $dynamicRelationRegisterService, protected array $options)
    {
    }

    private function getCurrentDatabaseName():string{
        if ($this->current_database === null) {
        $result = (new Collection(DB::select("SELECT DATABASE() AS database_name;")))->map(fn($row) => get_object_vars($row));
            $this->current_database = $result->toArray()[0]['database_name'];
        }
        return $this->current_database;
    }

    /**
     * Get List of all table in database
     *
     * @return array<array{name:string,type:string}>
     */
    private function getListOfTables(): array
    {
        if ($this->tables === null) {
            $result = (new Collection(DB::select('SHOW FULL TABLES;')))->map(fn($row) => get_object_vars($row));
            $result = $result->map(fn($row) => ['name' => array_values($row)[0], 'type' => $row ['Table_type']]);
            $this->tables = $result->toArray();
        }
        return $this->tables;
    }

    /**
     * check if table exist
     *
     * @param string $name
     * @return bool
     */
    public function getTableExist(string $name): bool
    {
        $tables = $this->getListOfTables();
        $match = array_filter($tables, fn($row) => $row['name'] === $name && $row['type'] === 'BASE TABLE');
        return sizeof($match) > 0;
    }

    public function getTableDescriber(string $name): TableDescriber
    {
        $exist = $this->getTableExist($name);

        $columns = [];
        $indexes = [];
        $foreignKeys = [];

        if ($exist) {
            $columns = $this->getTableColumns($name);
            $indexes = $this->getTableIndexes($name);
            $foreignKeys = $this->getForeignKey($name);
        }

        return new TableDescriber($exist, $name, $columns, $indexes, $foreignKeys);
    }

    /**
     * @param string $name
     * @return iterable<ColumnDescriber>
     */
    public function getTableColumns(string $name): iterable
    {
        $result = (new Collection(DB::select("SHOW COLUMNS FROM $name;")))->map(fn($row) => get_object_vars($row));
        $result = $result->map(fn($row) => new ColumnDescriber(
            $row['Field'],
            $row['Type'],
            $row['Null'] !== "NO",
            $row['Key'] !== "" ? $row['Key'] : null,
            $row['Default'] !== "" ? $row['Default'] : null,
            $row['Extra'] !== "" ? $row['Extra'] : null,
        ));
       return $result->toArray();
    }

    /**
     * @param string $name
     * @return iterable<IndexDescriber>
     */
    public function getTableIndexes(string $name): iterable
    {
        $result = (new Collection(DB::select("SHOW INDEXES FROM $name;")))->map(fn($row) => get_object_vars($row));

        $result = $result->map(fn($row) => new IndexDescriber(
            $row['Column_name'],
            $row['Key_name'],
            $row['Key_name'] === "PRIMARY",
            $row['Cardinality'] ?? null,
            $row['Index_type']
        ));
        return $result->toArray();
    }

    /**
     * @param string $name
     * @return iterable<ForeignKeyDescriber>
     */
    public function getForeignKey(string $name): iterable
    {
        $database_name = $this->getCurrentDatabaseName();
        $result = (new Collection(DB::select("SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = '$database_name' AND TABLE_NAME = '$name'")))->map(fn($row) => get_object_vars($row));

        $result = $result->map(fn($row) => new ForeignKeyDescriber(
            $row['CONSTRAINT_NAME'],
            $row['COLUMN_NAME'],
            $row['REFERENCED_TABLE_NAME'] ,
            $row['REFERENCED_COLUMN_NAME']
        ));
        return $result->toArray();
    }
}