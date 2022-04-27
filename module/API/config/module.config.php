<?php
return [
    'router' => [
        'routes' => [
            'api.rest.affiliates-claims' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v1/affiliates_claims[/:affiliates_claims_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\AffiliatesClaims\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'api.rest.affiliates-claims',
        ],
    ],
    'api-tools-rest' => [
        'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
            'listener' => 'API\\V1\\Rest\\AffiliatesClaims\\AffiliatesClaimsResource',
            'route_name' => 'api.rest.affiliates-claims',
            'route_identifier_name' => 'affiliates_claims_id',
            'collection_name' => 'affiliates_claims',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API\V1\Rest\AffiliatesClaims\AffiliatesClaimsEntity::class,
            'collection_class' => \API\V1\Rest\AffiliatesClaims\AffiliatesClaimsCollection::class,
            'service_name' => 'affiliates_claims',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'API\\V1\\Rest\\AffiliatesClaims\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
                0 => 'application/vnd.api.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \API\V1\Rest\AffiliatesClaims\AffiliatesClaimsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.affiliates-claims',
                'route_identifier_name' => 'affiliates_claims_id',
                'hydrator' => \Doctrine\Laminas\Hydrator\DoctrineObject::class,
            ],
            \API\V1\Rest\AffiliatesClaims\AffiliatesClaimsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.affiliates-claims',
                'route_identifier_name' => 'affiliates_claims_id',
                'is_collection' => true,
            ],
            \Admin\Entity\AffiliateClaim::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.affiliates-claims',
                'route_identifier_name' => 'affiliates_claims_id',
                'hydrator' => \Doctrine\Laminas\Hydrator\DoctrineObject::class,
            ],
        ],
    ],
    'api-tools' => [
        'db-connected' => [
            'API\\V1\\Rest\\AffiliatesClaims\\AffiliatesClaimsResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'affiliates_claims',
                'hydrator_name' => \Doctrine\Laminas\Hydrator\DoctrineObject::class,
                'controller_service_name' => 'API\\V1\\Rest\\AffiliatesClaims\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\AffiliatesClaims\\AffiliatesClaimsResource\\Table',
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\AffiliatesClaims\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'API\\V1\\Rest\\AffiliatesClaims\\Validator' => [
            0 => [
                'name' => 'claim_id',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '255',
                        ],
                    ],
                ],
            ],
            1 => [
                'name' => 'detail',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '255',
                        ],
                    ],
                ],
            ],
            2 => [
                'name' => 'sector',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '255',
                        ],
                    ],
                ],
            ],
            3 => [
                'name' => 'type',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '255',
                        ],
                    ],
                ],
            ],
            4 => [
                'name' => 'detail_answer',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            5 => [
                'name' => 'date_answer',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            6 => [
                'name' => 'document_id',
                'required' => false,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbNoRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'affiliates_claims',
                            'field' => 'document_id',
                        ],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '255',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [
            'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => false,
                ],
            ],
        ],
    ],
];
