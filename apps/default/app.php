<?php

/**
 * Default directory structure used in this example:
 *
 * Assets in subdirectory (v1) or on cookieless static sudomain (v2):
 *
 * ├ .                    # hosting root
 * ├ static/         (v2) # ASSETS_ROOT_DIR - coockieless static subdomain to serve static assets like CSS, javaScript and images
 * └ www/                 # ROOT_DIR - Publisher repository
 *   ├ .htaccess          # created after running install on www.example.com/index.php
 *   └ apps/              # APPS_ROOT_DIR - all apps directory
 *     └ default/         # APP_ROOT_DIR - current app directory (this file's directory)
 *       └ public/        # WWW_ROOT_DIR - public directory accessible through http://www.example.com/
 *         ├ assets/ (v1) # ASSETS_ROOT_DIR - public's subdirectory next to this file
 *         ├ templates/   # mutache templates/partials
 *         └ index.php    # what is loaded when called www.example.com (installed)
 */

/* Uncommenting sets string with the name of the static subdomain like 'static'
 * to save/link to http: *static.example.com
 *
 * Different hosting directory structure might apply to your case...
 *
 * Info: I can assume only that when you run www.domain.com or domain.com
 *       If you run this site from sub.domain.com, propably, you do not have
 *       access to static.domain.com. If you do have, however, the static
 *       subdomain setup is up to you. Delete this logic and replace it with
 *       your own setup.
 *
 *       This code is just to give a quick start for defaults.
 */
// $as_static_subdomain = 'static'; // Uncomment to enable static.domain.com

/**
 * Check for HTTPS scheme
 *
 * Returns true if HTTPS is on
 * @param void
 * @return boolean
 *
 */
if (!function_exists('is_https')) {
    function is_https() {
        if (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTPPS'])=== 'on') {
            return true;
        }

        if (isset($_SERVER['SCHEME']) && strtolower($_SERVER['SCHEME'])==='https') {
            return true;
        }

        if (isset($_SERVER['REQUEST_SCHEME']) && strtolower($_SERVER['REQUEST_SCHEME'])==='https') {
            return true;
        }

        return false;
    }
}

// Endpoint
$_SERVER['REQUEST_ENDPOINT'] = '/publisher';

if (isset($_SERVER['REQUEST_ENDPOINT'])) {
    $_SERVER['REQUEST_URI'] = preg_replace('|^/'.trim($_SERVER['REQUEST_ENDPOINT'], '/').'|', '', $_SERVER['REQUEST_URI']);
}

// On subdomain (sibling next to Publisher):
if (isset($as_static_subdomain) && $as_static_subdomain) {
    // Set absolut path to assets
    define('ASSETS_ROOT_DIR', dirname(ROOT_DIR).'/static');

    // Set URL for assets
    $nowww_domain = explode('.', $_SERVER['HTTP_HOST']);

    // Allow .dev suffix to support testing on local virtual host without
    // breaking DNS to original domain:
    if ($nowww_domain[(count($nowww_domain)-1)]==='dev') {
        // Merge with the domain
        array_pop($nowww_domain);
        $nowww_domain[(count($nowww_domain)-1)].= '.dev';
    }

    // As static domai only for www.domain.com and domain.com
    if (count($nowww_domain)===2 || count($nowww_domain)===3) {
        // Trim first
        $nowww_domain = $nowww_domain[(count($nowww_domain)-2)].'.'.$nowww_domain[(count($nowww_domain)-1)];

        // For those times when not set correctly
        if (!is_string($as_static_subdomain) || is_bool($as_static_subdomain) || trim($as_static_subdomain)==='') {
            exit('You need to set the static domain as a&nbsp;string like <b>static</b> to build domain name like <b>static.'.$nowww_domain.'</b>.');
        }

        define('ASSETS_URL', 'http'. (is_https() ? 's' : '').'://'.$as_static_subdomain.'.'.$nowww_domain.(isset($_SERVER['REQUEST_ENDPOINT']) ? '/'.trim($_SERVER['REQUEST_ENDPOINT'], '/') : '').'/');
    } else {
        // Write note for developer to the log
        trigger_error('The static domain setup is available only for level 2 doamins, e.g. domain.com and www.domain.com. Please setup manually.');
        $nowww_domain = implode('.', $nowww_domain);
    }
} else {
    // Set absolut path to assets
    define('ASSETS_ROOT_DIR', WWW_ROOT_DIR.'/assets');

    // Set URL for assets
    define('ASSETS_URL', 'http'. (is_https() ? 's' : '').'://'.$_SERVER['HTTP_HOST'].(isset($_SERVER['REQUEST_ENDPOINT']) ? '/'.trim($_SERVER['REQUEST_ENDPOINT'], '/') : '').'/assets');
}

// Make sure assets directory exists
if (!file_exists(ASSETS_ROOT_DIR)) {
    mkdir(ASSETS_ROOT_DIR, 0755, true);
}

// Composer autoloader
require_once ROOT_DIR.'/vendor/autoload.php';

// Set to `true` to write time benchmark as comment after `</html>`
define('MICROTIMEBENCHMARK', false);

// Configure
require_once APP_ROOT_DIR.'/config.php';

// Server request
$server->serve();
