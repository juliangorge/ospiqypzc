<?php
namespace Admin\Form;

use Laminas\Form\Form;

class Vademecum extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('vademecum');

        $this->add([
            'name' => 'drug',
            'attributes' => [
                'id' => 'drug',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Droga'
            ],
            'options' => [
                'label' => 'Droga <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'presentation',
            'attributes' => [
                'id' => 'presentation',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Presentación'
            ],
            'options' => [
                'label' => 'Presentación <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'region_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'region_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Región <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $em,
                'target_class' => 'Admin\Entity\Region',
                'property' => 'name',
                'is_method' => false,
                'display_empty_item' => true,
                'empty_item_label' => 'Seleccionar Región',
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
                'class' => 'my-3 btn btn-block btn-primary',
                'value' => 'Guardar',
                'type' => 'submit'
            ]
        ]);

        $this->setValidationGroup([
            'drug',
            'presentation',
            'region_id',
            'submit'
        ]);
    }
}