<?php

namespace Gianfriaur\LaravelDynamicRelation\Eloquent;

trait HasDynamicRelation
{
    private static ?array $dynamic_relations = null;

    public static function hasDynamicRelation($name): bool
    {
        return array_key_exists($name, static::getDynamicRelations());
    }

    public static function getDynamicRelations(): array{
        if (!static::$dynamic_relations){
            $register = app('dynamic_relation.register');
            $relations = $register->getModelRelations(static::class) ??[];
            static::$dynamic_relations = $relations;
        }
        return static::$dynamic_relations;
    }

    public function __get($name)
    {
        if (static::hasDynamicRelation($name)) {
            if ($this->relationLoaded($name)) {
                return $this->relations[$name]['callable'];
            }
            return $this->getRelationshipFromMethod($name);
        }
        return parent::__get($name);
    }

    public function __call($name, $arguments)
    {
        if (static::hasDynamicRelation($name)) {
            return call_user_func(static::getDynamicRelations()[$name]['callable'], $this);
        }
        return parent::__call($name, $arguments);
    }


}