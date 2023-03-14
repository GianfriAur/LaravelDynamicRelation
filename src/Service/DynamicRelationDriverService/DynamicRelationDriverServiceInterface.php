<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService;

use Gianfriaur\LaravelDynamicRelation\Describer\Table\ColumnDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\ForeignKeyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\IndexDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Table\TableDescriber;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Illuminate\Foundation\Application;

interface DynamicRelationDriverServiceInterface
{
    public function __construct(Application $app, DynamicRelationRegisterServiceInterface $dynamicRelationRegisterService, array $options);

    public function getTableExist(string $name):bool;

    public function getTableDescriber(string $name):TableDescriber;

    /**
     * @param string $name
     * @return iterable<ColumnDescriber>
     */
    public function getTableColumns(string $name):iterable;

    /**
     * @param string $name
     * @return iterable<IndexDescriber>
     */
    public function getTableIndexes(string $name):iterable;

    /**
     * @param string $name
     * @return iterable<ForeignKeyDescriber>
     */
    public function getForeignKey(string $name):iterable;
}