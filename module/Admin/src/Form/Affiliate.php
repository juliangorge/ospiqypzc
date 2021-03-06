<?php
namespace Admin\Form;

use Laminas\Form\Form;

class Affiliate extends Form
{
    public function __construct($em, $name = null)
    {
        parent::__construct('affiliate');

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
            'name' => 'credential_number',
            'attributes' => [
                'id' => 'credential_number',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Credencial'
            ],
            'options' => [
                'label' => 'Credencial <small>(requiere)</small>',
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
            'name' => 'phone_number',
            'attributes' => [
                'id' => 'phone_number',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Tel??fono'
            ],
            'options' => [
                'label' => 'Tel??fono <small>(requiere)</small>',
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
            'name' => 'location',
            'attributes' => [
                'id' => 'location',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Ciudad'
            ],
            'options' => [
                'label' => 'Ciudad <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'affiliate_type',
            'type'  => 'Admin\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'affiliate_type',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Tipo de Afiliado <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'object_manager' => $em,
                'target_class' => 'Admin\Entity\AffiliateType',
                'property' => 'name',
                'find_method'     => 'findBy',
                'value'           => 'getValue',
                'value_is_method' => true,

                'empty_item_label' => 'Seleccionar Tipo de Afiliado',
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
            'name' => 'region_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'region_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Regi??n <small>(requiere)</small>',
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
                'empty_item_label' => 'Seleccionar Regi??n',
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
            'firstname',
            'lastname',
            'credential_number',
            'dni',
            'email',
            'birthday',
            'location',
            'phone_number',
            'region_id',
            'is_active',
            'affiliate_type',
            'submit'
        ]);
    }
}