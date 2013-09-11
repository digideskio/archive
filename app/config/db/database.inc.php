<?php

//----------------------------
// DATABASE CONFIGURATION
//----------------------------

/*

Valid types (adapters) are Postgres & MySQL:

'type' must be one of: 'pgsql' or 'mysql'

*/

return array(
        'db' => array(
                'production' => array(
                        'type'      => 'mysql',
                        'host'      => 'localhost',
                        'port'      => 3306,
                        'database'  => 'production',
                        'user'      => 'USER',
                        'password'  => 'PASSWORD',
                        'charset' => 'utf8',
                        'directory' => '',
                        //'socket' => '/var/run/mysqld/mysqld.sock'
                ),

                'development' => array(
                        'type'      => 'mysql',
                        'host'      => 'localhost',
                        'port'      => 3306,
                        'database'  => 'development',
                        'user'      => 'USER',
                        'password'  => 'PASSWORD',
                        'charset' => 'utf8',
                        'directory' => '',
                        //'socket' => '/var/run/mysqld/mysqld.sock'
                ),

                'test'  => array(
                        'type'  => 'mysql',
                        'host'  => 'localhost',
                        'port'  => 3306,
                        'database'  => 'test',
                        'user'  => 'USER',
                        'password'  => 'PASSWORD',
                        'charset' => 'utf8',
                        'directory' => '',
                        //'socket' => '/var/run/mysqld/mysqld.sock'
                )

        ),

        'migrations_dir' => array('default' => RUCKUSING_WORKING_BASE . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'migrate'),

        'db_dir' => RUCKUSING_WORKING_BASE . DIRECTORY_SEPARATOR . 'migrate',

        'log_dir' => RUCKUSING_WORKING_BASE . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'logs',

        'ruckusing_base' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'ruckusing-migrations'

);
