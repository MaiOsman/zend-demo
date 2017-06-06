<?php

class Application_Form_LoginForm extends Zend_Form
{

    public function init()
    {
        $this->setMethod('POST');
        $id = new Zend_Form_Element_Hidden('id');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Enter Your Email :');
        $email->setAttribs(array(
                        'class'=>'form-control',
                        'placeholder'=>'example : Mai'));
        $email->setRequired();
        $email->addValidator('StringLength',false,Array(15,25));
        $email->addFilter('StringTrim');

        $passwd = new Zend_Form_Element_Password('passwd');
        $passwd->setLabel('Password : ');
        $passwd->setAttribs(array(
                        'class'=>'form-control'));
        $passwd->setRequired();
        $passwd->addValidator('StringLength',false,Array(6,20));

        $login= new Zend_Form_Element_Submit('login');
        $login->setAttribs(array('class'=>'btn btn-primary'));

        $this->addElements(array(
          $email,
          $passwd,
          $login
        ));
  }


    }
