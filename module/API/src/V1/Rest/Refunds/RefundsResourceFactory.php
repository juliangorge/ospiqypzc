<?php

namespace API\V1\Rest\Refunds;

use Psr\Container\ContainerInterface;

class RefundsResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RefundsResource(
            $container->get(RefundsTableGateway::class),
            'id',
            RefundsCollection::class
        );
    }
}
