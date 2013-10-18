<?php

class Application_Model_Teaser
{
    protected $_email;
    protected $_active=0;
    protected $_datetime;
    protected $_validation;
 
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid teaser property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid teaser property');
        }
        return $this->$method();
    }
 
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
 
 
    public function setEmail($email)
    {
        $this->_email = (string) $email;
        return $this;
    }
 
    public function getEmail()
    {
        return $this->_email;
    }
 
    public function setActive($active)
    {
        $this->_active = (int)$active;
        return $this;
    }
 
    public function getActive()
    {
        return $this->_active;
    }
 
    public function setDatetime($datetime)
    {
        $this->_datetime = $datetime;
        return $this;
    }
 
    public function getDatetime()
    {
        return $this->_datetime;
    }
    
    public function setValidation($validation)
    {
        $this->_validation = (string) $validation;
        return $this;
    }
 
    public function getValidation()
    {
        return $this->_validation;
    }

}
?>
