<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.09.18
 * Time: 11:05
 */

namespace Gallery\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class PhotoForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('photo-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Задаем бинарное кодирование содержимого (чтобы загрузился файл)
        $this->setAttribute('enctype', 'multipart/form-data');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {
        //связка <label><input> - называется элементом формы

        // Add "title" field //Заголовок фотографии
        $this->add([
            'type'  => 'text',
            'name' => 'title',
            'attributes' => [
                'id' => 'title'
            ],
            'options' => [
                'label' => 'Заголовок фотографии',
            ],
        ]);

        // Add "geo" field //Адрес снимка
        $this->add([
            'type'  => 'textarea',
            'name' => 'geo',
            'attributes' => [
                'id' => 'geo'
            ],
            'options' => [
                'label' => 'Адрес снимка',
            ],
        ]);

        // Add "file" field //Прикрепите файл
        $this->add([
            'type'  => 'file',
            'name' => 'file',
            'attributes' => [
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Прикрепите файл',
            ],
        ]);

        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Upload',
                'id' => 'submitbutton',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter()
    {

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name'     => 'title',
            'required' => true,
            'filters'  => [
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 50
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'geo',
            'required' => false,
            'filters'  => [
                ['name' => 'StripTags'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 50
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'file',
            'type'     => 'Zend\InputFilter\FileInput',//если не указано и по умолчанию Zend\InputFilter\Input::class
            'required' => true,
            'validators' => [//для файлов валидаторы первоочереднее фильтров

                ['name'    => 'FileUploadFile'], //действительно ли файл был выгружен на сервер через POST
                /*
                [
                    'name'    => 'FileMimeType', //является ли файл изображение JPEG PNG //требует пхп-расширения fileinfo
                    'options' => [
                        'mimeType'  => ['image/jpeg', 'image/png']
                    ]
                ],
                */

                //['name'    => 'FileIsImage'], //является ли файл изображение JPG PNG GIT

                [
                    'name'    => 'FileSize',
                    'options' => [
                        'max' => '20MB'
                    ]
                ]

                /*
                [
                    'name'    => 'FileImageSize', //допустимые размеры изображения
                    'options' => [
                        'minWidth'  => 128,
                        'minHeight' => 128,
                        'maxWidth'  => 4096,
                        'maxHeight' => 4096
                    ]
                ],
                */
            ],
            'filters'  => [
                [
                    'name' => 'Zend\Filter\File\RenameUpload',
                    'options' => [
                        'target'=>'./data/upload', //выгруженный файл сохраняем в эту папку
                        'useUploadName'=>true, //оставить имя файла и расширение, каким оно было при загрузке пользователем (для уник. использ рандомизатор)
                        'useUploadExtension'=>true,
                        'overwrite'=>false, //если файл с именем существует, то перезапись невозможна
                        'randomize'=>true, //рандомизировать имя файла (чтобы не было совпадений помещаемого файла и существующих)
                    ]
                ]

            ],
        ]);


    }


}