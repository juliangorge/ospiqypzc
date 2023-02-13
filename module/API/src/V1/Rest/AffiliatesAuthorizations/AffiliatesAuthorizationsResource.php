<?php
namespace API\V1\Rest\AffiliatesAuthorizations;

use Laminas\ApiTools\DbConnectedResource;
use Laminas\Db\TableGateway\TableGatewayInterface as TableGateway;
use Laminas\Paginator\Adapter\DbTableGateway as TableGatewayPaginator;

class AffiliatesAuthorizationsResource extends DbConnectedResource
{

    /**
     * Create a new resource.
     *
     * @param array|object $data Data representing the resource to create.
     * @return array|object Newly created resource.
     */
    public function create($data)
    {
        $data = $this->retrieveData($data);
        
        // Utilizo default value de MySQL
        unset($data['date_created']);
        unset($data['status']);
        unset($data['user_id']);
        unset($data['authorization_date']);
        $data['status'] = 0;

        $this->table->insert($data);
        $id = $this->table->getLastInsertValue();
        return $this->fetch($id);
    }

}