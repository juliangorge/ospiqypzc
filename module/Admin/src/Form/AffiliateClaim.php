<?php
namespace Admin\Form;

use Laminas\Form\Form;

class AffiliateClaim extends Form
{
    public function __construct($em, $name = null, $readonly = false)
    {
        parent::__construct('affiliate-claim');

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
            'name' => 'detail',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'detail',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Detalle',
                'readonly' => 'readonly'
            ],
            'options' => [
                'label' => 'Detalle',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        
        $this->add([
            'name' => 'detail_answer',
            'type' => 'textarea',
            'attributes' => [
                'id' => 'detail_answer',
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Respuesta',
                'readonly' => $readonly
            ],
            'options' => [
                'label' => 'Respuesta <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'date_answer',
            'type' => 'hidden'
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
            'detail',
            'detail_answer',
            'date_answer',
            'user_id',
            'submit'
        ]);
    }
}