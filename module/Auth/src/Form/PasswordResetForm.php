<?php
namespace Auth\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;

class PasswordResetForm extends Form
{
    public function __construct()
    {
        parent::__construct('password-reset');
     
        $this->setAttribute('method', 'POST');
                
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements() 
    {
        $this->add([
            'type'  => 'email',
            'name' => 'email',
            'attributes' => [
                'class' => 'form-control',
                'placeholder' => 'E-mail',
                'required' => true,
                'autofocus' => true,
            ],
        ]);

        $this->add([
            'type' => 'captcha',
            'name' => 'captcha',
            'attributes' => [
                'class' => 'form-control',
                'required' => true,
                'autofocus' => true,
                'placeholder' => 'Ingrese las letras',
                'autocomplete' => 'off'
            ],
            'options' => [
                'label' => '¿Eres robot?',
                'captcha' => [
                    'class' => 'Image',
                    'imgDir' => 'public/img/captcha',
                    'suffix' => '.png',
                    'imgUrl' => '/img/captcha/',
                    'imgAlt' => 'CAPTCHA Image',
                    'font' => './public/fonts/roboto/Roboto-Bold.ttf',
                    'fsize' => 30,
                    'width' => 350,
                    'height' => 100,
                    'expiration' => 600,
                    'dotNoiseLevel' => 10,
                    'lineNoiseLevel' => 10
                ],
            ],
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
                'id' => 'submit',
                'class'=>'btn btn-primary btn-block'
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
    }        
}