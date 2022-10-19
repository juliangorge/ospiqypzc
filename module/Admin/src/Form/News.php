<?php
namespace Admin\Form;

use Laminas\Form\Form;

class News extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('newsletter');

        $this->add([
            'name' => 'title',
            'attributes' => [
                'id' => 'title',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 120,
                'placeholder' => 'Título'
            ],
            'options' => [
                'label' => 'Título <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'body',
            'attributes' => [
                'id' => 'body',
                'required' => true,
                'class' => 'form-control',
                'type' => 'textarea',
                'rows' => 4,
                'placeholder' => 'Cuerpo'
            ],
            'options' => [
                'label' => 'Cuerpo <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'mt-3 col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'date',
            'attributes' => [
                'id' => 'date',
                'required' => true,
                'class' => 'form-control',
                'type' => 'date',
            ],
            'options' => [
                'label' => 'Fecha de publicación (según Web) <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'mt-3 col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'picture_url',
            'attributes' => [
                'id' => 'picture_url',
                'required' => false,
                'class' => 'form-control',
                'maxlength' => 120,
                'placeholder' => 'https://....'
            ],
            'options' => [
                'label' => 'Imagen (URL)',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'mt-3 col-form-label'
                ]
            ]
        ]);

        $this->add([
            'name' => 'piece_of_news_url',
            'attributes' => [
                'id' => 'piece_of_news_url',
                'required' => true,
                'class' => 'form-control',
                'maxlength' => 120,
                'placeholder' => 'https://....'
            ],
            'options' => [
                'label' => 'URL de la Publicación <small>(requiere)</small>',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
                'label_attributes' => [
                    'class' => 'mt-3 col-form-label'
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
            'name' => 'submit',
            'attributes' => [
                'id' => 'submit',
                'class' => 'btn btn-primary my-3',
                'value' => 'Guardar',
                'type' => 'submit'
            ]
        ]);

        $this->setValidationGroup([
            'title',
            'body',
            'date',
            'picture_url',
            'piece_of_news_url',
            'document_id',
            'submit'
        ]);
    }
}