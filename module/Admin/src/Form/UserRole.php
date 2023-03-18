<?php
namespace Admin\Form;

use Laminas\Form\Form;

class UserRole extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('user_role');

        $this->add([
            'name' => 'name',
            'attributes' => [
                'id' => 'name',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 120,
                'placeholder' => 'Nombre'
            ],
            'options' => [
                'label' => 'Nombre <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
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
            'name',
            'submit'
        ]);
    }
}