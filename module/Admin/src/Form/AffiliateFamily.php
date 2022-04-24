<?php
namespace Admin\Form;

use Laminas\Form\Form;

class AffiliateFamily extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('affiliate_family');

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
            'name' => 'affiliate_dni',
            'type' => 'number',
            'attributes' => [
                'id' => 'affiliate_dni',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 10,
                'placeholder' => 'DNI Afiliado'
            ],
            'options' => [
                'label' => 'DNI Afiliado <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'type_of_family_member_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'type_of_family_member_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Tipo de Familiar <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $em,
                'target_class' => 'Admin\Entity\TypeOfFamilyMember',
                'property' => 'name',
                'is_method' => false,
                'display_empty_item' => true,
                'empty_item_label' => 'Seleccionar Tipo de Familiar',
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
            'name' => 'phone_number',
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
            'name' => 'birthday',
            'type' => 'date',
            'attributes' => [
                'id' => 'birthday',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Fecha de Nacimiento'
            ],
            'options' => [
                'label' => 'Fecha de Nacimiento <small>(requiere)</small>',
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
            'firstname',
            'lastname',
            'dni',
            'email',
            'affiliate_dni',
            'type_of_family_member_id',
            'phone_number',
            'birthday',
            'submit'
        ]);
    }
}