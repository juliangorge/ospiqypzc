<?php
namespace Admin\Form\Element;

use Admin\Form\Element\ObjectSelect\Proxy;
use DoctrineModule\Form\Element\ObjectSelect as DoctrineObjectSelect;

/**
 * Class ObjectSelect
 *
 * Extends doctrines ObjectSelect to provide own Proxy implementation
 *
 * @package Admin\Form\Element
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
class ObjectSelect extends DoctrineObjectSelect
{
    /**
     * Provide own Proxy implementation to fix bug that only identifier can be loaded as option value
     */
    public function init()
    {
        $this->proxy = new Proxy();
    }
}