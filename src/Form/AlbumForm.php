<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.09.18
 * Time: 10:47
 */

namespace Gallery\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class AlbumForm extends Form
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // Define form name
        parent::__construct('album-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        $this->addElements();
        $this->addInputFilter();
    }

    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements()
    {

        // Add "label" field //Название альбома
        $this->add([
            'type'  => 'text',
            'name' => 'label',
            'attributes' => [
                'id' => 'label'
            ],
            'options' => [
                'label' => 'Название альбома',
            ],
        ]);

        // Add "note" field //Описание альбома
        $this->add([
            'type'  => 'textarea',
            'name' => 'note',
            'attributes' => [
                'id' => 'note'
            ],
            'options' => [
                'label' => 'Описание альбома',
            ],
        ]);

        // Add "authorName" field //Имя фотографа
        $this->add([
            'type'  => 'text',
            'name' => 'authorName',
            'attributes' => [
                'id' => 'authorName'
            ],
            'options' => [
                'label' => 'Имя фотографа',
            ],
        ]);


        // Add "email" field //E-mail
        $this->add([
            'type'  => 'email',
            'name' => 'email',
            'attributes' => [
                'value' => 'example@site.com',
                'id' => 'email'
            ],
            'options' => [
                'label' => 'E-mail',
            ],
        ]);

        // Add "phone" field //Телефон
        $this->add([
            'type'  => 'text',
            'name' => 'phone',
            'attributes' => [
                'value' => '+7 (111) 222-44-55',
                'id' => 'phone'
            ],
            'options' => [
                'label' => 'Телефон в формате +7 (111) 222-44-55',
            ],
        ]);

        // Add the submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Create',
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
            'name'     => 'label',
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
            'name'     => 'authorName',
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
            'name'     => 'email',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
               # [
                    #'name'  => 'Zend\Validator\EmailAddress',
                    #'options' => [
                        #'allow' => \Zend\Validator\Hostname::ALLOW_DNS, //разрешаем использование доменных имен в качестве адресов почты
                        #'useMxCheck' => false, //отключена проверки Mx-записи
                    #],
               # ],
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 40
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name'     => 'phone',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 18
                    ],
                ],
            ],
        ]);

    }


}