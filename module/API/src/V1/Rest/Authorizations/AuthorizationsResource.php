<?php

namespace API\V1\Rest\Authorizations;

use Laminas\ApiTools\DbConnectedResource;

class AuthorizationsResource extends DbConnectedResource
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
        unset($data['is_approved']);
        unset($data['user_id']);
        unset($data['authorization_date']);

        $this->table->insert($data);
        $id = $this->table->getLastInsertValue();
        return $this->fetch($id);
    }
}
