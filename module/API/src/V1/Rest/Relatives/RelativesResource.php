<?php

namespace API\V1\Rest\Relatives;

use Laminas\ApiTools\DbConnectedResource;

class RelativesResource extends DbConnectedResource
{
    public function fetchAll($data = [])
    {
        return new RelativesCollection($this->table->getRelatives($data));
    }
}
