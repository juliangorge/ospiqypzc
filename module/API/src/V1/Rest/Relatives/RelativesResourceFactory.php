<?php

namespace API\V1\Rest\Relatives;

use Psr\Container\ContainerInterface;

class RelativesResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RelativesResource(
            $container->get(RelativesTableGateway::class),
            'dni',
            RelativesCollection::class
        );
    }
}
