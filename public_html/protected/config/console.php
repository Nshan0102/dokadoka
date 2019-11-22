<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Fireworks',
    'sourceLanguage' => 'ru',
    'language' => 'ru',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',

    ),

    'modules'=>array(


        'admin',
        //uncomment the following to enable the Gii tool

        /*
'gii'=>array(
'class'=>'system.gii.GiiModule',
'password'=>'admin',
 // If removed, Gii defaults to localhost only. Edit carefully to taste.
'ipFilters'=>array('127.0.0.1','::1'),
), */

    ),

    // application components
    'components'=>array(


        'db' => require_once '_db_config.php',
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
            //'class'=>'application.modules.cms.components.CmsHandler',
        ),

        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(


                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace',
                    'categories'=>'system.db.*',
                    'logFile'=>'sql.log',
                ),
                /*
                    array(
                          'class'=>'CFileLogRoute',
                          'levels'=>'trace,info,profile,warning,error',
                          //'categories'=>'*',
                          'logFile' => 'all.log',
                    ),
                */
                /*
                array(
                      'class'=>'CFileLogRoute',
                      'levels'=>'trace,info,profile,warning,error',
                      'categories'=>'application',
                      'logFile' => 'application.log',
                ),
                */
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'trace,info,profile,warning,error',
                    'categories'=>'application.extensions.eauth',
                    'logFile' => 'eauth.log',
                ),

                // uncomment the following to show log messages on web pages
                //array('class'=>'CWebLogRoute'),

            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'fireworksorders@gmail.com',
        'adminPerPage'=>50,
    ),
);
