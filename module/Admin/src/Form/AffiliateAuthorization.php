<?php
namespace Admin\Form;

use Laminas\Form\Form;

class AffiliateAuthorization extends Form
{
    public function __construct($em, $name = null, $readonly = false)
    {
        parent::__construct('affiliate-authorization');

        $this->add([
            'name' => 'affiliate_fullname',
            'attributes' => [
                'id' => 'affiliate_fullname',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Afiliado',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Afiliado',
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
            'name' => 'authorization_administrative',
            'attributes' => [
                'id' => 'authorization_administrative',
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
            'name' => 'is_approved',
            'type' => \Laminas\Form\Element\Radio::class,
            'attributes' => [
                'id' => 'is_approved',
                'required' => true
            ],
            'options' => [
                'label' => 'Autorización',
                'label_attributes' => [
                    'class' => 'col-sm-12'
                ],
                'value_options' => [
                    '0' => 'NO Autorizar',
                    '1' => 'Autorizar',
                ],
                'value_options_attributes' => [
                    'class' => "btn btn-outline-primary"
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
            'affiliate_fullname',
            'type_of_authorization',
            'authorization_administrative',
            'is_approved',
            'submit'
        ]);
    }
}