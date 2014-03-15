<?php
namespace WdgUser;

class Module 
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
    * {@InheritDoc}
    */
    public function getFormElementConfig()
    {
        return include __DIR__ . '/config/form-elements.config.php';
    }
    
    /**
    * {@InheritDoc}
    */
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }
}
