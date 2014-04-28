<?php

/**
 * Default directory structure used in this example:
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
 *
 */

// Set Publisher directories:
// ! IMPORTANT: WWW_ROOT_DIR must match ROOT_DIR/.htaccess
//              that has been created for you for the first time you loaded
//              Publisher.
define('WWW_ROOT_DIR',  dirname(__FILE__));
define('APP_ROOT_DIR',  dirname(WWW_ROOT_DIR));
define('APPS_ROOT_DIR', dirname(APP_ROOT_DIR));
define('ROOT_DIR',      dirname(APPS_ROOT_DIR));

require_once APP_ROOT_DIR.'/app.php';
