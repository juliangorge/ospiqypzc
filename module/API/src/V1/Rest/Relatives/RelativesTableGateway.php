<?php

namespace API\V1\Rest\Relatives;

use Laminas\Db\TableGateway\TableGateway;
use Laminas\Paginator\Adapter\DbSelect;

class RelativesTableGateway extends TableGateway
{
    public function getRelatives($data = [])
    {
        $select = $this->getSql()->select();
        if (sizeof($data)) $select->where($data->toArray());

        return new DbSelect($select, $this->getAdapter(), $this->getResultSetPrototype());
    }
}
