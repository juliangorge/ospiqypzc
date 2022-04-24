<?php
namespace Admin\Form;

use Laminas\Form\Form;

class AffiliatePrescription extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('affiliate_prescription');

        $this->add([
            'name' => 'dni',
            'type' => 'number',
            'attributes' => [
                'id' => 'dni',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 11,
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
            'name' => 'age',
            'type' => 'number',
            'attributes' => [
                'id' => 'age',
                'required' => true,
                'class' => 'form-control',
                'min' => 0,
                'placeholder' => 'Edad'
            ],
            'options' => [
                'label' => 'Edad <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'appointment_date',
            'type' => 'date',
            'attributes' => [
                'id' => 'appointment_date',
                'required' => true,
                'class' => 'form-control',
            ],
            'options' => [
                'label' => 'Fecha de Cita <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'fullname',
            'attributes' => [
                'id' => 'fullname',
                'required' => true,
                'class' => 'form-control-plaintext',
                'maxlength' => 100,
                'placeholder' => 'Nombre'
            ],
            'options' => [
                'label' => 'Nombre',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'professional_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'professional_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Profesional <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $em,
                'target_class' => 'Admin\Entity\Professional',
                'is_method' => true,
                'find_method' => [
                    'name' => 'getUsername',
                ],
                'display_empty_item' => true,
                'empty_item_label' => 'Seleccionar Profesional',
                'find_method' => [
                    'name'   => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy'  => ['firstname' => 'ASC'],
                    ],
                ],
            ],
        ]);

        $this->add([
            'name' => 'gender_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'gender_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Género <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $em,
                'target_class' => 'Admin\Entity\Gender',
                'property' => 'name',
                'is_method' => false,
                'display_empty_item' => true,
                'empty_item_label' => 'Seleccionar Género',
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
            'name' => 'first_medication',
            'attributes' => [
                'id' => 'first_medication',
                'required' => true,
                'class' => 'form-control medication',
                'placeholder' => 'Seleccionar 1º Medicación'
            ],
            'options' => [
                'label' => 'Seleccionar 1º Medicación',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
            ],
        ]);

        $this->add([
            'name' => 'second_medication',
            'attributes' => [
                'id' => 'second_medication',
                'required' => false,
                'class' => 'form-control medication',
                'placeholder' => 'Seleccionar 2º Medicación'
            ],
            'options' => [
                'label' => 'Seleccionar 2º Medicación',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
            ],
        ]);

        $this->add([
            'name' => 'third_medication',
            'attributes' => [
                'id' => 'third_medication',
                'required' => false,
                'class' => 'form-control medication',
                'placeholder' => 'Seleccionar 3º Medicación'
            ],
            'options' => [
                'label' => 'Seleccionar 3º Medicación',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
            ],
        ]);

        $this->add([
            'name' => 'region_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'region_id',
            ]
        ]);

        $this->add([
            'name' => 'first_medication_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'first_medication_id',
            ]
        ]);

        $this->add([
            'name' => 'second_medication_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'second_medication_id',
            ]
        ]);

        $this->add([
            'name' => 'third_medication_id',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'third_medication_id',
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
            'dni',
            'fullname',
            'appointment_date',
            'age',
            'professional_id',
            'gender_id',
            'first_medication',
            'region_id',
            'submit'
        ]);
    }
}