<?php

use \attitude\Elements\HTTPException;
use \attitude\Elements\DependencyContainer;
use \attitude\Elements\Boot_Element;
use \attitude\Elements\Request_Element;

use \attitude\FlatYAMLDB\ContentDB_Element;
use \attitude\FlatYAMLDB\TranslationsDB_Element;
use \attitude\Mustache\DataPreprocessor_Component;
use \attitude\Mustache\AtomicLoader_FilesystemLoader;
use \attitude\Mustache\AtomicLoader_AssetsConcatenator;

// Identify language attributes for data translations
DependencyContainer::set('global::languageRegex', '/^(?:[a-z]{2}|[a-z]{2}_[A-Z]{2})$/');

// Content database
// Yaml file source
DependencyContainer::set('global::contentDBFile', APP_ROOT_DIR.'/db/content.yaml');
// Set by what attribute to query DB
DependencyContainer::set(
    'global::contentDBIndexes',
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
    )
);
// To use cache set false, to refresh on each load set true (e.g. for debugging)
DependencyContainer::set('global::contentDBNocache', false);

// Translations database
DependencyContainer::set('global::translationsDBFile', APP_ROOT_DIR.'/db/translations.yaml');
DependencyContainer::set('global::translationsDBIndexes', array());
DependencyContainer::set('global::translationsDBNocache', false);

// Money setup
// @TODO: use current language to provide settings
DependencyContainer::set('money::currency_prefix', false);
DependencyContainer::set('money::dec_point', ',');

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
    'content' => function($args) {
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

// Set pluralisation logic for Slovak (and Czech also)
// English rules is used by default
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
// Czech has same rules
DependencyContainer::set('global::language.pluralRules.csSelect', DependencyContainer::get('global::language.pluralRules.skSelect'));

// Templating
$loader_args = array(
    'publicDir' => WWW_ROOT_DIR,
    'publicURL' => 'http'. (isset($_SERVER['SCHEME']) && $_SERVER['SCHEME']==='HTTPS' ? 's' : '').'://'.$_SERVER['HTTP_HOST'].'/',
    'assets' => AtomicLoader_FilesystemLoader::getAssetDefaults()
);

// Cache path
DependencyContainer::set('global::mustacheCachePath',    ROOT_DIR.'/cache/mustache');
DependencyContainer::set('global::mustacheViews',    new AtomicLoader_FilesystemLoader(WWW_ROOT_DIR.'/templates/views', $loader_args));
DependencyContainer::set('global::mustachePartials', new AtomicLoader_FilesystemLoader(WWW_ROOT_DIR.'/templates', $loader_args));

// More Mustache helpers
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

// Assets concatenation
$concatenation_args = $loader_args;
$concatenation_args['publicStaticDir'] = ASSETS_ROOT_DIR;
$concatenation_args['publicStaticURL'] = ASSETS_URL;

DependencyContainer::set('global::assetsConcantenator', new AtomicLoader_AssetsConcatenator(WWW_ROOT_DIR, $concatenation_args));
