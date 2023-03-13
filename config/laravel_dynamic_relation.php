<?php
return [

    'relation_builder' => 'default',

    'relation_register' => 'default',

    'relation_validator' => config('database.default'),

    'relation_migrator' => null,

    'relation_builders' => [
        'default' => [
            'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationBuilderService\DefaultDynamicRelationBuilderServiceService::class,
            'options' => []
        ]
    ],

    'relation_registers' => [
        'default' => [
            'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationRegisterService\DefaultDynamicRelationRegisterServiceService::class,
            'options' => []
        ]
    ],

    'relation_validators' =>[
        'drivers' => [
            'sqlite' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\SqliteDynamicRelationValidatorService::class,
                'options' => []
            ],
            'mysql' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\MysqlDynamicRelationValidatorService::class,
                'options' => []
            ],
            'pgsql' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\PgsqlDynamicRelationValidatorService::class,
                'options' => []
            ],
            'sqlsrv' => [
                'class' => Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\SqlsrvDynamicRelationValidatorService::class,
                'options' => []
            ],
        ],
        'shared_options' => [
            // show a warning if the validation finds problems on the performance of the tables (ex missing foreign_key or index)
            'show_performance_warning' => true,
            // show a warning if the validation finds problems between the types of the keys
            'show_colum_type_warning' => true,

            // alert with error if the validation find a missing foreign_key
            'missing_foreign_key_is_error' => false,
        ]
    ],

    'relation_migrators' => [ /* TODO: make default migrator*/]
];