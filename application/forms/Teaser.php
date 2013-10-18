<?php

class Application_Form_Teaser extends Zend_Form
{

    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        $this->setDecorators(array('FormElements','Form'));

 
        // Add an email element
        $this->addElement('text', 'email', array(
            'required'   => true,
            'value'      => 'Cadastre seu e-mail para receber as novidades.', 
            'class'      => 'FieldInput',
            'onfocus'    => 'FocusOn(this);',
            'onblur'     => 'FocusOff(this);',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress', array(
                    
                ),
            ),
            'decorators'=>array(
                'ViewHelper',
            ),
            

        ));
 
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'class'    => 'FieldButton',
            'label'    => 'Enviar',
            'decorators'=>array(
                'ViewHelper',
            ),
        ));
        
        
   
    }


}

