<?php

namespace Publisher;

use \attitude\Elements\HTTPException;
use \attitude\Elements\DependencyContainer;
use \attitude\Elements\Boot_Element;
use \attitude\Elements\Request_Element;

use \attitude\FlatYAMLDB\ContentDB_Element;
use \attitude\FlatYAMLDB\TranslationsDB_Element;
use \attitude\Mustache\DataPreprocessor_Component;
use \attitude\Mustache\AtomicLoader_FilesystemLoader;
use \attitude\Mustache\AtomicLoader_AssetsConcatenator;

/**
 * This class is ment as an example
 */
class Default_Engine
{
    /**
     * @var object $db Database
     */
    protected $db;

    /**
     * @var object $request Request
     */
    protected $request;

    /**
     * @var string $requestURI Requested URI
     */
    protected $requestURI;

    /**
     * @var string $request_language Requested language locale/code
     */
    protected $request_language;

    /**
     * @var array $default_language Default language data
     */
    protected $default_language;

    /**
     * @var array $languages Array of available languages
     */
    protected $languages;

    /**
     * @var array $language Current language
     */
    protected $language;

    /**
     * @var object $translations_service Translations service
     */
    protected $translations_service;

    /**
     * @var float $microtime_start Start time for benchmarking
     */
    protected $microtime_start;

    /**
     * @var object $html_engine HTML rendering engine
     */
    protected $html_engine;

    public function __construct(Request_Element $request)
    {
        $this->microtime_start = microtime(true);

        if (!defined('BOOT_HAS_PASSED') || !(defined('BOOT_HAS_PASSED') && BOOT_HAS_PASSED)) {
            // Boot sequence and checks
            DependencyContainer::set('global::boot', Boot_Element::instance());

            exit;
        }

        $this->request = $request;

        try {
            $this
                ->boot($request)
                ->setDatabase()
                ->setDefaultLanguage()
                ->setAvailableLanguages()
                ->setRequestedLanguage()
                ->setTranslationsService()
                ->setRenderingEngine();
        } catch (HTTPException $e) {
            echo $e->getMessage();
            exit;
        }

        return $this;
    }

    protected function boot(Request_Element $request)
    {
        $this->request = $request;

        // Explode the request URI for LANGUAGE
        $requestURI = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->request_language = array_shift($requestURI);
        $this->requestURI = '/'.implode('/', $requestURI);

        return $this;
    }

    public function serve()
    {
        // Collection lookup
        try {
            $collection = DependencyContainer::get('global::db')->getCollection($this->requestURI);

            // Push current language info to the data
            $collection['meta']['language'] = $this->language;
            $collection['meta']['homeURL']  = $this->request->getHostLocation();

            $contentFilterHook = DependencyContainer::get('global::contentFilterHook', null);

            if (is_callable($contentFilterHook)) {
                $collection = $contentFilterHook->__invoke($collection);
            }

            $html = $this->html_engine->render($collection, $this->language['id']);

            $concatenator = DependencyContainer::get('global::assetsConcantenator');
            $concatenator->active = isset($_GET['combine-assets']) && $_GET['combine-assets']==='false' ? false : true;

            // If minification is specified, overrides any `combine-assets`
            if (isset($_GET['minify-assets'])) {
                $concatenator->minify = $_GET['minify-assets']==='false' ? false : true;
            } elseif (isset($_GET['combine-assets'])) {
                $concatenator->minify = $_GET['combine-assets']==='false' ? false : true;
            }

            $html = $concatenator->defaultConcatenateAssets($html);

            if (defined('MICROTIMEBENCHMARK') && MICROTIMEBENCHMARK===true) {
                $microtime = microtime(true) - $this->microtime_start;

                echo $html."\n<!-- Generated in ".$microtime." sec.-->";
            } else {
                echo $html;
            }
        } catch (HTTPException $e) {
            $e->header();
            echo $e->getMessage();
        }

        // Update translation database with new strings
        $this->translations_service->updateDictionary();

        return $this;
    }

    protected function setDatabase()
    {
        $this->db = new ContentDB_Element(
            DependencyContainer::get('global::contentDBFiles'),
            DependencyContainer::get('global::contentDBIndexes'),
            DependencyContainer::get('global::contentDBRoot'),
            DependencyContainer::get('global::contentDBNocache')
        );

        // Set db
        DependencyContainer::set('global::db', $this->db);

        return $this;
    }

