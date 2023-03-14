<?php
return [

    'relation_builder' => 'default',

    'relation_register' => 'default',

    'relation_driver' => config('database.default'),

    'relation_validator' => 'default',

    'relation_migrator' => null,

    'relation_builders' => [
        'default' => [
            'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService\DefaultDynamicRelationBuilderServiceService::class,
            'options' => []
        ]
    ],

    'relation_registers' => [
        'default' => [
            'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DefaultDynamicRelationRegisterService::class,
            'options' => []
        ]
    ],

    'relation_validators' =>[
        'drivers' => [
            'sqlite' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService\SqliteDynamicRelationDriverService::class,
                'options' => []
            ],
            'mysql' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService\MysqlDynamicRelationDriverService::class,
                'options' => []
            ],
            'pgsql' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService\PgsqlDynamicRelationDriverService::class,
                'options' => []
            ],
            'sqlsrv' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationDriverService\SqlsrvDynamicRelationDriverService::class,
                'options' => []
            ],
        ],
        'shared_drivers_options' => [],
        'providers'=>[
            'default' => [
                'class'=> Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\DefaultDynamicRelationValidatorService::class,
                'options' => [
                    // show a warning if the validation finds problems on the performance of the tables (ex missing foreign_key or index)
                    'show_performance_warning' => true,
                    // show a warning if the validation finds problems between the types of the keys
                    'show_colum_type_warning' => true,

                    // show a warning if the validation finds a missing inverse relation
                    'show_inverse_relation_missing_warning' => true,

                    // alert with error if the validation find a missing foreign_key
                    'missing_foreign_key_is_error' => false,
                ]
            ]
        ]
    ],

    'relation_migrators' => [ /* TODO: make default migrator*/]
];