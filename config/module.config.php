<?php
namespace WdgUser;

return array(
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class' => 'WdgUser\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),
    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers' => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'WdgUser\Entity\User\Role',
            ),
        ),
    ),
    'doctrine' => array(
	'driver' => array(
	     __NAMESPACE__ . '_driver' => array(
                 'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                 'cache' => 'array',
                 'paths' => array(__DIR__ . '/../src/'.__NAMESPACE__.'/Entity')
	     ),
             'orm_default' => array(
                 'drivers' => array(
                      __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                 )
             )
	    )
    )
);