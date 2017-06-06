<?php

class Application_Form_TrackForm extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('POST');
        $id = new Zend_Form_Element_Hidden('id');

        $tr_name = new Zend_Form_Element_Text('tr_name');
        $tr_name->setLabel('Track Name : ');
        $tr_name->setAttribs(array(
                        'class'=>'form-control',
                        'placeholder'=>'example : open source'));
        $tr_name->setRequired();
        $tr_name->addValidator('StringLength',false,Array(3,20));
        $tr_name->addFilter('StringTrim');

        $submit= new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(array('class'=>'btn btn-success'));

        $reset= new Zend_Form_Element_Submit('reset');
        $reset->setAttribs(array('class'=>'btn btn-danger'));

        $this->addElements(array(
          $tr_name,
          $submit,
          $reset
        ));
    }


}
