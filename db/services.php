<?php

$services = array(
    'repositoryciaeservice' => array(                      //the name of the web service
        'functions' => array ('local_repositoryciae_submit_create_collab_form','local_repositoryciae_loadjson'), //web service functions of this service
        'requiredcapability' => '',                //if set, the web service user need this capability to access 
                                                   //any function of this service. For example: 'some/capability:specified'                 
        'restrictedusers' =>0,                      //if enabled, the Moodle administrator must link some user to this service
                                                    //into the administration
        'enabled'=>1,                               //if enabled, the service can be reachable on a default installation
        'shortname'=>'repositoryciaeservice' //the short name used to refer to this service from elsewhere including when fetching a token
     )
);

$functions = array(
    'local_repositoryciae_submit_create_collab_form' => array(
        'classname' => 'local_repositoryciae_external',
        'methodname' => 'submit_create_collab_form',
        'classpath' => 'local/repositoryciae/externallib.php',
        'description' => 'Creates a collab from submitted form data',
        'ajax' => true,
        'type' => 'write'
    ),
    'local_repositoryciae_loadjson' => array(
        'classname' => 'local_repositoryciae_external',
        'methodname' => 'loadjson',
        'classpath' => 'local/repositoryciae/externallib.php',
        'description' => 'Load json data for compound user profile type',
        'type' => 'read',
        'ajax' => true,
        'loginrequired' => true,
    )
);