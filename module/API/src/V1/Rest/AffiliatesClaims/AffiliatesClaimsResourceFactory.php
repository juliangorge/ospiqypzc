<?php
namespace API\V1\Rest\AffiliatesClaims;

use Psr\Container\ContainerInterface;

class AffiliatesClaimsResourceFactory
{
    public function __invoke(ContainerInterface $container)
    {;
        return new AffiliatesClaimsResource(
            $container->get(AffiliatesClaimsTableGateway::class),
            'id',
            AffiliatesClaimsCollection::class
        );
    }
}