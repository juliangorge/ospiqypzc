<?php
namespace Admin\Form;

use Laminas\Form\Form;

class Professionals extends Form
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
            'name' => 'last_name',
            'attributes' => [
                'id' => 'last_name',
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
                'target_class' => 'Juliangorge\OsycTools\Entity\Specialties',
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
            'name' => 'type_of_medical_attention_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'type_of_medical_attention_id',
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
                'target_class' => 'Juliangorge\OsycTools\Entity\TypesOfMedicalAttention',
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
            'name' => 'registration',
            'attributes' => [
                'id' => 'registration',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Matrícula'
            ],
            'options' => [
                'label' => 'Matrícula <small>(requiere)</small>',
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
                'label' => 'Universidad <small>(requiere)</small>',
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
                'label' => 'CUIT <small>(requiere)</small>',
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
                'label' => 'Teléfono <small>(requiere)</small>',
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
                'label' => 'Email <small>(requiere)</small>',
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
            'specialty_id',
            'type_of_medical_attention_id',
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