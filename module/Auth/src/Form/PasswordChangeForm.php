<?php
namespace Auth\Form;

use Laminas\Form\Form;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilter;

class PasswordChangeForm extends Form
{   
    private $scenario;
    
    public function __construct($scenario)
    {
        parent::__construct('password-change');
     
        $this->scenario = $scenario;
        
        $this->setAttribute('method', 'post');
        
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements() 
    {
        if ($this->scenario == 'change') {
            $this->add([
                'type'  => 'password',
                'name' => 'old_password',
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Contraseña actual',
                    'required' => true,
                    'autofocus' => true,
                    'minlength' => 6,
                    'maxlength' => 64
                ],
                'options' => [
                    'label' => 'Contraseña actual <small>(requiere)</small>',
                    'label_attributes' => [
                        'class' => 'col-form-label w-100'
                    ],
                    'label_options' => [
                        'disable_html_escape' => true,
                    ],
                ]
            ]);
        }

        $this->add([
            'type'  => 'password',
            'name' => 'new_password',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Nueva contraseña',
                'required' => true,
                'autofocus' => true,
                'minlength' => 6,
                'maxlength' => 64
            ],
            'options' => [
                'label' => 'Nueva contraseña <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label w-100'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ]
        ]);

        $this->add([
            'type'  => 'password',
            'name' => 'confirm_new_password',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'Confirmar nueva contraseña',
                'required' => true,
                'autofocus' => true,
                'minlength' => 6,
                'maxlength' => 64
            ],
            'options' => [
                'label' => 'Confirmar nueva contraseña <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label w-100'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ]
        ]);

        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                'timeout' => 600
                ]
            ],
        ]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Entrar',
                'class' => 'btn btn-primary btn-block'
            ],
        ]);
    }

    private function addInputFilter() 
    {
        $inputFilter = $this->getInputFilter();
        
        if ($this->scenario == 'change') {

            $inputFilter->add([
                'name'     => 'old_password',
                'required' => true,
                'filters'  => [],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);
        }

        $inputFilter->add([
            'name'     => 'new_password',
            'required' => true,
            'filters'  => [],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 6,
                        'max' => 64
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'confirm_new_password',
            'required' => true,
            'filters'  => [],
            'validators' => [
                [
                    'name'    => 'Identical',
                    'options' => [
                        'token' => 'new_password',
                    ],
                ],
            ],
        ]);
    }
}