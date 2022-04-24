<?php
namespace Admin\Form;

use Laminas\Form\Form;

class ClinicalHistory extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('clinical_history');

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
            'name' => 'file_number',
            'attributes' => [
                'id' => 'file_number',
                'class' => 'form-control',
                'placeholder' => 'Número de Archivo'
            ],
            'options' => [
                'label' => 'Número de Archivo <small>(opcional)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'diagnose',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'diagnose',
                'class' => 'form-control',
                'placeholder' => 'Diagnóstico'
            ],
            'options' => [
                'label' => 'Diagnóstico <small>(opcional)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'observations',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'observations',
                'class' => 'form-control',
                'placeholder' => 'Observaciones'
            ],
            'options' => [
                'label' => 'Observaciones <small>(opcional)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'treatment',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'treatment',
                'class' => 'form-control',
                'placeholder' => 'Tratamiento'
            ],
            'options' => [
                'label' => 'Tratamiento <small>(opcional)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'date',
            'type' => 'Laminas\Form\Element\DateTimeLocal',
            'attributes' => [
                'id' => 'date',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Fecha'
            ],
            'options' => [
                'label' => 'Fecha <small>(requerido)</small>',
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
            #'file_number',
            #'diagnose',
            #'observations',
            #'treatment',
            'date',
            'professional_id',
            'submit'
        ]);
    }
}