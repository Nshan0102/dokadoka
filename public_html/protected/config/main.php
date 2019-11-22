<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

if(isset($_SERVER['REQUEST_URI']) && preg_match('~^/catalogApi~i',$_SERVER['REQUEST_URI']))
    $enableCsrfValidation=false;
else
    $enableCsrfValidation=true;
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Fireworks',
    'defaultController' => 'Site',
    'sourceLanguage' => 'ru',
    'language' => 'ru',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',

        'application.components.shoppingCart.*',

        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
        'ext.eauth.custom_services.*',
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
        'loid' => array('class' => 'ext.lightopenid.loid'),
        'eauth' => array(
            'class' => 'ext.eauth.EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'services' => array( // You can change the providers and their classes.
                //'google' => array('class' => 'GoogleOpenIDService'),
                //'yandex' => array('class' => 'YandexOpenIDService'),
                'twitter' => array(
                    // register your app here: https://dev.twitter.com/apps/new
                    'class' => 'CustomTwitterService', //'TwitterOAuthService',
                    'key' => '6sj6S6Bgi3mgXfto03M59g', //Consumer key
                    'secret' => 'yr94KcsZa8DsuPA9GDSSbWLVTp3gDrNEbxGlLYx9WY',//Consumer secret
                ),

                //!!! https://developers.facebook.com/docs/guides/web/#login
                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'FacebookOAuthService',
                    'client_id' => '222218144550343',//App ID:
                    'client_secret' => '9becdb162227294672822c1afe9003be',//App Secret:
                ),


                'vkontakte' => array(
                    'class' => 'VKontakteOAuthService',
                    'client_id' => '2915074',
                    'client_secret' => 'wibCt5TwUXzAwJZgeyRs',
                ),

                'odnoklassniki' => array(
                    // register your app here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                    'class' => 'OdnoklassnikiOAuthService',
                    'client_id' => '71596032',
                    'client_public' => 'CBAJHFEFABABABABA',
                    'client_secret' => '155EEB35E45ED0F3223E16FD',
                    'title' => 'Однокл.',
                ),

                'mailru' => array(
                    // register your app here: http://api.mail.ru/sites/my/add
                    'class' => 'MailruOAuthService',
                    'client_id' => '665250',//ID со страницы http://api.mail.ru/sites/my/665250
                    'client_secret' => 'e4b6bf595560f48c97012329c9f599df',
                ),

                //@todo rem
                //$cs->registerCoreScript('jquery');
                // in EAuthWidget

            ),
        ),

        'cache'=>array( 'class' => 'system.caching.CFileCache' ),

        'request'=>array(
            'enableCsrfValidation'=>$enableCsrfValidation,
        ),

        'shoppingCart' =>
            array(
                'class' => 'application.components.shoppingCart.EShoppingCart',
            ),

        'adminUser'=>array(             // Webuser for the admin area (admin)
            'class'=>'CWebUser',
            'loginUrl'=>array('admin/main/login'),
            'stateKeyPrefix'=>'admin_',
            'allowAutoLogin'=>true,
        ),

		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),


		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
            //'urlSuffix' => '/',
            'useStrictParsing' => false,
			'rules'=>array(
                '' => 'Site/index',


                 /*
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                 */

                'category/<id:([\w-]+)>'=>'Site/category/uri/<id>/',
                'item/<id:([\w-]+)>'=>'Site/item/uri/<id>/',
                //'page/<id:([\w-]+)>'=>'Site/page/uri/<id>/',

                'ajaxbasket'=>'Site/ajaxBasket',
                'login'=>'Site/login',
                'basket'=>'Site/basket',
                'search'=>'Site/search',
                'contact'=>'Site/contact',

                'page/<id:([\w-]+)>'=>'Site/page/uri/<id>',

                //always last!
                '<id:([\w-]+)>'=>'Site/page/uri/<id>',

			),

		),
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

                array(
                      'class'=>'CFileLogRoute',
                      'levels'=>'trace,info,profile,warning,error',
                      'categories'=>'application',
                      'logFile' => 'application.log',
                ),

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
    'params' => require_once(dirname(__FILE__).'/_params.php'),
);
