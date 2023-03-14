<?php

namespace Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService;

use Gianfriaur\LaravelDynamicRelation\Describer\Relation\BelongsToDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\ManyToManyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\OneToManyDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\OneToOneDescriber;
use Gianfriaur\LaravelDynamicRelation\Describer\Relation\RelationDescriberInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Application;

readonly class DefaultDynamicRelationBuilderServiceService implements DynamicRelationBuilderServiceInterface
{

    public function __construct(protected Application $app, protected array $options)
    {
    }

    public function generateOwnerRelation(
        $associated_class,
        RelationDescriberInterface $describer,
    ): callable
    {
        return match (true) {
            $describer instanceof OneToOneDescriber =>
            function (Model $model) use ($associated_class, $describer): HasOne {
                return $model->hasOne(
                    $associated_class,
                    $describer->foreignKey,
                    $describer->localKey
                );
            },
            $describer instanceof BelongsToDescriber =>
            function (Model $model) use ($associated_class, $describer): BelongsTo {
                return $model->belongsTo(
                    $associated_class,
                    $describer->foreignKey,
                    $describer->owner_key
                );
            },
            $describer instanceof OneToManyDescriber =>
            function (Model $model) use ($associated_class, $describer): HasMany {
                return $model->hasMany(
                    $associated_class,
                    $describer->foreignKey,
                    $describer->localKey
                );
            },
            $describer instanceof ManyToManyDescriber =>
            function (Model $model) use ($associated_class, $describer): BelongsToMany {
                return $model->belongsToMany(
                    $associated_class,
                    $describer->table,
                    $describer->foreignPivotKey,
                    $describer->relatedPivotKey,
                    $describer->parentKey,
                    $describer->relatedKey,
                    $describer->relation
                );
            },
            default => throw new \Exception(get_class($describer) . ' not supported yet')
        };
    }
}