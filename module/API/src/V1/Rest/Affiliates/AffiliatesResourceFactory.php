<?php

namespace API\V1\Rest\Affiliates;

use Psr\Container\ContainerInterface;

class AffiliatesResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AffiliatesResource(
            $container->get(AffiliatesTableGateway::class),
            'dni',
            AffiliatesCollection::class
        );
    }
}
