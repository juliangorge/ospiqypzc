<?php
namespace Admin\Form;

use Laminas\Form\Form;

class Specialties extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('specialties');

        $this->add([
            'name' => 'name',
            'attributes' => [
                'id' => 'name',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 120,
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
            'name' => 'document_id',
            'attributes' => [
                'id' => 'document_id',
                'type' => 'hidden'
            ],
        ]);

        $this->add([
            'name' => 'allow_edoctor',
            'type' => \Laminas\Form\Element\Checkbox::class,
            'attributes' => [
                'id' => 'allow_edoctor',
                'class' => 'form-check-input'
            ],
            'options' => [
                'label' => 'Telemedicina',
                'label_attributes' => [
                    'class' => 'col-sm-12 mb-1'
                ],
            ]
        ]);

        $this->add([
            'name' => 'attention_intervals',
            'type' => 'number',
            'attributes' => [
                'id' => 'attention_intervals',
                'required' => true,
                'class' => 'form-control',
                'min' => 10,
                'placeholder' => 'Intervalo'
            ],
            'options' => [
                'label' => 'Intervalo <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'submit',
            'attributes' => [
                'id' => 'submit',
                'class' => 'btn btn-primary my-3',
                'value' => 'Guardar',
                'type' => 'submit'
            ]
        ]);

        $this->setValidationGroup([
            'name',
            'allow_edoctor',
            'attention_intervals',
            'submit'
        ]);
    }
}