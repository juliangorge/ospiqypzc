<?php

namespace API\V1\Rest\Claims;

use Psr\Container\ContainerInterface;

class ClaimsResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {;
        return new ClaimsResource(
            $container->get(ClaimsTableGateway::class),
            'id',
            ClaimsCollection::class
        );
    }
}
