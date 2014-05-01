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

// Set as true to save/link to http://static.example.com
// Note that different hosting directory structure might apply to your case.
$as_static_subdomain = false;

// On subdomain (sibling next to Publisher):
if ($as_static_subdomain) {
    // Set absolut path to assets
    define('ASSETS_ROOT_DIR', dirname(ROOT_DIR).'/static');

    // Set URL for assets
    $nowww_domain = explode('.', $_SERVER['HTTP_HOST']);
    $nowww_domain = $nowww_domain[(count($nowww_domain)-2)].'.'.$nowww_domain[(count($nowww_domain)-1)];
    define('ASSETS_URL',
        'http'. (isset($_SERVER['SCHEME']) && $_SERVER['SCHEME']==='HTTPS' ? 's' : '').
        '://static.'.$nowww_domain.'/'
    );
} else {
    // Set absolut path to assets
    define('ASSETS_ROOT_DIR', WWW_ROOT_DIR.'/assets');

    // Set URL for assets
    define('ASSETS_URL',
        'http'. (isset($_SERVER['SCHEME']) && $_SERVER['SCHEME']==='HTTPS' ? 's' : '').
        '://'.$_SERVER['HTTP_HOST'].'/assets'
    );
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

// Serve request
$publisher = new \Publisher\Default_Engine();
$publisher->serve();
