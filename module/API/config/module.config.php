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
            'api.rest.affiliates-authorizations' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v1/affiliates_authorizations[/:affiliates_authorizations_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'api.rest.affiliates-claims',
            1 => 'api.rest.affiliates-authorizations',
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
        'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller' => [
            'listener' => 'API\\V1\\Rest\\AffiliatesAuthorizations\\AffiliatesAuthorizationsResource',
            'route_name' => 'api.rest.affiliates-authorizations',
            'route_identifier_name' => 'affiliates_authorizations_id',
            'collection_name' => 'affiliates_authorizations',
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
            'entity_class' => \API\V1\Rest\AffiliatesAuthorizations\AffiliatesAuthorizationsEntity::class,
            'collection_class' => \API\V1\Rest\AffiliatesAuthorizations\AffiliatesAuthorizationsCollection::class,
            'service_name' => 'affiliates_authorizations',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'API\\V1\\Rest\\AffiliatesClaims\\Controller' => 'Json',
            'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller' => [
                0 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller' => [
                0 => 'application/json',
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
            \API\V1\Rest\AffiliatesAuthorizations\AffiliatesAuthorizationsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.affiliates-authorizations',
                'route_identifier_name' => 'affiliates_authorizations_id',
                'hydrator' => \Doctrine\Laminas\Hydrator\DoctrineObject::class,
            ],
            \API\V1\Rest\AffiliatesAuthorizations\AffiliatesAuthorizationsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.affiliates-authorizations',
                'route_identifier_name' => 'affiliates_authorizations_id',
                'is_collection' => true,
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
            'API\\V1\\Rest\\AffiliatesAuthorizations\\AffiliatesAuthorizationsResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'affiliates_authorizations',
                'hydrator_name' => \Doctrine\Laminas\Hydrator\DoctrineObject::class,
                'controller_service_name' => 'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\AffiliatesAuthorizations\\AffiliatesAuthorizationsResource\\Table',
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'API\\V1\\Rest\\AffiliatesClaims\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\AffiliatesClaims\\Validator',
        ],
        'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\AffiliatesAuthorizations\\Validator',
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
                'name' => 'title',
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
                'name' => 'detail_answer',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
                'name' => 'date_answer',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            5 => [
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
        'API\\V1\\Rest\\AffiliatesAuthorizations\\Validator' => [
            0 => [
                'name' => 'dni',
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
                            'max' => '10',
                        ],
                    ],
                ],
            ],
            1 => [
                'name' => 'user_id',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [],
            ],
            2 => [
                'name' => 'authorization_date',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'date_created',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
                'name' => 'is_approved',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [],
            ],
            5 => [
                'name' => 'type_of_authorization',
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
                            'table' => 'affiliates_authorizations',
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
            'API\\V1\\Rest\\AffiliatesAuthorizations\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
        ],
    ],
];
