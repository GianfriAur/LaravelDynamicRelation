<?php
return [

    'relation_builder' => 'default',

    'relation_register' => 'default',

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

    'relation_migrators' => [ /* TODO: make default migrator*/]
];