<?php
namespace API\V1\Rest\AffiliatesAuthorizations;

use Psr\Container\ContainerInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ArraySerializable;

class AffiliatesAuthorizationsTableGatewayFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new AffiliatesAuthorizationsTableGateway(
            'affiliates_authorizations',
            $container->get('dbadapter'),
            null,
            $this->getResultSetPrototype($container)
        );
    }

    private function getResultSetPrototype(ContainerInterface $container)
    {
        $hydrators = $container->get('HydratorManager');
        $hydrator = $hydrators->get(ArraySerializable::class);
        return new HydratingResultSet($hydrator, new AffiliatesAuthorizationsEntity());
    }
}