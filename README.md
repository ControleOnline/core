[![Build Status](https://travis-ci.org/ControleOnline/core.svg)](https://travis-ci.org/ControleOnline/core)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ControleOnline/core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ControleOnline/core/)
[![Code Coverage](https://scrutinizer-ci.com/g/ControleOnline/core/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ControleOnline/core/)
[![Build Status](https://scrutinizer-ci.com/g/ControleOnline/core/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ControleOnline/core/)

# Core of Controle Online modules #

Used by another projects:
```
https://github.com/ControleOnline/rest-essentials
https://github.com/ControleOnline/speed-up-essentials
https://github.com/ControleOnline/doubleclick
https://github.com/ControleOnline/table-essentials
https://github.com/ControleOnline/zf2-essentials
```


### Configure DB ###
In your config/autoload/database.local.php confiruration add the following:

```
<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'user',
                    'password' => 'pass',
                    'dbname' => 'db',
                    'driver' => 'pdo_mysql',
                    'charset' => 'utf8',//Very important
                    'driverOptions' => array(
                            1002=>'SET NAMES utf8' //Very important
                    )
                )
            )
        )
    )
);
```


### Zend 2 ###
In your config/application.config.php confiruration add the following:

```
<?php
$modules = array(
    'Core' 
);
return array(
    'modules' => $modules,
    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
    ),
);
```
In your module.config.php file:

```
<?php
namespace YourNameSpace;

return array(
    'Core' => array(
            'EntityPath' => getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR
        )
);
```