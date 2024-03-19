<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
return array(
    'db' => array(
        'adapters' => array(
            'dbConfig' => array(
                'driver'   => 'Pdo_Mysql',
                'database' => 'forewinv_crm_db',
                'username' => 'forewinv_root',
                'password' => 'forewin@123',
                'hostname' => 'localhost',
                'port'     => '',
                'charset'  => 'utf8'
            ),
            'dbNotify' => array(
                'driver'   => 'Pdo_Mysql',
                'database' => 'forewinv_crm_notify',
                'username' => 'forewinv_root',
                'password' => 'forewin@123',
                'hostname' => 'localhost',
                'port'     => '',
                'charset'  => 'utf8'
            ),
        )
        // 'adapters' => array(
        //     'dbConfig' => array(
        //         'driver'   => 'Pdo_Mysql',
        //         'database' => 'langtech_forewin',
        //         'username' => 'root',
        //         'password' => '',
        //         'hostname' => '192.168.2.98',
        //         'port'     => '',
        //         'charset'  => 'utf8'
        //     ),
        //     'dbNotify' => array(
        //         'driver'   => 'Pdo_Mysql',
        //         'database' => 'langtech_notify',
        //         'username' => 'root',
        //         'password' => '',
        //         'hostname' => '192.168.2.98',
        //         'port'     => '',
        //         'charset'  => 'utf8'
        //     ),
        // )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Db\Adapter\AdapterAbstractServiceFactory'
        )
    )
);
?>