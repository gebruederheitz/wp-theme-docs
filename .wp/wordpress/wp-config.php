<?php

/**
 * Composer autoload.
 */
require_once realpath(dirname(__DIR__) . '/../vendor' . '/autoload.php');

define('WPSTARTER_PATH', dirname(__DIR__));

/**
 * Configuration constants.
 *
 * Use environment variables to set WordPress constants.
 * Mandatory settings:
 * - DB_NAME
 * - DB_USER
 * - DB_PASSWORD
 *
 * @var array $env All the variables stored in environment variables
 */
$env = WCM\WPStarter\Helpers::settings(dirname(__DIR__), '.env');

global $table_prefix;
array_walk($env, function ($value, $name) {
    switch ($name) {
        case 'DB_TABLE_PREFIX':
            $GLOBALS['table_prefix'] = preg_replace('#[^\w]#', '', $value);
            break;
        default:
            defined($name) or define($name, $value);
            break;
    }
});

/*
 * Use DDEV settings if not overwritten by .env
 */
defined('DB_NAME') or define('DB_NAME', 'db');

/** MySQL database username */
defined('DB_USER') or define('DB_USER', 'db');

/** MySQL database password */
defined('DB_PASSWORD') or define('DB_PASSWORD', 'db');

/** MySQL hostname */
defined('DB_HOST') or define('DB_HOST', 'db');

/** WP_HOME URL */
defined('WP_HOME') || define('WP_HOME', 'https://wp-theme-docs.ddev.site');

/** WP_SITEURL location */
defined('WP_SITEURL') || define('WP_SITEURL', WP_HOME . '/');

/** Enable debug */
defined('WP_DEBUG') || define('WP_DEBUG', true);

if (!isset($table_prefix) || empty($table_prefix)) {
    $table_prefix = 'wp_';
}

/**
 * Set unique authentication keys if not already set via environment variables.
 */
defined('AUTH_KEY') or
    define(
        'AUTH_KEY',
        'aBDgcN_pE!BD)@WGODiQ9|BEd+>/ E@/VDNl+$`RMgn~`S/,Z< @~)oC=2G/r>dn',
    );
defined('SECURE_AUTH_KEY') or
    define(
        'SECURE_AUTH_KEY',
        '{DOn8BN|a/-_BD*;(oi^.zK|U+N7+.BQr,OvLlUX{}K64j6m8R^oTw3|j[$kR%qv',
    );
defined('LOGGED_IN_KEY') or
    define(
        'LOGGED_IN_KEY',
        ' se#Ip4jF87mKH%B)>nM i=*bzhmJ>]g` ,8f`U4^7Cf%2sq[F,^cN`D~7*m~~Ou',
    );
defined('NONCE_KEY') or
    define(
        'NONCE_KEY',
        'Yk}}gxF+63tmDqPqLgG&eg$,Q4E(7Zj|mx+^nDk$y]/HWRoF*=l*[#},{CsP=la5',
    );
defined('AUTH_SALT') or
    define(
        'AUTH_SALT',
        'dC% ZxYLq6[n@b^3v#oB? :<f0ciY^3j+bilraM(P>z~o308glMVqzP3`P-%54$+',
    );
defined('SECURE_AUTH_SALT') or
    define(
        'SECURE_AUTH_SALT',
        '!@eD3n #]DuU*4?7]8 gij#E6{<)C#-=k.[.jf9|uDXo]Is ,UW;$x+ 4^)}q~i.',
    );
defined('LOGGED_IN_SALT') or
    define(
        'LOGGED_IN_SALT',
        'xJjOt.L5HT^#y? Yw3-942/6oM)!(X#9FrJvd!vnlGn(R8DBsY7l+,dvoi~`WRE}',
    );
defined('NONCE_SALT') or
    define(
        'NONCE_SALT',
        '1UFX#xybm%8Ob5RXUOD&rY6Bv72(0J0*!$X#`Gzf))PT!Je0qMbG5+2anG!X6l{[',
    );

/**
 * Environment-aware settings. Be creative, but avoid to set sensitive settings here.
 */
