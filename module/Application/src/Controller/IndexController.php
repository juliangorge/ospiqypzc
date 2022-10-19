<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\ApiTools\Admin\Module as AdminModule;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

use function class_exists;

class IndexController extends AbstractActionController
{

    public function indexAction()
    {
        return $this->redirect()->toRoute('admin');
    }
}
