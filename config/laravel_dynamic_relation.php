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
                    'validations'=>[
                        Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator\TableExistValidator::class,
                        Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator\OneToOneRelationValidator::class,
                        Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator\BelongsToRelationValidator::class,
                        Gianfriaur\LaravelDynamicRelation\Service\DynamicRelationValidatorService\Validator\OneToManyRelationValidator::class
                    ]
                ]
            ]
        ]
    ],

    'relation_migrators' => [ /* TODO: make default migrator*/]
];