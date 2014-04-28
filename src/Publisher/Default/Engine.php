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

    public function __construct()
    {
        $this->microtime_start = microtime(true);

        $this
            ->boot()
            ->setDatabase()
            ->setDefaultLanguage()
            ->setAvailableLanguages()
            ->setRequestedLanguage()
            ->setTranslationsService()
            ->setRenderingEngine();

        return $this;
    }

    public function serve()
    {
        // Collection lookup
        try {
            $collection = DependencyContainer::get('global::db')->getCollection($this->requestURI);

            $html = $this->html_engine->render($collection, $this->language['_id']);

            $concatenation_args = array(
                'publicDir' => WWW_ROOT_DIR,
                'publicURL' => '/',
                'publicStaticDir' => ASSETS_ROOT_DIR,
                'publicStaticURL' => ASSETS_URL,
                'assets' => AtomicLoader_FilesystemLoader::getAssetDefaults()
            );

            $concatenator = new AtomicLoader_AssetsConcatenator(WWW_ROOT_DIR, $concatenation_args);
            $concatenator->active = isset($_GET['combine-assets']) && $_GET['combine-assets']==='false' ? false : true;

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

    protected function boot()
    {
        DependencyContainer::set('global::languageRegex', '/^(?:[a-z]{2}|[a-z]{2}_[A-Z]{2})$/');
        DependencyContainer::set('global::boot', Boot_Element::instance());
        DependencyContainer::set('global::request',  new Request_Element(
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI_ARRAY'],
            $_SERVER['argv'],
            $_SERVER['HTTP_ACCEPT'],
            $GLOBALS['_'.$_SERVER['REQUEST_METHOD']]
        ));

        // Explode the request URI for LANGUAGE
        $requestURI = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->request_language = array_shift($requestURI);
        $this->requestURI = '/'.implode('/', $requestURI);

        return $this;
    }

    protected function setDatabase()
    {
        $this->db = new ContentDB_Element(
            APP_ROOT_DIR.'/db/content.yaml',
            array(
                '_id',
                '_collection',
                '_type',
                'code',
                'default',
                'name',
                'published',
                'route',
                'subtitle',
                'tags',
                'title',
            ),
            true
        );

        // Set db
        DependencyContainer::set('global::db', $this->db);

        // Set expanders
        DependencyContainer::set('global::dataExpanders', array(
            'link' => function($args) {
                return DependencyContainer::get('global::db')->expanderLink($args);
            },
            'href' => function($args) {
                return DependencyContainer::get('global::db')->expanderHref($args);
            },
            'title' => function($args) {
                return DependencyContainer::get('global::db')->expanderTitle($args);
            },
            'query' => function($args) {
                return DependencyContainer::get('global::db')->expanderQuery($args);
            },
            'content' => function($args) { echo 'RUN';
                if (!isset($args[0])) {
                    throw new HTTPException(500, 'content() expander expects sequential array: `content(): [content_id_1, content_id_2, ...]');
                }

                $results = array();

                foreach ((array) $args as $_args) {
                    $_args          = array('_id' => $_args);
                    $_args['_type'] = 'content';
                    try {
                        $results[] = DependencyContainer::get('global::db')->expanderQuery($_args);
                    } catch (HTTPException $e) {}
                }

                return $results;
            },
            'lastMonths' => function($args) {
                $months = array();
                $count  = isset($args['count']) ? (int) $args['count'] : 4;
                $locale = DependencyContainer::get('global::language.locale');

                // Set locale for time
                setlocale(LC_TIME, $locale);

                $time = time();

                $this_month = (int) date('n', $time);
                $this_year = (int) date('Y', $time);

                $time = time(0, 0, 0, $this_month, 1, $this_year);

                while (count($months) < $count) {
                    $months[] = array(
                        'fullName' => strftime('%B', $time),
                        'shortName' => strftime('%b', $time),
                        'order' => $this_month
                    );

                    $this_month--;
                    if ($this_month===0) {
                        $this_month = 12;
                        $this_year--;
                    }

                    $time = mktime(0, 0, 0, $this_month, 1, $this_year);
                }


                return array_reverse($months);
            },
            'copyrightYear' => function ($args) {
                $Y = date('Y');

                if (isset($args['since']) && $Y > $args['since']) {
                    return $Y;
                }

                return false;
            }
        ));

        return $this;
    }

    protected function setDefaultLanguage()
    {
        // Get default language
        try {
            $default_language = $this->db->query(array('_type' => 'language', 'default' => true));
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
            array('_type'=>'language')
          : array('_type'=>'language', 'published' => true);

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
            array('_type'=>'language', 'code' => $this->request_language, '_limit' => 1)
          : array('_type'=>'language', 'code' => $this->request_language, '_limit' => 1, 'published' => true);

        try {
            $this->language = $this->db->query($language_exists_args, true);

            DependencyContainer::set('global::language', $this->language);
            DependencyContainer::set('global::language.locale', $this->language['_id']);
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

            header('Location: '. $location);

            exit;
        }

        return $this;
    }

    protected function setTranslationsService()
    {
        $this->translations_service = new TranslationsDB_Element(
            APP_ROOT_DIR.'/db/translations.yaml',
            array(),
            true
        );

        DependencyContainer::set('global.i18n.service', $this->translations_service);
        DependencyContainer::set('money::currency_prefix', false);
        DependencyContainer::set('money::dec_point', ',');

        DependencyContainer::set('global::language.pluralRules.skSelect', function($n) {
            // Change 1,25 to 1.25 which is understood correctly as float
            if (is_string($n)) {
                $n = str_replace(',','.',$n);
            }

            if (intval($n) != $n) {
                return TranslationsDB_Element::FRACTION;
            }

            if ($n == 1) {
                return TranslationsDB_Element::ONE;
            }

            if ($n == ($n | 0) && $n >= 2 && $n <= 4) {
                return TranslationsDB_Element::FEW;
            }

            return TranslationsDB_Element::OTHER;
        });

        DependencyContainer::set('i18l::translate', function($one, $other='', $count=0, $offset=0) {
            return DependencyContainer::get('global.i18n.service')->translate($one, $other, $count, $offset);
        });

        return $this;
    }

    protected function setRenderingEngine()
    {
        $loader_args = array(
            'publicDir' => WWW_ROOT_DIR,
            'publicURL' => '/',
            'assets' => AtomicLoader_FilesystemLoader::getAssetDefaults()
        );

        $mustache_cache_dir_path = ROOT_DIR.'/cache/mustache';

        // Create cache dir if it doeas not exist
        if (!file_exists($mustache_cache_dir_path)) {
            mkdir($mustache_cache_dir_path, 0755, true);
        }

        DependencyContainer::set('global::mustacheCachePath',    $mustache_cache_dir_path);
        DependencyContainer::set('global::mustacheViews',    new AtomicLoader_FilesystemLoader(WWW_ROOT_DIR.'/templates/views', $loader_args));
        DependencyContainer::set('global::mustachePartials', new AtomicLoader_FilesystemLoader(WWW_ROOT_DIR.'/templates', $loader_args));

        DependencyContainer::set('global::markdownParser', new \Parsedown());
        DependencyContainer::set('global::mustacheHelpers', array(
            'markdown' => function($str) {
                return DependencyContainer::get('global::markdownParser')->parse($str);
            },
            'csslinebreaks' => function($str) {
                $lines = explode('<br>', preg_replace('/<br.*?\/?>/', '<br>', $str));

                foreach ($lines as $i => &$line) {
                    $line = '<span class="csslinebreak csslinebreak--'.($i+1).'">'.$line.'</span>';
                }

                return implode('', $lines);
            }
        ));

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
                         || $language['_id'][str_replace('-', '_', $prefered_language_code)] === $prefered_language
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