$environment = getenv('WORDPRESS_ENV');
switch ($environment) {
    case 'development':
        defined('WP_DEBUG') or define('WP_DEBUG', true);
        defined('WP_DEBUG_DISPLAY') or define('WP_DEBUG_DISPLAY', true);
        defined('WP_DEBUG_LOG') or define('WP_DEBUG_LOG', false);
        defined('SAVEQUERIES') or define('SAVEQUERIES', true);
        defined('SCRIPT_DEBUG') or define('SCRIPT_DEBUG', true);
        break;

    case 'staging':
        defined('WP_DEBUG') or define('WP_DEBUG', true);
        defined('WP_DEBUG_DISPLAY') or define('WP_DEBUG_DISPLAY', false);
        defined('WP_DEBUG_LOG') or define('WP_DEBUG_LOG', true);
        defined('SCRIPT_DEBUG') or define('SCRIPT_DEBUG', true);
        break;

    case 'production':
    default:
        defined('WP_DEBUG') or define('WP_DEBUG', false);
        defined('WP_DEBUG_DISPLAY') or define('WP_DEBUG_DISPLAY', false);
        defined('WP_DEBUG_LOG') or define('WP_DEBUG_LOG', false);
        defined('SCRIPT_DEBUG') or define('SCRIPT_DEBUG', false);
        break;
}

/**
 * Fix HTTPS behind load balancers.
 *
 * @link https://core.trac.wordpress.org/ticket/31288
 */
if (
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    'https' === strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'])
) {
    $_SERVER['HTTPS'] = 'on';
}

/**
 * Set WordPress paths and URLs if not set via environment variables.
 */
if (!defined('WP_HOME')) {
    $server = filter_input_array(INPUT_SERVER, [
        'HTTPS' => FILTER_SANITIZE_STRING,
        'SERVER_PORT' => FILTER_SANITIZE_NUMBER_INT,
        'SERVER_NAME' => FILTER_SANITIZE_URL,
    ]);
    $secure = in_array((string) $server['HTTPS'], ['on', '1'], true);
    $scheme = $secure ? 'https://' : 'http://';
    $name = $server['SERVER_NAME'] ?: 'localhost';
    define('WP_HOME', $scheme . $name);
}

defined('ABSPATH') or
    define('ABSPATH', realpath(dirname(__DIR__) . '/public/wordpress'));
defined('WP_SITEURL') or
    define('WP_SITEURL', rtrim(WP_HOME, '/') . '/wordpress');

/**
 * Define content constants only if needed, or network install screen will complain for no reason
 */
//$custom_content_dir =
//    realpath(dirname(__DIR__) . '/public/wp-content') !==
//    realpath(ABSPATH . '/wp-content');
//if ($custom_content_dir && !defined('WP_CONTENT_DIR')) {
//    define('WP_CONTENT_DIR', realpath(dirname(__DIR__) . '/public/wp-content'));
//}
//if (
//    $custom_content_dir &&
//    !(defined('MULTISITE') && MULTISITE) &&
//    !defined('WP_CONTENT_URL')
//) {
//    define('WP_CONTENT_URL', rtrim(WP_HOME, '/') . '/wp-content');
//}

/**
 * Remove theme file editing rights from all users
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Clean up.
 */
unset(
    $env,
    $environment,
    $server,
    $secure,
    $scheme,
    $name,
    $custom_content_dir,
);

/**
 * Allows to load MU plugins in subfolders.
 */
WCM\WPStarter\Helpers::addHook(
    'muplugins_loaded',
    new WCM\WPStarter\MuLoader\MuLoader(),
    PHP_INT_MAX,
    0,
);

/**
 * Register default themes inside WordPress package wp-content folder.
 */
WCM\WPStarter\Helpers::addHook(
    'plugins_loaded',
    function () {
        WCM\WPStarter\Helpers::loadThemeFolder(true);
    },
    0,
);

/**
 * Sets up WordPress vars and included files.
 */
require_once ABSPATH . '/wp-settings.php';
