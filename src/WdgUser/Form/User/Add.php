<?php
namespace WdgUser\Form\User;

class Add extends Edit
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
                'required' => true
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'options' => array(
                'label' => 'Password Verify',
            ),
            'attributes' => array(
                'type' => 'password'
            ),
        ));
    }
}