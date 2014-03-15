<?php

return array(
    'invokables' => array(
        'wdguser_service_user' => 'WdgUser\Service\User'
    ),
    'factories' => array(
        // We alias this one because it's WdgUser's instance of
        // Zend\Authentication\AuthenticationService. We don't want to
        // hog the FQCN service alias for a Zend\* class.
        'wdguser_user_repos' => function ($sm) {
            return $sm->get('doctrine.entity_manager.orm_default')->getRepository("WdgUser\Entity\User");
        },
    )
);