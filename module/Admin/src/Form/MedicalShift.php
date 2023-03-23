<?php
namespace Admin\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

use Admin\Form\Validators\MedicalCenterExistsValidator;
use Admin\Form\Validators\AffiliateOrFamilyExistsValidator;
use Admin\Form\Validators\SpecialtyByProfessionalValidator;
use Admin\Form\Validators\SpecialtyByMedicalCenterValidator;
use Admin\Form\Validators\ProfessionalCalendarValidator;

class MedicalShift extends Form
{

    protected $em;

    public function __construct($em)
    {
        parent::__construct('medical_shift');
        $this->em = $em;

        $this->addElements();

        $this->addInputFilter();
    }

    protected function addElements()
    {
        $this->add([
            'name' => 'dni',
            'attributes' => [
                'id' => 'dni',
                'required' => true,
                'class' => 'form-control',
                'minlength' => 7,
                'maxlength' => 8,
                'placeholder' => 'DNI'
            ],
            'options' => [
                'label' => 'DNI de Afiliado/Familiar <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'medical_center_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'medical_center_id',
                'required' => true,
                'class' => 'form-control',
                'onchange' => 'onChangeMedicalCenterId()'
            ],
            'options' => [
                'label' => 'Centro Médico <small>(requerido)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $this->em,
                'target_class' => 'Admin\Entity\MedicalCenter',
                'property' => 'name',
                'is_method' => false,
                'display_empty_item' => true,
                'empty_item_label' => 'Seleccionar',
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
            'name' => 'specialty_id',
            'type'  => 'select',
            'attributes' => [
                'id' => 'specialty_id',
                'required' => true,
                'class' => 'form-control',
                'onchange' => 'onChangeSpecialtyId()'
            ],
            'options' => [
                'label' => 'Especialidad <small>(requerido)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'empty_option' => 'Seleccionar',
                'disable_inarray_validator' => true,
            ],
        ]);

        $this->add([
            'name' => 'professional_id',
            'type'  => 'select',
            'attributes' => [
                'id' => 'professional_id',
                'required' => true,
                'class' => 'form-control',
                'onchange' => 'onChangeProfessionalId()'
            ],
            'options' => [
                'label' => 'Profesional <small>(requerido)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'empty_option' => 'Seleccionar',
                'disable_inarray_validator' => true,
            ],
        ]);

        $this->add([
            'name' => 'day',
            'type'  => 'text',
            'attributes' => [
                'id' => 'day',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Seleccionar',
                'onchange' => 'onChangeDay()'
            ],
            'options' => [
                'label' => 'Fecha <small>(requerido)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
        ]);

        $this->add([
            'name' => 'time',
            'type'  => 'select',
            'attributes' => [
                'id' => 'time',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Horario <small>(requerido)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'empty_option' => 'Seleccionar',
                'disable_inarray_validator' => true,
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => [
                'required' => true,
                'class' => 'btn btn-primary my-3',
                'value' => 'Guardar',
            ]
        ]);
    }

    protected function addInputFilter() 
    {
        $em = $this->em;
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name'  => 'dni',
            'required' => true,
            'filters'  => [
                ['name' => 'Digits']
            ],
            'validators' => [
                [
                    'name' => 'Digits',
                    'priority' => 1
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 7,
                        'max' => 8,
                        'message' => 'Documento no válido'
                    ],
                    'priority' => 2
                ],
                [
                    'name' => AffiliateOrFamilyExistsValidator::class,
                    'options' => [
                        'em' => $this->em,
                    ],
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'medical_center_id',
            'required' => true,
            'filters' => [
                ['name' => 'ToInt'],
            ],
            'validators' => [
                [
                    'name' => MedicalCenterExistsValidator::class,
                    'options' => [
                        'em' => $this->em,
                    ],
                ]
            ],
        ]);

        $inputFilter->add([
            'name' => 'specialty_id',
            'required' => true,
            'filters'  => [
                ['name' => 'Digits']
            ],
            'validators' => [
                [
                    'name' => SpecialtyByMedicalCenterValidator::class,
                    'options' => [
                        'em' => $this->em,
                    ],
                ]
            ],
        ]);

        $inputFilter->add([
            'name'  => 'professional_id',
            'required' => true,
            'filters'  => [
                ['name' => 'Digits']
            ],
            'validators' => [
                [
                    'name' => SpecialtyByProfessionalValidator::class,
                    'options' => [
                        'em' => $this->em,
                    ],
                ]
            ],
        ]);

        $inputFilter->add([
            'name'  => 'day',
            'required' => true,
            'filters'  => [],
            'validators' => [
                [
                    'name' => 'Date',
                    'options' => [
                        'format' => 'Y-m-d',
                        'strict' => true
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name'  => 'time',
            'required' => true,
            'filters'  => [],
            'validators' => [
                [
                    'name' => ProfessionalCalendarValidator::class,
                    'options' => [
                        'em' => $this->em,
                    ],
                ]
            ],
        ]);
    }
}