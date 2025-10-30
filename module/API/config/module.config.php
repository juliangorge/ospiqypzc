<?php
return [
    'router' => [
        'routes' => [
            'api.rest.affiliates' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/affiliates[/:dni]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Affiliates\\Controller',
                    ],
                ],
            ],
            'api.rest.authorizations' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/authorizations[/:authorization_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Authorizations\\Controller',
                    ],
                ],
            ],
            'api.rest.complaints' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/complaints[/:complaint_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Complaints\\Controller',
                    ],
                ],
            ],
            'api.rest.refunds' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/refunds[/:refund_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Refunds\\Controller',
                    ],
                ],
            ],
            'api.rest.professional-calendar' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/professional_calendar[/:professional_calendar_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\ProfessionalCalendar\\Controller',
                    ],
                ],
            ],
            'api.rest.specialties' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/specialties[/:specialty_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Specialties\\Controller',
                    ],
                ],
            ],
            'api.rest.professionals' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/professionals[/:professional_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Professionals\\Controller',
                    ],
                ],
            ],
            'api.rest.prescriptions' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/prescriptions[/:prescription_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Prescriptions\\Controller',
                    ],
                ],
            ],
            'api.rest.medical-records' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/medical_records[/:medical_record_id]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\MedicalRecords\\Controller',
                    ],
                ],
            ],
            'api.rest.relatives' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/api/v2/relatives[/:dni]',
                    'defaults' => [
                        'controller' => 'API\\V1\\Rest\\Relatives\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'API\\V1\\Rest\\Affiliates\\Controller' => \Laminas\ApiTools\Rest\Factory\RestControllerFactory::class,
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            1 => 'api.rest.authorizations',
            0 => 'api.rest.complaints',
            2 => 'api.rest.refunds',
            5 => 'api.rest.professional-calendar',
            6 => 'api.rest.specialties',
            7 => 'api.rest.professionals',
            8 => 'api.rest.prescriptions',
            9 => 'api.rest.medical-records',
            10 => 'api.rest.affiliates',
            11 => 'api.rest.relatives',
        ],
    ],
    'api-tools-rest' => [
        'API\\V1\\Rest\\Authorizations\\Controller' => [
            'listener' => \API\V1\Rest\Authorizations\AuthorizationsResource::class,
            'route_name' => 'api.rest.authorizations',
            'route_identifier_name' => 'authorization_id',
            'collection_name' => 'authorizations',
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
            'entity_class' => \API\V1\Rest\Authorizations\AuthorizationsEntity::class,
            'collection_class' => \API\V1\Rest\Authorizations\AuthorizationsCollection::class,
            'service_name' => 'authorizations',
        ],
        'API\\V1\\Rest\\Complaints\\Controller' => [
            'listener' => 'API\\V1\\Rest\\Complaints\\ComplaintsResource',
            'route_name' => 'api.rest.complaints',
            'route_identifier_name' => 'complaint_id',
            'collection_name' => 'complaints',
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
            'entity_class' => \API\V1\Rest\Complaints\ComplaintsEntity::class,
            'collection_class' => \API\V1\Rest\Complaints\ComplaintsCollection::class,
            'service_name' => 'complaints',
        ],
        'API\\V1\\Rest\\Refunds\\Controller' => [
            'listener' => \API\V1\Rest\Refunds\RefundsResource::class,
            'route_name' => 'api.rest.refunds',
            'route_identifier_name' => 'refund_id',
            'collection_name' => 'refunds',
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
            'entity_class' => \API\V1\Rest\Refunds\RefundsEntity::class,
            'collection_class' => \API\V1\Rest\Refunds\RefundsCollection::class,
            'service_name' => 'refunds',
        ],
        'API\\V1\\Rest\\ProfessionalCalendar\\Controller' => [
            'listener' => 'API\\V1\\Rest\\ProfessionalCalendar\\ProfessionalCalendarResource',
            'route_name' => 'api.rest.professional-calendar',
            'route_identifier_name' => 'professional_calendar_id',
            'collection_name' => 'professional_calendar',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API\V1\Rest\ProfessionalCalendar\ProfessionalCalendarEntity::class,
            'collection_class' => \API\V1\Rest\ProfessionalCalendar\ProfessionalCalendarCollection::class,
            'service_name' => 'professional_calendar',
        ],
        'API\\V1\\Rest\\Specialties\\Controller' => [
            'listener' => 'API\\V1\\Rest\\Specialties\\SpecialtiesResource',
            'route_name' => 'api.rest.specialties',
            'route_identifier_name' => 'specialty_id',
            'collection_name' => 'specialties',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API\V1\Rest\Specialties\SpecialtiesEntity::class,
            'collection_class' => \API\V1\Rest\Specialties\SpecialtiesCollection::class,
            'service_name' => 'specialties',
        ],
        'API\\V1\\Rest\\Professionals\\Controller' => [
            'listener' => 'API\\V1\\Rest\\Professionals\\ProfessionalsResource',
            'route_name' => 'api.rest.professionals',
            'route_identifier_name' => 'professional_id',
            'collection_name' => 'professionals',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API\V1\Rest\Professionals\ProfessionalsEntity::class,
            'collection_class' => \API\V1\Rest\Professionals\ProfessionalsCollection::class,
            'service_name' => 'professionals',
        ],
        'API\\V1\\Rest\\Prescriptions\\Controller' => [
            'listener' => 'API\\V1\\Rest\\Prescriptions\\PrescriptionsResource',
            'route_name' => 'api.rest.prescriptions',
            'route_identifier_name' => 'prescription_id',
            'collection_name' => 'prescriptions',
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
            'entity_class' => \API\V1\Rest\Prescriptions\PrescriptionsEntity::class,
            'collection_class' => \API\V1\Rest\Prescriptions\PrescriptionsCollection::class,
            'service_name' => 'prescriptions',
        ],
        'API\\V1\\Rest\\MedicalRecords\\Controller' => [
            'listener' => 'API\\V1\\Rest\\MedicalRecords\\MedicalRecordsResource',
            'route_name' => 'api.rest.medical-records',
            'route_identifier_name' => 'medical_record_id',
            'collection_name' => 'medical_records',
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
            'entity_class' => \API\V1\Rest\MedicalRecords\MedicalRecordsEntity::class,
            'collection_class' => \API\V1\Rest\MedicalRecords\MedicalRecordsCollection::class,
            'service_name' => 'medical_records',
        ],
        'API\\V1\\Rest\\Affiliates\\Controller' => [
            'listener' => \API\V1\Rest\Affiliates\AffiliatesResource::class,
            'route_name' => 'api.rest.affiliates',
            'route_identifier_name' => 'dni',
            'collection_name' => 'affiliates',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API\V1\Rest\Affiliates\AffiliatesEntity::class,
            'collection_class' => \API\V1\Rest\Affiliates\AffiliatesCollection::class,
            'service_name' => 'affiliates',
        ],
        'API\\V1\\Rest\\Relatives\\Controller' => [
            'listener' => \API\V1\Rest\Relatives\RelativesResource::class,
            'route_name' => 'api.rest.relatives',
            'route_identifier_name' => 'dni',
            'collection_name' => 'relatives',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
            ],
            'collection_query_whitelist' => [
                0 => 'main_affiliate_dni',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API\V1\Rest\Relatives\RelativesEntity::class,
            'collection_class' => \API\V1\Rest\Relatives\RelativesCollection::class,
            'service_name' => 'relatives',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'API\\V1\\Rest\\Authorizations\\Controller' => 'Json',
            'API\\V1\\Rest\\Complaints\\Controller' => 'Json',
            'API\\V1\\Rest\\Refunds\\Controller' => 'Json',
            'API\\V1\\Rest\\ProfessionalCalendar\\Controller' => 'Json',
            'API\\V1\\Rest\\Specialties\\Controller' => 'Json',
            'API\\V1\\Rest\\Professionals\\Controller' => 'Json',
            'API\\V1\\Rest\\Prescriptions\\Controller' => 'HalJson',
            'API\\V1\\Rest\\MedicalRecords\\Controller' => 'Json',
            'API\\V1\\Rest\\Affiliates\\Controller' => 'Json',
            'API\\V1\\Rest\\Relatives\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'API\\V1\\Rest\\Authorizations\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Complaints\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Refunds\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\ProfessionalCalendar\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Specialties\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Professionals\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Prescriptions\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\MedicalRecords\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Affiliates\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Relatives\\Controller' => [
                0 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'API\\V1\\Rest\\Authorizations\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Complaints\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Refunds\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\ProfessionalCalendar\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Specialties\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Professionals\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Prescriptions\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\MedicalRecords\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Affiliates\\Controller' => [
                0 => 'application/json',
            ],
            'API\\V1\\Rest\\Relatives\\Controller' => [
                0 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \API\V1\Rest\Authorizations\AuthorizationsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.authorizations',
                'route_identifier_name' => 'authorization_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Authorizations\AuthorizationsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.authorizations',
                'route_identifier_name' => 'authorization_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\Complaints\ComplaintsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.complaints',
                'route_identifier_name' => 'complaint_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Complaints\ComplaintsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.complaints',
                'route_identifier_name' => 'complaint_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\Refunds\RefundsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.refunds',
                'route_identifier_name' => 'refund_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Refunds\RefundsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.refunds',
                'route_identifier_name' => 'refund_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\ProfessionalCalendar\ProfessionalCalendarEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.professional-calendar',
                'route_identifier_name' => 'professional_calendar_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\ProfessionalCalendar\ProfessionalCalendarCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.professional-calendar',
                'route_identifier_name' => 'professional_calendar_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\Specialties\SpecialtiesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.specialties',
                'route_identifier_name' => 'specialty_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Specialties\SpecialtiesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.specialties',
                'route_identifier_name' => 'specialty_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\Professionals\ProfessionalsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.professionals',
                'route_identifier_name' => 'professional_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Professionals\ProfessionalsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.professionals',
                'route_identifier_name' => 'professional_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\Prescriptions\PrescriptionsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.prescriptions',
                'route_identifier_name' => 'prescription_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Prescriptions\PrescriptionsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.prescriptions',
                'route_identifier_name' => 'prescription_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\MedicalRecords\MedicalRecordsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.medical-records',
                'route_identifier_name' => 'medical_record_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\MedicalRecords\MedicalRecordsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.medical-records',
                'route_identifier_name' => 'medical_record_id',
                'is_collection' => true,
            ],
            \API\V1\Rest\Affiliates\AffiliatesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.affiliates',
                'route_identifier_name' => 'dni',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Affiliates\AffiliatesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api.rest.affiliates',
                'route_identifier_name' => 'dni',
                'is_collection' => true,
            ],
            \API\V1\Rest\Relatives\RelativesEntity::class => [
                'entity_identifier_name' => 'dni',
                'route_name' => 'api.rest.relatives',
                'route_identifier_name' => 'dni',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \API\V1\Rest\Relatives\RelativesCollection::class => [
                'entity_identifier_name' => 'dni',
                'route_name' => 'api.rest.relatives',
                'route_identifier_name' => 'dni',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools' => [
        'db-connected' => [
            \API\V1\Rest\Authorizations\AuthorizationsResource::class => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'authorizations',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Authorizations\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\Authorizations\\AuthorizationsResource\\Table',
            ],
            'API\\V1\\Rest\\Complaints\\ComplaintsResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'complaints',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Complaints\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\Complaints\\ComplaintsResource\\Table',
            ],
            \API\V1\Rest\Refunds\RefundsResource::class => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'refunds',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Refunds\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\Refunds\\RefundsResource\\Table',
            ],
            'API\\V1\\Rest\\ProfessionalCalendar\\ProfessionalCalendarResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'professional_calendar',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\ProfessionalCalendar\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\ProfessionalCalendar\\ProfessionalCalendarResource\\Table',
            ],
            'API\\V1\\Rest\\Specialties\\SpecialtiesResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'specialties',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Specialties\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\Specialties\\SpecialtiesResource\\Table',
            ],
            'API\\V1\\Rest\\Professionals\\ProfessionalsResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'professionals',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Professionals\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\Professionals\\ProfessionalsResource\\Table',
            ],
            'API\\V1\\Rest\\Prescriptions\\PrescriptionsResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'prescriptions',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Prescriptions\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\Prescriptions\\PrescriptionsResource\\Table',
            ],
            'API\\V1\\Rest\\MedicalRecords\\MedicalRecordsResource' => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'medical_records',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\MedicalRecords\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'API\\V1\\Rest\\MedicalRecords\\MedicalRecordsResource\\Table',
            ],
            \API\V1\Rest\Affiliates\AffiliatesResource::class => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'affiliates',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Affiliates\\Controller',
                'entity_identifier_name' => 'dni',
                'table_service' => 'API\\V1\\Rest\\Affiliates\\AffiliatesResource\\Table',
            ],
            \API\V1\Rest\Relatives\RelativesResource::class => [
                'adapter_name' => 'dbadapter',
                'table_name' => 'relatives',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'API\\V1\\Rest\\Relatives\\Controller',
                'entity_identifier_name' => 'dni',
                'table_service' => 'API\\V1\\Rest\\Relatives\\RelativesResource\\Table',
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'API\\V1\\Rest\\Authorizations\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Authorizations\\Validator',
        ],
        'API\\V1\\Rest\\Complaints\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Complaints\\Validator',
        ],
        'API\\V1\\Rest\\Refunds\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Refunds\\Validator',
        ],
        'API\\V1\\Rest\\ProfessionalCalendar\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\ProfessionalCalendar\\Validator',
        ],
        'API\\V1\\Rest\\Specialties\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Specialties\\Validator',
        ],
        'API\\V1\\Rest\\Professionals\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Professionals\\Validator',
        ],
        'API\\V1\\Rest\\Prescriptions\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Prescriptions\\Validator',
        ],
        'API\\V1\\Rest\\MedicalRecords\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\MedicalRecords\\Validator',
        ],
        'API\\V1\\Rest\\Affiliates\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Affiliates\\Validator',
        ],
        'API\\V1\\Rest\\Relatives\\Controller' => [
            'input_filter' => 'API\\V1\\Rest\\Relatives\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'API\\V1\\Rest\\Affiliates\\Validator' => [
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
                            'table' => 'complaints',
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
        'API\\V1\\Rest\\Complaints\\Validator' => [
            0 => [
                'name' => 'complaint_id',
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
            2 => [
                'name' => 'details',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'details_answer',
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
                'name' => 'user_id',
                'required' => false,
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
            6 => [
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
            7 => [
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
                            'table' => 'complaints',
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
            8 => [
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
        ],
        'API\\V1\\Rest\\Authorizations\\Validator' => [
            0 => [
                'name' => 'authorization_id',
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
            2 => [
                'name' => 'user_id',
                'required' => false,
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
            3 => [
                'name' => 'authorization_date',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
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
            5 => [
                'name' => 'medical_order_image_url',
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
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '255',
                        ],
                    ],
                ],
            ],
            6 => [
                'name' => 'complementary_studies_image_url',
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
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '255',
                        ],
                    ],
                ],
            ],
            7 => [
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
                            'table' => 'authorizations',
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
        'API\\V1\\Rest\\Refunds\\Validator' => [
            0 => [
                'name' => 'refund_id',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbNoRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'refunds',
                            'field' => 'refund_id',
                        ],
                    ],
                ],
            ],
            1 => [
                'name' => 'amount',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            2 => [
                'name' => 'authorization_date',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'motive',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
                'name' => 'user_id',
                'required' => false,
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
            6 => [
                'name' => 'ticket_image_url',
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
            7 => [
                'name' => 'ticket_number',
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
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbNoRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'refunds',
                            'field' => 'ticket_number',
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
            8 => [
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
                            'table' => 'refunds',
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
            9 => [
                'name' => 'subsidiary',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
        ],
        'API\\V1\\Rest\\Professionals\\Validator' => [
            0 => [
                'name' => 'first_name',
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
                'name' => 'last_name',
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
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbNoRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'professionals',
                            'field' => 'dni',
                        ],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '10',
                        ],
                    ],
                ],
            ],
            3 => [
                'name' => 'type_of_medical_attention_id',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'types_of_medical_attention',
                            'field' => 'id',
                        ],
                    ],
                ],
            ],
            4 => [
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
                            'table' => 'professionals',
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
            5 => [
                'name' => 'registration',
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
                'name' => 'college',
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
            7 => [
                'name' => 'cuit',
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
            8 => [
                'name' => 'phone_number',
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
            9 => [
                'name' => 'email',
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
            10 => [
                'name' => 'is_active',
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
        ],
        'API\\V1\\Rest\\Specialties\\Validator' => [
            0 => [
                'name' => 'name',
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
                            'table' => 'specialties',
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
            2 => [
                'name' => 'allow_edoctor',
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
            3 => [
                'name' => 'interval',
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
        ],
        'API\\V1\\Rest\\ProfessionalCalendar\\Validator' => [
            0 => [
                'name' => 'professional_id',
                'required' => false,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'professionals',
                            'field' => 'id',
                        ],
                    ],
                ],
            ],
            1 => [
                'name' => 'medical_center_id',
                'required' => false,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'medical_centers',
                            'field' => 'id',
                        ],
                    ],
                ],
            ],
            2 => [
                'name' => 'starting_at',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'ending_at',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
                'name' => 'date',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
        ],
        'API\\V1\\Rest\\Prescriptions\\Validator' => [
            0 => [
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
                            'table' => 'prescriptions',
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
        'API\\V1\\Rest\\MedicalRecords\\Validator' => [
            0 => [
                'name' => 'background',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            1 => [
                'name' => 'clinic_history_id',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbNoRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'medical_records',
                            'field' => 'clinic_history_id',
                        ],
                    ],
                ],
            ],
            2 => [
                'name' => 'date',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            3 => [
                'name' => 'diagnostic',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            4 => [
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
                            'max' => '255',
                        ],
                    ],
                ],
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
                            'table' => 'medical_records',
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
            6 => [
                'name' => 'edoctor_room_id',
                'required' => false,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\Digits::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbNoRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'medical_records',
                            'field' => 'edoctor_room_id',
                        ],
                    ],
                ],
            ],
            7 => [
                'name' => 'observations',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            8 => [
                'name' => 'previous_studies',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            9 => [
                'name' => 'professional',
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
            10 => [
                'name' => 'specialty',
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
            11 => [
                'name' => 'studies',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
            12 => [
                'name' => 'treatment_indicated',
                'required' => false,
                'filters' => [],
                'validators' => [],
            ],
        ],
        'API\\V1\\Rest\\Relatives\\Validator' => [
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
                        'name' => 'Laminas\\ApiTools\\ContentValidation\\Validator\\DbNoRecordExists',
                        'options' => [
                            'adapter' => 'dbadapter',
                            'table' => 'relatives',
                            'field' => 'dni',
                        ],
                    ],
                    1 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '10',
                        ],
                    ],
                ],
            ],
            1 => [
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
                            'table' => 'relatives',
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
    'service_manager' => [
        'factories' => [
            \API\V1\Rest\Affiliates\AffiliatesResource::class => \API\V1\Rest\Affiliates\AffiliatesResourceFactory::class,
            \API\V1\Rest\Affiliates\AffiliatesTableGateway::class => \API\V1\Rest\Affiliates\AffiliatesTableGatewayFactory::class,
            \API\V1\Rest\Relatives\RelativesResource::class => \API\V1\Rest\Relatives\RelativesResourceFactory::class,
            \API\V1\Rest\Relatives\RelativesTableGateway::class => \API\V1\Rest\Relatives\RelativesTableGatewayFactory::class,
            \API\V1\Rest\Authorizations\AuthorizationsResource::class => \API\V1\Rest\Authorizations\AuthorizationsResourceFactory::class,
            \API\V1\Rest\Authorizations\AuthorizationsTableGateway::class => \API\V1\Rest\Authorizations\AuthorizationsTableGatewayFactory::class,
            'API\\V1\\Rest\\Complaints\\ComplaintsResource' => 'API\\V1\\Rest\\Complaints\\ComplaintsResourceFactory',
            'API\\V1\\Rest\\Complaints\\ComplaintsTableGateway' => 'API\\V1\\Rest\\Complaints\\ComplaintsTableGatewayFactory',
            \API\V1\Rest\Refunds\RefundsResource::class => \API\V1\Rest\Refunds\RefundsResourceFactory::class,
            \API\V1\Rest\Refunds\RefundsTableGateway::class => \API\V1\Rest\Refunds\RefundsTableGatewayFactory::class,
        ],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [
            'API\\V1\\Rest\\Affiliates\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => true,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => true,
                    'DELETE' => false,
                ],
            ],
            'API\\V1\\Rest\\Authorizations\\Controller' => [
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
            'API\\V1\\Rest\\Complaints\\Controller' => [
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
            'API\\V1\\Rest\\Refunds\\Controller' => [
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
            'API\\V1\\Rest\\Specialties\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
            ],
            'API\\V1\\Rest\\Professionals\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => true,
                    'DELETE' => true,
                ],
            ],
            'API\\V1\\Rest\\ProfessionalCalendar\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
            'API\\V1\\Rest\\Prescriptions\\Controller' => [
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
            'API\\V1\\Rest\\MedicalRecords\\Controller' => [
                'collection' => [
                    'GET' => false,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => false,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
            'API\\V1\\Rest\\Relatives\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => true,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => true,
                    'DELETE' => false,
                ],
            ],
        ],
    ],
];
