<?php
namespace Admin\Form;

use Laminas\Form\Form;

class Professional extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('professionals');

        $this->add([
            'name' => 'first_name',
            'attributes' => [
                'id' => 'first_name',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
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
            'name' => 'last_name',
            'attributes' => [
                'id' => 'last_name',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Apellido'
            ],
            'options' => [
                'label' => 'Apellido <small>(requerido)</small>',
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
                'label' => 'DNI <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'specialties',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'specialties',
                'required' => true,
                'class' => 'select2 form-control select2-multiple',
                'multiple' => 'multiple'
            ],
            'options' => [
                'label' => 'Especialidad <small>(requerido)</small>',
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
            'name' => 'registration',
            'attributes' => [
                'id' => 'registration',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Matrícula'
            ],
            'options' => [
                'label' => 'Matrícula <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'college',
            'attributes' => [
                'id' => 'college',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Universidad'
            ],
            'options' => [
                'label' => 'Universidad <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'cuit',
            'attributes' => [
                'id' => 'cuit',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'CUIT'
            ],
            'options' => [
                'label' => 'CUIT <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'phone_number',
            'type' => 'tel',
            'attributes' => [
                'id' => 'phone_number',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Teléfono'
            ],
            'options' => [
                'label' => 'Teléfono <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'email',
            'type' => 'email',
            'attributes' => [
                'id' => 'email',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Email'
            ],
            'options' => [
                'label' => 'Email <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'type'  => 'select',
            'name' => 'is_active',
            'attributes' => [
                'id' => 'is_active',
                'required' => true,
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Estado',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'value_options' => [
                    1 => 'Activo',
                    0 => 'Desactivado',
                ]
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
            'first_name',
            'last_name',
            'dni',
            'specialties',
            'registration',
            'college',
            'cuit',
            'phone_number',
            'email',
            'is_active',
            'submit'
        ]);
    }
}