<?php

/*****************
 * stcg-config
 *
 * The file defines the global constants for the application. It is relative to the environment.
 *
 *
 */

//paths
define('DIR_BASE',      '/');
define('DIR_CAPTCHA',   DIR_BASE . 'recaptcha/');
define('DIR_SCRIPTS',   DIR_BASE . 'scripts/');
define('DIR_THEMES',    DIR_BASE . 'themes/');

define('VIEW_HEADER',   DIR_BASE . 'header.php');
define('VIEW_FOOTER',   DIR_BASE . 'footer.php');

define('HTML_CHARSET',  'utf8');

define('ENV_DEV', 'localhost');
define('ENV_PROD', 'servethecitygeneva.ch');
//define('ENV_PROD', 'hacksrus.biz/stcg/signup');


//DB
if ($_SERVER['HTTP_HOST'] == ENV_DEV)
{
    define('DB_HOST', 'localhost');
    define('DB_CHARSET', 'utf8');
    define('DB_USERNAME',   'root');
    define('DB_PWD',        '1f1te111');
    define('DB_NAME',       'stcg');

    define('SESSION_DOMAIN','localhost/stcg');
    define('SESSION_PATH','/');

    define('EMAIL_ORG', 'swisspenelope@gmail.com');
}
else
{
    define('DB_HOST', 'mysql.servethecitygeneva.ch');
    define('DB_CHARSET', 'utf8');
    define('DB_USERNAME',  'adminstcg');
    define('DB_PWD',       '1f1te111');
    define('DB_NAME',      'servethecitygenevach1');

    define('SESSION_DOMAIN','www.servethecitygeneva.ch/stcg/site');
    define('SESSION_PATH','/');

    define('EMAIL_ORG', 'swisspenelope@gmail.com');
}

//Logs
define("SEV_CRITICAL", 1);
define("SEV_WARNING", 2);
define("SEV_INFO", 3);
define("SEV_DEBUG", 4);

if ($_SERVER['HTTP_HOST'] == ENV_DEV) {
    define('LOG_FILE',       'logs/log.txt');
}
elseif ($_SERVER['HTTP_HOST'] == ENV_PROD){
    define('LOG_FILE', 'logs/log.txt');
}
?>