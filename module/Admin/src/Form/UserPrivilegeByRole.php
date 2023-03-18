<?php
namespace Admin\Form;

use Laminas\Form\Form;

class UserPrivilegeByRole extends Form
{
    public function __construct($em)
    {
        parent::__construct('user_privilege');

        $this->add([
            'name' => 'privileges',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'privileges',
                'required' => true,
                'class' => 'form-select multiselect',
                'multiple' => 'multiple'
            ],
            'options' => [
                'label' => 'Privilegios',
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
                'object_manager' => $em,
                'target_class' => 'Juliangorge\Users\Entity\UserPrivilege',
                'is_method' => true,
                'property' => 'Label',
                'find_method' => [
                    'name'   => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy'  => ['name' => 'ASC'],
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'id' => 'submit',
                'class' => 'btn btn-primary my-3',
                'value' => 'Guardar',
                'type' => 'submit'
            ]
        ]);

        $this->setValidationGroup([
            'privileges',
            'submit'
        ]);
    }
}