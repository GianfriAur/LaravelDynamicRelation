<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService;

use Gianfriaur\LaravelDynamicRelation\Describer\Table\TableDescriber;
use Gianfriaur\LaravelDynamicRelation\Exception\NotImplementedYetException;
use Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DynamicRelationRegisterServiceInterface;
use Illuminate\Foundation\Application;

readonly class SqliteDynamicRelationDriverService implements DynamicRelationDriverServiceInterface
{
    public function __construct(protected Application $app, protected DynamicRelationRegisterServiceInterface $dynamicRelationRegisterService, protected array $options)
    {
    }

    public function getTableExist(string $name): bool
    {
        throw new NotImplementedYetException();
    }

    public function getTableDescriber(string $name): TableDescriber
    {
        throw new NotImplementedYetException();
    }

    public function getTableColumns(string $name): iterable
    {
        throw new NotImplementedYetException();
    }

    public function getTableIndexes(string $name): iterable
    {
        throw new NotImplementedYetException();
    }

    public function getForeignKey(string $name): iterable
    {
        throw new NotImplementedYetException();
    }
}