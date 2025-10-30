<?php

namespace API\V1\Rest\Authorizations;

use Psr\Container\ContainerInterface;

class AuthorizationsResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {;
        return new AuthorizationsResource(
            $container->get(AuthorizationsTableGateway::class),
            'id',
            AuthorizationsCollection::class
        );
    }
}
