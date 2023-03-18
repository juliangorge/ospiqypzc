<?php
namespace Admin\Form;

use Laminas\Form\Form;

class AffiliatesClaims extends Form
{
    public function __construct($em, $name = null, $readonly = false)
    {
        parent::__construct('affiliates-claims');

        $this->add([
            'name' => 'claim_id',
            'attributes' => [
                'id' => 'claim_id',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Reclamo',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Reclamo',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

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
            'name' => 'title',
            'attributes' => [
                'id' => 'title',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 100,
                'placeholder' => 'Título',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Título',
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
                'placeholder' => 'DNI',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'DNI',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'details',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'details',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Detalles',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Detalles',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        
        $this->add([
            'name' => 'details_answer',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'details_answer',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Respuesta',
                'readonly' => $readonly
            ],
            'options' => [
                'label' => 'Respuesta <small>(requerido)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
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
            'claim_id',
            'affiliate_fullname',
            'title',
            'dni',
            'details',
            'details_answer',
            'user_id',
            'submit'
        ]);
    }
}