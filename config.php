<?php
// HTTP
define('HTTP_SERVER', 'http://fruits.itservice.ks.ua/');

// HTTPS
define('HTTPS_SERVER', 'http://fruits.itservice.ks.ua/');

// DIR
define('DIR_APPLICATION', '/home/itserv10/itservice.ks.ua/fruits/catalog/');
define('DIR_SYSTEM', '/home/itserv10/itservice.ks.ua/fruits/system/');
define('DIR_IMAGE', '/home/itserv10/itservice.ks.ua/fruits/image/');
define('DIR_STORAGE', '/home/itserv10/itservice.ks.ua/fruits_storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'itserv10.mysql.tools');
define('DB_USERNAME', 'itserv10_fruits');
define('DB_PASSWORD', 'l5sjmlpp');
define('DB_DATABASE', 'itserv10_fruits');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');