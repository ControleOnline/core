<?php

namespace Core;

return array(
    'view_helpers' => array(
        'invokables' => array(
            'render_entity_form' => 'Core\Helper\RenderEntityForm'
        ),
    ),
    'LazyLoadImages' => array(
        'LazyLoadImages' => true,
        'LazyLoadClass' => 'lazy-load',
        'LazyLoadPlaceHolder' => 'data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==',
        'LazyLoadOnlyOnNoScript' => array('itemprop'),
        'LazyLoadExcludeTags' => array('script', 'noscript', 'textarea')
    ),
    'view' => array(
        'terminal_sufix' => array(
            '.html'
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Core\Storage\SessionStorage' => 'Core\Factory\SessionFactory',
            'Core\Controller\Default' => 'Core\Factory\DefaultControllerFactory',
            'Core\Model\Default' => 'Core\Factory\DefaultModelFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Core\Controller\Default' => 'Core\Controller\DefaultController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Home\Controller\Default',
                        'action' => 'index',
                    ),
                ),
            ),
            'default' => array(
                'type' => 'Core\Core',
                'options' => array(
                    'route' => '/[:module][/:controller[/:action]]',
                    'constraints' => array(
                        'module' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'discoveryModule' => 'Core',
                        'module' => 'Home',
                        'controller' => 'Default',
                        'action' => 'index',
                        'base_url' => 'api',
                    ),
                ),
            )
        )
    ),
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'datetime_functions' => array(
                    'convert_tz' => 'DoctrineExtensions\Query\Mysql\ConvertTz',
                    'date' => 'DoctrineExtensions\Query\Mysql\Date',
                    'date_format' => 'DoctrineExtensions\Query\Mysql\DateFormat',
                    'dateadd' => 'DoctrineExtensions\Query\Mysql\DateAdd',
                    'datesub' => 'DoctrineExtensions\Query\Mysql\DateSub',
                    'datediff' => 'DoctrineExtensions\Query\Mysql\DateDiff',
                    'day' => 'DoctrineExtensions\Query\Mysql\Day',
                    'dayname' => 'DoctrineExtensions\Query\Mysql\DayName',
                    'dayofweek' => 'DoctrineExtensions\Query\Mysql\DayOfWeek',
                    'dayofyear' => 'DoctrineExtensions\Query\Mysql\DayOfYear',
                    'div' => 'DoctrineExtensions\Query\Mysql\Div',
                    'from_unixtime' => 'DoctrineExtensions\Query\Mysql\FromUnixtime',
                    'hour' => 'DoctrineExtensions\Query\Mysql\Hour',
                    'last_day' => 'DoctrineExtensions\Query\Mysql\LastDay',
                    'minute' => 'DoctrineExtensions\Query\Mysql\Minute',
                    'now' => 'DoctrineExtensions\Query\Mysql\Now',
                    'month' => 'DoctrineExtensions\Query\Mysql\Month',
                    'monthname' => 'DoctrineExtensions\Query\Mysql\MonthName',
                    'second' => 'DoctrineExtensions\Query\Mysql\Second',
                    'sectotime' => 'DoctrineExtensions\Query\Mysql\SecToTime',
                    'strtodate' => 'DoctrineExtensions\Query\Mysql\StrToDate',
                    'time' => 'DoctrineExtensions\Query\Mysql\Time',
                    'timediff' => 'DoctrineExtensions\Query\Mysql\TimeDiff',
                    'timestampadd' => 'DoctrineExtensions\Query\Mysql\TimestampAdd',
                    'timestampdiff' => 'DoctrineExtensions\Query\Mysql\TimestampDiff',
                    'timetosec' => 'DoctrineExtensions\Query\Mysql\TimeToSec',
                    'week' => 'DoctrineExtensions\Query\Mysql\Week',
                    'weekday' => 'DoctrineExtensions\Query\Mysql\WeekDay',
                    'year' => 'DoctrineExtensions\Query\Mysql\Year',
                    'yearmonth' => 'DoctrineExtensions\Query\Mysql\YearMonth',
                    'yearweek' => 'DoctrineExtensions\Query\Mysql\YearWeek',
                    'unix_timestamp' => 'DoctrineExtensions\Query\Mysql\UnixTimestamp',
                    'utc_timestamp' => 'DoctrineExtensions\Query\Mysql\UtcTimestamp',
                    'extract' => 'DoctrineExtensions\Query\Mysql\Extract',
                ),
                'numeric_functions' => array(
                    'acos' => 'DoctrineExtensions\Query\Mysql\Acos',
                    'asin' => 'DoctrineExtensions\Query\Mysql\Asin',
                    'atan2' => 'DoctrineExtensions\Query\Mysql\Atan2',
                    'atan' => 'DoctrineExtensions\Query\Mysql\Atan',
                    'bit_count' => 'DoctrineExtensions\Query\Mysql\BitCount',
                    'bit_xor' => 'DoctrineExtensions\Query\Mysql\BitXor',
                    'ceil' => 'DoctrineExtensions\Query\Mysql\Ceil',
                    'cos' => 'DoctrineExtensions\Query\Mysql\Cos',
                    'cot' => 'DoctrineExtensions\Query\Mysql\Cot',
                    'degrees' => 'DoctrineExtensions\Query\Mysql\Degrees',
                    'exp' => 'DoctrineExtensions\Query\Mysql\Exp',
                    'floor' => 'DoctrineExtensions\Query\Mysql\Floor',
                    'log' => 'DoctrineExtensions\Query\Mysql\Log',
                    'log10' => 'DoctrineExtensions\Query\Mysql\Log10',
                    'log2' => 'DoctrineExtensions\Query\Mysql\Log2',
                    'pi' => 'DoctrineExtensions\Query\Mysql\Pi',
                    'power' => 'DoctrineExtensions\Query\Mysql\Power',
                    'quarter' => 'DoctrineExtensions\Query\Mysql\Quarter',
                    'radians' => 'DoctrineExtensions\Query\Mysql\Radians',
                    'rand' => 'DoctrineExtensions\Query\Mysql\Rand',
                    'round' => 'DoctrineExtensions\Query\Mysql\Round',
                    'stddev' => 'DoctrineExtensions\Query\Mysql\StdDev',
                    'sin' => 'DoctrineExtensions\Query\Mysql\Sin',
                    'std' => 'DoctrineExtensions\Query\Mysql\Std',
                    'tan' => 'DoctrineExtensions\Query\Mysql\Tan',
                    'variance' => 'DoctrineExtensions\Query\Mysql\Variance',
                ),
                'string_functions' => array(
                    'aes_decrypt' => 'DoctrineExtensions\Query\Mysql\AesDecrypt',
                    'aes_encrypt' => 'DoctrineExtensions\Query\Mysql\AesEncrypt',
                    'any_value' => 'DoctrineExtensions\Query\Mysql\AnyValue',
                    'ascii' => 'DoctrineExtensions\Query\Mysql\Ascii',
                    'binary' => 'DoctrineExtensions\Query\Mysql\Binary',
                    'char_length' => 'DoctrineExtensions\Query\Mysql\CharLength',
                    'collate' => 'DoctrineExtensions\Query\Mysql\Collate',
                    'concat_ws' => 'DoctrineExtensions\Query\Mysql\ConcatWs',
                    'countif' => 'DoctrineExtensions\Query\Mysql\CountIf',
                    'crc32' => 'DoctrineExtensions\Query\Mysql\Crc32',
                    'degrees' => 'DoctrineExtensions\Query\Mysql\Degrees',
                    'field' => 'DoctrineExtensions\Query\Mysql\Field',
                    'find_in_set' => 'DoctrineExtensions\Query\Mysql\FindInSet',
                    'greatest' => 'DoctrineExtensions\Query\Mysql\Greatest',
                    'group_concat' => 'DoctrineExtensions\Query\Mysql\GroupConcat',
                    'ifelse' => 'DoctrineExtensions\Query\Mysql\IfElse',
                    'ifnull' => 'DoctrineExtensions\Query\Mysql\IfNull',
                    'least' => 'DoctrineExtensions\Query\Mysql\Least',
                    'lpad' => 'DoctrineExtensions\Query\Mysql\Lpad',
                    'match' => 'DoctrineExtensions\Query\Mysql\MatchAgainst',
                    'md5' => 'DoctrineExtensions\Query\Mysql\Md5',
                    'nullif' => 'DoctrineExtensions\Query\Mysql\NullIf',
                    'radians' => 'DoctrineExtensions\Query\Mysql\Radians',
                    'regexp' => 'DoctrineExtensions\Query\Mysql\Regexp',
                    'replace' => 'DoctrineExtensions\Query\Mysql\Replace',
                    'rpad' => 'DoctrineExtensions\Query\Mysql\Rpad',
                    'sha1' => 'DoctrineExtensions\Query\Mysql\Sha1',
                    'sha2' => 'DoctrineExtensions\Query\Mysql\Sha2',
                    'soundex' => 'DoctrineExtensions\Query\Mysql\Soundex',
                    'str_to_date' => 'DoctrineExtensions\Query\Mysql\StrToDate',
                    'substring_index' => 'DoctrineExtensions\Query\Mysql\SubstringIndex',
                    'uuid_short' => 'DoctrineExtensions\Query\Mysql\UuidShort',
                    'hex' => 'DoctrineExtensions\Query\Mysql\Hex',
                    'unhex' => 'DoctrineExtensions\Query\Mysql\Unex',
                )
            )
        ),
        'orm' => array(
            'auto_mapping' => true
        ),
        'driver' => array(
            'Entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array'
            ),
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Core\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
);
