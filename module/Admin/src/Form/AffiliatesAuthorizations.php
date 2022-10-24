<?php
namespace Admin\Form;

use Laminas\Form\Form;

class AffiliatesAuthorizations extends Form
{
    public function __construct($em, $name = null, $readonly = false)
    {
        parent::__construct('affiliates-authorizations');

        $this->add([
            'name' => 'affiliate_fullname',
            'attributes' => [
                'id' => 'affiliate_fullname',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Afiliado/Familiar',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Afiliado/Familiar',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'type_of_authorization',
            'attributes' => [
                'id' => 'type_of_authorization',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Tipo de Autorización',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Tipo de Autorización',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'administrative_name',
            'attributes' => [
                'id' => 'administrative_name',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Responsable',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Responsable',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'status',
            'type' => 'radio',
            'attributes' => [
                #'id' => 'status',
                'required' => true,
                'class' => 'form-check-input'
            ],
            'options' => [
                'label' => 'Autorización',
                'label_attributes' => [
                    'class' => 'col-sm-12 mb-1'
                ],
                'value_options' => [
                    '0' => 'NO Autorizar',
                    '1' => 'Autorizar',
                    '2' => 'Comunicarse con Obra Social'
                ],
            ]
        ]);

        $this->add([
            'name' => 'user_id',
            'type' => 'hidden'
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
            'affiliate_fullname',
            'type_of_authorization',
            'administrative_name',
            'status',
            'user_id',
            'submit'
        ]);
    }
}