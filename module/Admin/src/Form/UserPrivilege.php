<?php
namespace Admin\Form;

use Laminas\Form\Form;

class UserPrivilege extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('privileges');

        $this->add([
            'name' => 'functionality',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'functionality',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Funcionalidad'
            ],
            'options' => [
                'label' => 'Funcionalidad <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'name',
            'attributes' => [
                'id' => 'name',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Nombre de Ruta'
            ],
            'options' => [
                'label' => 'Nombre de Ruta <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'action',
            'attributes' => [
                'id' => 'action',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Acción'
            ],
            'options' => [
                'label' => 'Acción <small>(requerido)</small>',
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
                'class' => 'my-3 btn btn-block btn-primary',
                'value' => 'Guardar',
                'type' => 'submit'
            ]
        ]);

        $this->setValidationGroup([
            'functionality',
            'name',
            'action',
            'submit'
        ]);
    }
}