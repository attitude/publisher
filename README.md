Publisher
=========

A multilanguage website generator inspired by Squarespace

Installation
---

Download [latest release][releases] or clone this repository and use [Composer][].
Hit `/index.php` and follow on-screen instructions.

Structure
---

Refer to `/apps/default/app.php` (and `/apps/default/public/index.php`) inline
comments for setting up assets path and URL.

- v1: Assets in subdirectory (default)
- v2: Assets on static subdomain (prepared, needs tweaks)

```
Assets in subdirectory (v1) or on cookieless static sudomain (v2):
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
```

Start your app by copying the default app, running installer again (just delete .htacess).

See `/apps/default/app.php` and set:

```php
$as_static_subdomain = true;
```

What it does
---

- Parses Macaw HTML export from enhanced markup
  - [Tutorial](http://www.martinadamko.sk/?p=152) & [Example App](https://github.com/attitude/publisher-macaw-example)
  - [Introduction](http://www.martinadamko.sk/?p=145)
- Content translation
- Template translation with pluralisation similar to i18n & l10n of AngularJS
- Atomic Design principle of templates, CSS and javaScript as small componenets
- Automatic assets inclusion & concatenation
- Protecting e-mail using Hivelogic Enkoder
- Flexible YAML database
- Enhanced Mustache templates
- Markdow filter,

###### See core components for more insights:

- [Mustache Data Preprocessor](https://github.com/attitude/mustache-data-preprocessor)
- [Mustache Atomic Loader](https://github.com/attitude/mustache-atomic-loader)
  - FilesystemLoader
  - MacawLoader
- [Flat YAML DB](https://github.com/attitude/flat-yaml-db)

Under development
---

Pulisher is about to be tested on few small projects to see how it performs.
Page load on laptop without Memcached: cca. **1/5 of second**, which is quite
decent but fairly proximate.

Let me know how you like it.

Enjoy!

[@martin_adamko](http://twitter.com/martin_adamko)

[releases]: https://github.com/attitude/publisher/releases/latest
[Composer]: https://getcomposer.org/
