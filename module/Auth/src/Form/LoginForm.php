<?php
namespace Auth\Form;

use Laminas\Form\Form;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilter;

class LoginForm extends Form
{
    public function __construct()
    {
        parent::__construct('login');
     
        $this->setAttribute('method', 'POST');
                
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements() 
    {
        $this->add([
            'type' => 'email',
            'name' => 'email',
            'attributes' => [
                'id' => 'input-email',
                'class' => 'form-control',
                'placeholder' => 'E-mail'
            ],
            'options' => [
                'label' => 'E-mail',
            ],
        ]);

        $this->add([
            'type'  => 'password',
            'name' => 'password',
            'attributes' => [
                'id' => 'input-password',
                'class' => 'form-control',
                'placeholder' => 'Contraseña'
            ],
            'options' => [
                'label' => 'Contraseña',
            ],
        ]);

        $this->add([            
            'type'  => \Laminas\Form\Element\Checkbox::class,
            'name' => 'remember_me',
            'options' => [
                'use_hidden_element' => true,
                'label' => 'Recordar contraseña',
            ],
            'attributes' => [
                'id' => 'remember_me',
            ]
        ]);
        
        $this->add([
            'type'  => 'hidden',
            'name' => 'redirect_url'
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
            'type'  => 'button',
            'name' => 'submit',
            'options' => [
                'label'   => 'Entrar',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [ 
                'type'  => 'submit',
                'class' => 'btn btn-block btn-primary',
                'id' => 'submit',
            ],
        ]);
    }

    private function addInputFilter() 
    {
        $inputFilter = $this->getInputFilter();
                
        $inputFilter->add([
            'name'     => 'email',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],                
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'allow' => \Laminas\Validator\Hostname::ALLOW_DNS,
                        'useMxCheck' => false,
                    ],
                ],
            ],
        ]);       

        $inputFilter->add([
            'name'     => 'password',
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
            'name'     => 'remember_me',
            'required' => false,
            'filters'  => [],
            'validators' => [
                [
                    'name'    => 'InArray',
                    'options' => [
                        'haystack' => [0, 1],
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'redirect_url',
            'required' => false,
            'filters'  => [
                ['name'=>'StringTrim']
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 0,
                        'max' => 2048
                    ]
                ],
            ],
        ]);
    }
}