    protected function setDefaultLanguage()
    {
        // Get default language
        try {
            $default_language = $this->db->query(array('type' => 'languages', 'default' => true));
            $this->default_language = $default_language[0];
        } catch(HTTPException $e) {
            $e->header();
            echo 'Please define a default language in database.';

            exit;
        }

        return $this;
    }

    protected function setAvailableLanguages()
    {
        // Get all languages
        $all_languages_args = $this->is_user_logged_in() ?
            array('type'=>'languages')
          : array('type'=>'languages', 'published' => true);

        try {
            $this->languages = $this->db->query($all_languages_args);
            DependencyContainer::set('global::languages', $this->languages);
        } catch(HTTPException $e) {
            $e->header();
            echo 'There are no languages defined in database.';

            exit;
        }

        return $this;
    }

    protected function setRequestedLanguage()
    {
        // Requested Language
        $language_exists_args = $this->is_user_logged_in() ?
            array('type'=>'languages', 'code' => $this->request_language, '_limit' => 1)
          : array('type'=>'languages', 'code' => $this->request_language, '_limit' => 1, 'published' => true);

        try {
            $this->language = $this->db->query($language_exists_args, true);

            DependencyContainer::set('global::language', $this->language);
            DependencyContainer::set('global::language.locale', $this->language['id']);
        } catch(HTTPException $e) {
            $status = new HTTPException(301);
            $status->header();

            $prefered_language = $this->getPreferedLanguage();

            $status = new HTTPException(301);
            $status->header();

            // Add http://...com
            $location = DependencyContainer::get('global::request')->getHostLocation();

            // Add language
            if ($prefered_language===null) {
                $location.= '/'.$this->default_language['code'];
                header('X-Redirected-By-Accept-Language: false');
            } else {
                $location.= '/'.$prefered_language['code'];
                header('X-Redirected-By-Accept-Language: true');
            }

            // Add full or part of the REQUEST_URI
            $location.=
                strlen($this->request_language) > 5 // first word is longer than expected
            || !preg_match(DependencyContainer::get('global::languageRegex'), $this->request_language, $devnull) // first word is not a lang code
                ?
                ($this->request_language ? '/'. $this->request_language : '') . $this->requestURI
                :
                $this->requestURI;

            // Redirect
            header('Location: '. $location);

            exit;
        }

        return $this;
    }

    protected function setTranslationsService()
    {
        $this->translations_service = new TranslationsDB_Element(
            DependencyContainer::get('global::translationsDBFile'),
            DependencyContainer::get('global::translationsDBIndexes'),
            DependencyContainer::get('global::translationsDBRoot'),
            DependencyContainer::get('global::translationsDBNocache')
        );

        // Set service it to be available
        DependencyContainer::set('global.i18n.service', $this->translations_service);

        // Set the translation method
        DependencyContainer::set('i18l::translate', function($one, $other='', $count=0, $offset=0) {
            return DependencyContainer::get('global.i18n.service')->translate($one, $other, $count, $offset);
        });

        return $this;
    }

    protected function setRenderingEngine()
    {
        $mustache_cache_dir_path = DependencyContainer::get('global::mustacheCachePath');

        // Create cache dir if it doeas not exist
        if (!file_exists($mustache_cache_dir_path)) {
            mkdir($mustache_cache_dir_path, 0755, true);
        }

        try {
            $this->html_engine = DataPreprocessor_Component::instance();
        } catch (HTTPException $e) {
            $e->header();
            echo $e->getMessage();

            exit;
        }

        return $this;
    }

    protected function is_user_logged_in()
    {
        return false;
    }

    protected function getPreferedLanguage()
    {
        static $prefered_language = false;

        if ($prefered_language===false) {

            // User prefers language
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $_prefered_languages = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

                foreach($_prefered_languages as $prefered_language) {
                    $prefered_language = explode(';', $prefered_language);

                    if (sizeof($prefered_language)===1) {
                        $prefered_language[] = 'q=1.0';
                    }

                    $prefered_languages[ 1000 * (float) ltrim($prefered_language[1], 'q=') ] = $prefered_language[0];
                }

                krsort($prefered_languages);

                $prefered_language = null;

                // Look for matches
                foreach ($prefered_languages as $prefered_language_code) {
                    if ($prefered_language) {
                        break;
                    }

                    foreach ((array) $this->languages as $language) {
                        if ($language['code'] === $prefered_language_code
                         || $language['id'][str_replace('-', '_', $prefered_language_code)] === $prefered_language
                        ) {
                            $prefered_language = $language;

                            break;
                        }
                    }
                }
            }
        }

        return $prefered_language;
    }
}
