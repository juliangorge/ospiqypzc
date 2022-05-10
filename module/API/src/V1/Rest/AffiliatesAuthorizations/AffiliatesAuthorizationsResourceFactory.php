<?php
namespace API\V1\Rest\AffiliatesAuthorizations;

use Psr\Container\ContainerInterface;

class AffiliatesAuthorizationsResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {;
        return new AffiliatesAuthorizationsResource(
            $container->get(AffiliatesAuthorizationsTableGateway::class),
            'id',
            AffiliatesAuthorizationsCollection::class
        );
    }
}