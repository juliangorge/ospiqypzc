<?php
namespace Admin\Form;

use Laminas\Form\Form;

class Professional extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('professional');

        $this->add([
            'name' => 'firstname',
            'attributes' => [
                'id' => 'firstname',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Nombre'
            ],
            'options' => [
                'label' => 'Nombre <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'lastname',
            'attributes' => [
                'id' => 'lastname',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Apellido'
            ],
            'options' => [
                'label' => 'Apellido <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'dni',
            'type' => 'number',
            'attributes' => [
                'id' => 'dni',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 10,
                'placeholder' => 'DNI'
            ],
            'options' => [
                'label' => 'DNI <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'specialty_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'specialty_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Especialidad <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $em,
                'target_class' => 'Admin\Entity\Specialty',
                'property' => 'name',
                'is_method' => false,
                'display_empty_item' => true,
                'empty_item_label' => 'Seleccionar Especialidad',
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
            'name' => 'type_of_attention_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'type_of_attention_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Tipo de Atención <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $em,
                'target_class' => 'Admin\Entity\TypeOfAttention',
                'property' => 'name',
                'is_method' => false,
                'display_empty_item' => true,
                'empty_item_label' => 'Seleccionar Tipo de Atención',
                'find_method' => [
                    'name'   => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy'  => ['id' => 'ASC'],
                    ],
                ],
            ],
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
            'firstname',
            'lastname',
            'dni',
            'specialty_id',
            'type_of_attention_id',
            'submit'
        ]);
    }
}