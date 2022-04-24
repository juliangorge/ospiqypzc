<?php
namespace Auth\Form;

use Laminas\Form\Form;
use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilter;
use Auth\Validator\UserExistsValidator;

class RegisterForm extends Form
{
    private $em = NULL;

    private $adminForm = false;
    private $editForm = false;

    public function __construct($em = NULL, $adminForm = false, $editForm = false)
    {
        parent::__construct('register');
        $this->setAttribute('method', 'POST');

        $this->em = $em;
        $this->adminForm = $adminForm;
        $this->editForm = $editForm;
        
        $this->addElements();
        $this->addInputFilter();
    }
    
    protected function addElements() 
    {
        $this->add([
            'type'  => 'text',
            'name' => 'email',
            'attributes' => [
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'E-mail',
                'minlength' => 1,
                'maxlength' => 128
            ],
            'options' => [
                'label' => 'E-mail <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label w-100'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ]
        ]);
        
        $this->add([
            'type'  => 'text',
            'name' => 'firstname',
            'attributes' => [
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Nombre',
                'minlength' => 1,
                'maxlength' => 128
            ],
            'options' => [
                'label' => 'Nombre <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label w-100'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ]
        ]);

        $this->add([            
            'type'  => 'text',
            'name' => 'lastname',
            'attributes' => [
                'required' => true,
                'class' => 'form-control',
                'placeholder' => 'Apellido',
                'minlength' => 1,
                'maxlength' => 128
            ],
            'options' => [
                'label' => 'Apellido <small>(requiere)</small>',
                'label_attributes' => [
                    'class' => 'col-form-label w-100'
                ],
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ]
        ]);

        if(!$this->editForm){
            $this->add([
                'type'  => 'password',
                'name' => 'password',
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control',
                    'placeholder' => 'Contraseña',
                    'minlength' => 6,
                    'maxlength' => 64
                ],
                'options' => [
                    'label' => 'Contraseña <small>(requiere)</small>',
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
                'name' => 'confirm_password',
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control',
                    'placeholder' => 'Confirmar contraseña',
                    'minlength' => 6,
                    'maxlength' => 64
                ],
                'options' => [
                    'label' => 'Confirmar contraseña <small>(requiere)</small>',
                    'label_attributes' => [
                        'class' => 'col-form-label w-100'
                    ],
                    'label_options' => [
                        'disable_html_escape' => true,
                    ],
                ]
            ]);
        }

        if($this->adminForm){
            $this->add([
                'name' => 'rank_id',
                'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Rango <small>(requiere)</small>',
                    'label_attributes' => [
                        'class' => 'col-form-label w-100'
                    ],
                    'label_options' => [
                        'disable_html_escape' => true,
                    ],
                    'object_manager' => $this->em,
                    'target_class' => 'Auth\Entity\UserRank',
                    'property' => 'name',
                    'is_method' => false,
                    'display_empty_item' => true,
                    'empty_item_label' => 'Seleccionar Rango',
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
                'name' => 'status',
                'type' => 'select',
                'attributes' => [
                    'required' => true,
                    'class' => 'form-control'
                ],
                'options' => [
                    'label' => 'Estado <small>(requiere)</small>',
                    'label_attributes' => [
                        'class' => 'col-form-label w-100'
                    ],
                    'label_options' => [
                        'disable_html_escape' => true,
                    ],
                    'value_options' => [
                        '1' => 'Activo',
                        '0' => 'Bloqueado'
                    ]
                ],
            ]);
        }

        $this->add([
            'type'  => 'hidden',
            'name' => 'redirect_url'
        ]);
        
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Enviar',
                'class' => 'btn btn-block btn-primary',
            ],
        ]);
    }

    private function addInputFilter() 
    {
        $inputFilter = $this->getInputFilter();

        if($this->editForm){
            $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Laminas\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck' => false,
                        ],
                    ],
                ],
            ]);

        }else{

            $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Laminas\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck' => false,
                        ],
                    ],
                    [
                        'name' => UserExistsValidator::class,
                        'options' => [
                            'em' => $this->em
                        ],
                    ],
                ],
            ]);
        }

        

        $inputFilter->add([
            'name'     => 'firstname',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'lastname',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
        ]);

        if(!$this->editForm){
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
                'name'     => 'confirm_password',
                'required' => true,
                'filters'  => [],
                'validators' => [
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token' => 'password',
                        ],
                    ],
                ],
            ]);
        }
        
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