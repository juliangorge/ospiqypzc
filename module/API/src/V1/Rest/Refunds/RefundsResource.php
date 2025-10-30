<?php

namespace API\V1\Rest\Refunds;

use Laminas\ApiTools\DbConnectedResource;

class RefundsResource extends DbConnectedResource
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
        $data['authorization_date'] = NULL;
        $this->table->insert($data);
        $id = $this->table->getLastInsertValue();
        return $this->fetch($id);
    }
}
