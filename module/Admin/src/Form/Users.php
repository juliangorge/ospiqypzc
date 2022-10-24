<?php
namespace Admin\Form;

use Juliangorge\Users\Form\RegisterForm as OriginalForm;

class Users extends OriginalForm
{
    protected function addElements() 
    {

        $this->add([
            'name' => 'email',
            'attributes' => [
                'class' => 'form-control my-1',
                'placeholder' => 'E-mail',
            ],
            'options' => [
                'label' => 'E-mail',
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
            ],
        ]);
        
        $this->add([
            'name' => 'first_name',
            'attributes' => [
                'class' => 'form-control my-1',
                'placeholder' => 'Nombre',
            ],
            'options' => [
                'label' => 'Nombre',
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
            ],
        ]);

        $this->add([
            'name' => 'last_name',
            'attributes' => [
                'class' => 'form-control my-1',
                'placeholder' => 'Apellido',
            ],
            'options' => [
                'label' => 'Apellido',
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
            ],
        ]);

        if ($this->scenario == 'create') {
            // Add "password" field
            $this->add([
                'type'  => 'password',
                'name' => 'password',
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Contraseña',
                ],
                'options' => [
                    'label' => 'Contraseña',
                    'label_attributes' => [
                        'class' => 'col-sm-12 col-form-label'
                    ],
                ],
            ]);
            
            // Add "confirm_password" field
            $this->add([
                'type'  => 'password',
                'name' => 'confirm_password',
                'attributes' => [
                    'class' => 'form-control',
                    'placeholder' => 'Confirmar contraseña',
                ],
                'options' => [
                    'label' => 'Confirmar contraseña',
                    'label_attributes' => [
                        'class' => 'col-sm-12 col-form-label'
                    ],
                ],
            ]);
        }

        $this->add([
            'name' => 'rank_id',
            'type'  => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => [
                'id' => 'rank_id',
                'required' => true,
                'class' => 'form-control'
            ],
            'options' => [
                'label' => 'Rango',
                'label_attributes' => [
                    'class' => 'col-sm-12 col-form-label'
                ],
                'object_manager' => $this->entityManager,
                'target_class' => 'Juliangorge\Users\Entity\UserRank',
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
            'type'  => 'hidden',
            'name' => 'redirect_url'
        ]);

        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Guardar',
                'class' => 'btn btn-block btn-primary',
            ],
        ]);

    }
}