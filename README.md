[![Build Status](https://travis-ci.org/ControleOnline/core.svg)](https://travis-ci.org/ControleOnline/core)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ControleOnline/core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ControleOnline/core/)
[![Code Coverage](https://scrutinizer-ci.com/g/ControleOnline/core/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ControleOnline/core/)
[![Build Status](https://scrutinizer-ci.com/g/ControleOnline/core/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ControleOnline/core/)

More on [Controle Online](http://controleonline.com "Controle Online").

# Core of Controle Online modules #

Used by another projects:
```
https://github.com/ControleOnline/rest-essentials
https://github.com/ControleOnline/speed-up-essentials
https://github.com/ControleOnline/doubleclick
https://github.com/ControleOnline/table-essentials
https://github.com/ControleOnline/zf2-essentials
```
## Installation ##
### Composer ###
Add these lines to your composer.json:

```
    "require": {
        "controleonline/rest-essentials": "*"        
    },
    "scripts": {
        "post-update-cmd": [
            "git describe --abbrev=0 --tags > .version",
            "bower install controleonline\\core-js"
        ],
        "pre-install-cmd": [
            "echo '{\"directory\" : \"public/vendor/\"}' > .bowerrc"
        ],
        "post-install-cmd": [                           
        ]
    },

```
## Settings ##
### Configure DB ###
In your config/autoload/database.local.php confiruration add the following:

```
<?php
$db = array(
    'host' => 'localhost',
    'port' => '3306',
    'user' => 'user',
    'password' => 'pass',
    'dbname' => 'db',
    'driver' => 'pdo_mysql',
    'init_command' => 'SET NAMES utf8',
    'port' => '3306'
);
return array(
    'db' => array( //Use on zend session to store session on database (common on balanced web servers)
        'driver' => $db['driver'],
        'dsn' => 'mysql:dbname=' . $db['dbname'] . ';host=' . $db['host'],
        'username' => $db['user'],
        'password' => $db['password'],
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => $db['init_command'],
            'buffer_results' => true
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => $db['host'],
                    'port' => $db['port'],
                    'user' => $db['user'],
                    'password' => $db['password'],
                    'dbname' => $db['dbname'],
                    'driver' => $db['driver'],
                    'charset' => 'utf8', //Very important
                    'driverOptions' => array(
                        1002 => $db['init_command'] //Very important
                    )
                )
            )
        )
    )
);
```

### Configure Session ###
In your config/autoload/session.local.php confiruration add the following:

```
<?php
return array(
    'session' => array(
        'sessionConfig' => array(
            'cache_expire' => 86400,
            'cookie_domain' => 'localhost',
            'name' => 'localhost',
            'cookie_lifetime' => 1800,
            'gc_maxlifetime' => 1800,
            'cookie_path' => '/',
            'cookie_secure' => TRUE,
            'remember_me_seconds' => 3600,
            'use_cookies' => true,
        ),
        'serviceConfig' => array(
            'base64Encode' => false
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

### VIEW TERMINAL ###
Simply add the .html suffix at the end of the URL to set view terminal:
```
http://localhost/<Module>/<Controller>/<Action>.html?<Parameters>
```

If you need to change the suffix, just change in the setting (config/application.config.local.php):
```
<?php
return array(
    'view' => array(
        'terminal_sufix' => array(            
            '.html',
            '.ajax' //Another extension
        )
    ),
    //Another configs
)
```

Do not forget to return a ViewModel on your controller:
```
        $view = new ViewModel();
        //Your code
        $this->_view->setVariables(\ControleOnline\Core\Helper\Format::returnData(array('Test')));
        return $view;
```
### AUTOMATIC ADD JS/CSS FILES ###
To add your js / css files simply place them following this structure:
```
public/assets/js/application.js
<module>/assets/js/modules/<module>.js
<module>/assets/js/modules/<module>/<controller>.js
<module>/assets/js/modules/<module>/<controller>/<action>.js

public/assets/css/application.css
<module>/assets/css/modules/<module>.css
<module>/assets/css/modules/<module>/<controller>.css
<module>/assets/css/modules/<module>/<controller>/<action>.css
```
If these files exist, they will be added in head:
```
<script type="text/javascript" src="/assets/js/application.js"></script>
<script type="text/javascript" src="/js/modules/<module>.js"></script>
<script type="text/javascript" src="/js/modules/<module>/<controller>.js"></script>
<script type="text/javascript" src="/js/modules/<module>/<controller>/<action>.js"></script>

<link href="/assets/css/application.css" media="screen" rel="stylesheet" type="text/css">
<link href="/css/modules/<module.css" media="screen" rel="stylesheet" type="text/css">
<link href="/css/modules/<module/<controller>.css" media="screen" rel="stylesheet" type="text/css">
<link href="/css/modules/<module/<controller>/<action>.css" media="screen" rel="stylesheet" type="text/css">
```

We also add the libraries that are in bower.json and its dependencies automatically.

If you need to add a library manually (this happens when the library's bower.json is not well configured or if there is more than one js file in each dependency), just add it manually:

```
Assets\Helper\Header::addJsLibs('lazyLoad', '/assets/js/core/LazyLoad.js');
```

Do not forget to keep in the layout file the call to the headers:
```
<html lang="en">
    <head>
        <?= $this->headLink() ?>
        <?= $this->headStyle() ?>
        <?= $this->headScript() ?>
    </head>    
    <body data-js-libs='<?= $this->requireJsLibs ? json_encode($this->requireJsLibs) : '{}' ?>' data-js-files='<?= $this->requireJsFiles ? json_encode($this->requireJsFiles) : '{}' ?>'>
        <div class="show-messages">
            <!-- This div (class .show-messages) receives all system alerts  -->
        </div>
    </body>
</html>   
```

### LazyLoad Images ###
The LazyLoad settings can be changed this way in the config/module.config.php file:

```
<?php
return array(
    'LazyLoadImages' => array(
        'LazyLoadImages' => true,
        'LazyLoadClass' => 'lazy-load',
        'LazyLoadPlaceHolder' => 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',
        'LazyLoadOnlyOnNoScript' => array('itemprop'),
        'LazyLoadExcludeTags' => array('script', 'noscript', 'textarea')
    ),
//Another configs
)
```