<?php

class Application_Form_UserForm extends Zend_Form
{

    public function init()
    {
        $this->setMethod('POST');
        $id = new Zend_Form_Element_Hidden('id');

        $fname = new Zend_Form_Element_Text('fname');
        $fname->setLabel('First Name');
        $fname->setAttribs(array(
                        'class'=>'form-control',
                        'placeholder'=>'example : Mai'));
        $fname->setRequired();
        $fname->addValidator('StringLength',false,Array(3,11));
        $fname->addFilter('StringTrim');

        $lname =  new Zend_form_Element_Text('lname');  // <input type="text" name="lname" value="">
        $lname->setLabel('Last Name: ');
        $lname->setAttribs(array(
                        'class'=>'form-control',
                        'placeholder'=>'example : Mohamed'));   //style
        $lname->setRequired();
        $lname->addValidator('StringLength',false,Array(3,11));
        $lname->addFilter('StringTrim');

        $gender = new Zend_Form_Element_Select('gender');
        $gender->setRequired();
        $gender->addMultiOption('male','Male')->
        addMultiOption('female','Female')->
        addMultiOption('non','Prefer not to mention');
        $gender->setAttrib('class', 'form-control');

        $email = new Zend_form_Element_Text('email');  // <input type="text" name="lname" value="">
        $email->setLabel('Email: ');
        $email->setAttribs(array(
                        'class'=>'form-control',
                        'placeholder'=>'example:mai@example.com'));   //style
        $email->setRequired();
        $email->addValidator('StringLength',false,Array(3,20));
        $email->addFilter('StringTrim');

        $track = new Zend_form_Element_Select('track');  // drop down list
        // $track->addMultiOption('opensource', 'OS');   //opensource:eli hatt7t fe el db (value) , os: eli hayb2a maktob fe el dropdown list
        // $track->addMultiOption('sys dev', 'SD');
        // $track->addMultiOption('sys admin', 'SA');
        // $track->addMultiOption('user interface', 'UI');
        // $track->addMultiOption('mob app', 'MA');

        $track_Model = new Application_Model_Track();
        $all_tracks = $track_Model->getAllTracks();
        foreach ($all_tracks as $key => $value){
          $track->addMultiOption($value['tr_name'],$value['tr_name']);
        }
        $track->setLabel('Track :');
        $track->setAttribs(array('class'=>'form-control'));

        $submit= new Zend_Form_Element_Submit('submit');
        $submit->setAttribs(array('class'=>'btn btn-success'));

        $reset= new Zend_Form_Element_Submit('reset');
        $reset->setAttribs(array('class'=>'btn btn-danger'));

        $this->addElements(array(
          $email,
          $fname,
          $lname,
          $gender,
          $track,
          $submit,
          $reset
        ));
}


}
