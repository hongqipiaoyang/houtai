<?php

//*
 return [
    'class' => 'yii\db\Connection',
    // 'dsn' => 'mysql:host=221.228.229.221;dbname=caipiao',
    'dsn' => 'mysql:host=127.0.0.1;dbname=user',
    // 'username' => 'clientUser',
    'username' => 'root',
    // 'password' => '123456',
    'password' => 'root',
    'charset' => 'utf8',
//     'tablePrefix'  => 'sl_',
];
// */


/*
//配置主从服务器
return [
    'class'                   => 'yii\db\Conection',
    'charset'               => 'utf8',
    'tablePrefix'          => '',

    //配置主服务器
    'masterConfig'      =>[
            'username'    =>'root',
            'password'     =>'',
            'attributes'     =>[
                    PDO::ATTR_TIMEOUT =>10,
            ],
    ],

    'masters' =>[
          ['dsn' =>'mysql:host=localhost;dbname=ceshi'],
    ],

//     配置从服务器
    'slaveConfig' => [
        'username'    =>'root',
        'password'     =>'',
        'attributes'     =>[
            PDO::ATTR_TIMEOUT =>10,
        ],
    ],

    'slaves' =>[
          ['dsn' =>'mysql:host=localhost;dbname=ceshi'],
    ],

];
//*/
