<?php
use WdgUser\Form;

return array(
    'factories' => array(
        'wdguser_edit_form' => function($sm){
            $form = new Form\User\Edit();

            $form->setInputFilter(new \WdgUser\Filter\User\Edit());

            return $form;
        },
        'wdguser_change_password_form' => function($sm){
            $form = new Form\User\ChangePassword();

            $form->setInputFilter(new \WdgUser\Filter\User\ChangePassword());

            return $form;
        },
        'wdguser_add_form' => function($sm){
            $form = new Form\User\Add();

            $form->setInputFilter(new \WdgUser\Filter\User\Add());

            return $form;
        },
    )
);