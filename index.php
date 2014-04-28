<?php
define('ROOT_DIR', dirname(__FILE__));

$vendors_installed = false;
$htaccess_exists = false;
$bad_input = false;
$installed = false;
$failed_to_write = false;

if (file_exists(ROOT_DIR.'/vendor')) {
    $vendors_installed = true;
}

$htaccess_tempalte_path = ROOT_DIR.'/htaccess.txt';
$htaccess_path = ROOT_DIR.'/.htaccess';

if (file_exists($htaccess_tempalte_path)) {
    $htaccess_exists = true;
}

if ($htaccess_exists && $vendors_installed && isset($_POST['app'])) {
    $appname = preg_replace('|[^a-zA-Z0-9-_]+|', '',  trim($_POST['app']));

    if ($appname!==$_POST['app'] || strlen(trim($appname))===0) {
        $bad_input = true;
    } else {
        // Valid input
        $htaccess = str_replace('{{appname}}', $appname, file_get_contents($htaccess_tempalte_path));
        $failed_to_write = ! file_put_contents($htaccess_path, $htaccess);

        $installed = true;
    }
}

// Simple CSS preprocessor
$css_brand_color = '#499';
$css_brand_color_lighter = '#cff';

$css_rhythm = 24;

function em($px, $base=16)
{
    return number_format($px/$base, 3, '.', '').'em';
}

?><!doctype html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Installation | Publisher</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style type="text/css">/*! normalize.css v3.0.1 | MIT License | git.io/normalize */html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;}body{margin:0;}article,aside,details,figcaption,figure,footer,header,hgroup,main,nav,section,summary{display:block;}audio,canvas,progress,video{display:inline-block;vertical-align:baseline;}audio:not([controls]){display:none;height:0;}[hidden],template{display:none;}a{background:transparent;}a:active,a:hover{outline:0;}abbr[title]{border-bottom:1px dotted;}b,strong{font-weight:bold;}dfn{font-style:italic;}h1{font-size:2em;margin:0.67em 0;}mark{background:#ff0;color:#000;}small{font-size:80%;}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline;}sup{top:-0.5em;}sub{bottom:-0.25em;}img{border:0;}svg:not(:root){overflow:hidden;}figure{margin:1em 40px;}hr{-moz-box-sizing:content-box;box-sizing:content-box;height:0;}pre{overflow:auto;}code,kbd,pre,samp{font-family:monospace,monospace;font-size:1em;}button,input,optgroup,select,textarea{color:inherit;font:inherit;margin:0;}button{overflow:visible;}button,select{text-transform:none;}button,html input[type="button"],input[type="reset"],input[type="submit"]{-webkit-appearance:button;cursor:pointer;}button[disabled],html input[disabled]{cursor:default;}button::-moz-focus-inner,input::-moz-focus-inner{border:0;padding:0;}input{line-height:normal;}input[type="checkbox"],input[type="radio"]{box-sizing:border-box;padding:0;}input[type="number"]::-webkit-inner-spin-button,input[type="number"]::-webkit-outer-spin-button{height:auto;}input[type="search"]{-webkit-appearance:textfield;-moz-box-sizing:content-box;-webkit-box-sizing:content-box;box-sizing:content-box;}input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration{-webkit-appearance:none;}fieldset{border:1px solid #c0c0c0;margin:0 2px;padding:0.35em 0.625em 0.75em;}legend{border:0;padding:0;}textarea{overflow:auto;}optgroup{font-weight:bold;}table{border-collapse:collapse;border-spacing:0;}td,th{padding:0;}</style>
        <link href='http://fonts.googleapis.com/css?family=Cutive+Mono' rel='stylesheet' type='text/css'>
        <script type="text/javascript">/* Modernizr 2.7.1 (Custom Build) | MIT & BSD */;window.Modernizr=function(a,b,c){function D(a){j.cssText=a}function E(a,b){return D(n.join(a+";")+(b||""))}function F(a,b){return typeof a===b}function G(a,b){return!!~(""+a).indexOf(b)}function H(a,b){for(var d in a){var e=a[d];if(!G(e,"-")&&j[e]!==c)return b=="pfx"?e:!0}return!1}function I(a,b,d){for(var e in a){var f=b[a[e]];if(f!==c)return d===!1?a[e]:F(f,"function")?f.bind(d||b):f}return!1}function J(a,b,c){var d=a.charAt(0).toUpperCase()+a.slice(1),e=(a+" "+p.join(d+" ")+d).split(" ");return F(b,"string")||F(b,"undefined")?H(e,b):(e=(a+" "+q.join(d+" ")+d).split(" "),I(e,b,c))}function K(){e.input=function(c){for(var d=0,e=c.length;d<e;d++)u[c[d]]=c[d]in k;return u.list&&(u.list=!!b.createElement("datalist")&&!!a.HTMLDataListElement),u}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),e.inputtypes=function(a){for(var d=0,e,f,h,i=a.length;d<i;d++)k.setAttribute("type",f=a[d]),e=k.type!=="text",e&&(k.value=l,k.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(f)&&k.style.WebkitAppearance!==c?(g.appendChild(k),h=b.defaultView,e=h.getComputedStyle&&h.getComputedStyle(k,null).WebkitAppearance!=="textfield"&&k.offsetHeight!==0,g.removeChild(k)):/^(search|tel)$/.test(f)||(/^(url|email)$/.test(f)?e=k.checkValidity&&k.checkValidity()===!1:e=k.value!=l)),t[a[d]]=!!e;return t}("search tel url email datetime date month week time datetime-local number range color".split(" "))}var d="2.7.1",e={},f=!0,g=b.documentElement,h="modernizr",i=b.createElement(h),j=i.style,k=b.createElement("input"),l=":)",m={}.toString,n=" -webkit- -moz- -o- -ms- ".split(" "),o="Webkit Moz O ms",p=o.split(" "),q=o.toLowerCase().split(" "),r={svg:"http://www.w3.org/2000/svg"},s={},t={},u={},v=[],w=v.slice,x,y=function(a,c,d,e){var f,i,j,k,l=b.createElement("div"),m=b.body,n=m||b.createElement("body");if(parseInt(d,10))while(d--)j=b.createElement("div"),j.id=e?e[d]:h+(d+1),l.appendChild(j);return f=["&#173;",'<style id="s',h,'">',a,"</style>"].join(""),l.id=h,(m?l:n).innerHTML+=f,n.appendChild(l),m||(n.style.background="",n.style.overflow="hidden",k=g.style.overflow,g.style.overflow="hidden",g.appendChild(n)),i=c(l,a),m?l.parentNode.removeChild(l):(n.parentNode.removeChild(n),g.style.overflow=k),!!i},z=function(b){var c=a.matchMedia||a.msMatchMedia;if(c)return c(b).matches;var d;return y("@media "+b+" { #"+h+" { position: absolute; } }",function(b){d=(a.getComputedStyle?getComputedStyle(b,null):b.currentStyle)["position"]=="absolute"}),d},A=function(){function d(d,e){e=e||b.createElement(a[d]||"div"),d="on"+d;var f=d in e;return f||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(d,""),f=F(e[d],"function"),F(e[d],"undefined")||(e[d]=c),e.removeAttribute(d))),e=null,f}var a={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return d}(),B={}.hasOwnProperty,C;!F(B,"undefined")&&!F(B.call,"undefined")?C=function(a,b){return B.call(a,b)}:C=function(a,b){return b in a&&F(a.constructor.prototype[b],"undefined")},Function.prototype.bind||(Function.prototype.bind=function(b){var c=this;if(typeof c!="function")throw new TypeError;var d=w.call(arguments,1),e=function(){if(this instanceof e){var a=function(){};a.prototype=c.prototype;var f=new a,g=c.apply(f,d.concat(w.call(arguments)));return Object(g)===g?g:f}return c.apply(b,d.concat(w.call(arguments)))};return e}),s.flexbox=function(){return J("flexWrap")},s.canvas=function(){var a=b.createElement("canvas");return!!a.getContext&&!!a.getContext("2d")},s.canvastext=function(){return!!e.canvas&&!!F(b.createElement("canvas").getContext("2d").fillText,"function")},s.webgl=function(){return!!a.WebGLRenderingContext},s.touch=function(){var c;return"ontouchstart"in a||a.DocumentTouch&&b instanceof DocumentTouch?c=!0:y(["@media (",n.join("touch-enabled),("),h,")","{#modernizr{top:9px;position:absolute}}"].join(""),function(a){c=a.offsetTop===9}),c},s.geolocation=function(){return"geolocation"in navigator},s.postmessage=function(){return!!a.postMessage},s.websqldatabase=function(){return!!a.openDatabase},s.indexedDB=function(){return!!J("indexedDB",a)},s.hashchange=function(){return A("hashchange",a)&&(b.documentMode===c||b.documentMode>7)},s.history=function(){return!!a.history&&!!history.pushState},s.draganddrop=function(){var a=b.createElement("div");return"draggable"in a||"ondragstart"in a&&"ondrop"in a},s.websockets=function(){return"WebSocket"in a||"MozWebSocket"in a},s.rgba=function(){return D("background-color:rgba(150,255,150,.5)"),G(j.backgroundColor,"rgba")},s.hsla=function(){return D("background-color:hsla(120,40%,100%,.5)"),G(j.backgroundColor,"rgba")||G(j.backgroundColor,"hsla")},s.multiplebgs=function(){return D("background:url(https://),url(https://),red url(https://)"),/(url\s*\(.*?){3}/.test(j.background)},s.backgroundsize=function(){return J("backgroundSize")},s.borderimage=function(){return J("borderImage")},s.borderradius=function(){return J("borderRadius")},s.boxshadow=function(){return J("boxShadow")},s.textshadow=function(){return b.createElement("div").style.textShadow===""},s.opacity=function(){return E("opacity:.55"),/^0.55$/.test(j.opacity)},s.cssanimations=function(){return J("animationName")},s.csscolumns=function(){return J("columnCount")},s.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";return D((a+"-webkit- ".split(" ").join(b+a)+n.join(c+a)).slice(0,-a.length)),G(j.backgroundImage,"gradient")},s.cssreflections=function(){return J("boxReflect")},s.csstransforms=function(){return!!J("transform")},s.csstransforms3d=function(){var a=!!J("perspective");return a&&"webkitPerspective"in g.style&&y("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",function(b,c){a=b.offsetLeft===9&&b.offsetHeight===3}),a},s.csstransitions=function(){return J("transition")},s.fontface=function(){var a;return y('@font-face {font-family:"font";src:url("https://")}',function(c,d){var e=b.getElementById("smodernizr"),f=e.sheet||e.styleSheet,g=f?f.cssRules&&f.cssRules[0]?f.cssRules[0].cssText:f.cssText||"":"";a=/src/i.test(g)&&g.indexOf(d.split(" ")[0])===0}),a},s.generatedcontent=function(){var a;return y(["#",h,"{font:0/0 a}#",h,':after{content:"',l,'";visibility:hidden;font:3px/1 a}'].join(""),function(b){a=b.offsetHeight>=3}),a},s.video=function(){var a=b.createElement("video"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"').replace(/^no$/,""),c.h264=a.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/,""),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/,"")}catch(d){}return c},s.audio=function(){var a=b.createElement("audio"),c=!1;try{if(c=!!a.canPlayType)c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/,""),c.mp3=a.canPlayType("audio/mpeg;").replace(/^no$/,""),c.wav=a.canPlayType('audio/wav; codecs="1"').replace(/^no$/,""),c.m4a=(a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;")).replace(/^no$/,"")}catch(d){}return c},s.localstorage=function(){try{return localStorage.setItem(h,h),localStorage.removeItem(h),!0}catch(a){return!1}},s.sessionstorage=function(){try{return sessionStorage.setItem(h,h),sessionStorage.removeItem(h),!0}catch(a){return!1}},s.webworkers=function(){return!!a.Worker},s.applicationcache=function(){return!!a.applicationCache},s.svg=function(){return!!b.createElementNS&&!!b.createElementNS(r.svg,"svg").createSVGRect},s.inlinesvg=function(){var a=b.createElement("div");return a.innerHTML="<svg/>",(a.firstChild&&a.firstChild.namespaceURI)==r.svg},s.smil=function(){return!!b.createElementNS&&/SVGAnimate/.test(m.call(b.createElementNS(r.svg,"animate")))},s.svgclippaths=function(){return!!b.createElementNS&&/SVGClipPath/.test(m.call(b.createElementNS(r.svg,"clipPath")))};for(var L in s)C(s,L)&&(x=L.toLowerCase(),e[x]=s[L](),v.push((e[x]?"":"no-")+x));return e.input||K(),e.addTest=function(a,b){if(typeof a=="object")for(var d in a)C(a,d)&&e.addTest(d,a[d]);else{a=a.toLowerCase();if(e[a]!==c)return e;b=typeof b=="function"?b():b,typeof f!="undefined"&&f&&(g.className+=" "+(b?"":"no-")+a),e[a]=b}return e},D(""),i=k=null,function(a,b){function l(a,b){var c=a.createElement("p"),d=a.getElementsByTagName("head")[0]||a.documentElement;return c.innerHTML="x<style>"+b+"</style>",d.insertBefore(c.lastChild,d.firstChild)}function m(){var a=s.elements;return typeof a=="string"?a.split(" "):a}function n(a){var b=j[a[h]];return b||(b={},i++,a[h]=i,j[i]=b),b}function o(a,c,d){c||(c=b);if(k)return c.createElement(a);d||(d=n(c));var g;return d.cache[a]?g=d.cache[a].cloneNode():f.test(a)?g=(d.cache[a]=d.createElem(a)).cloneNode():g=d.createElem(a),g.canHaveChildren&&!e.test(a)&&!g.tagUrn?d.frag.appendChild(g):g}function p(a,c){a||(a=b);if(k)return a.createDocumentFragment();c=c||n(a);var d=c.frag.cloneNode(),e=0,f=m(),g=f.length;for(;e<g;e++)d.createElement(f[e]);return d}function q(a,b){b.cache||(b.cache={},b.createElem=a.createElement,b.createFrag=a.createDocumentFragment,b.frag=b.createFrag()),a.createElement=function(c){return s.shivMethods?o(c,a,b):b.createElem(c)},a.createDocumentFragment=Function("h,f","return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&("+m().join().replace(/[\w\-]+/g,function(a){return b.createElem(a),b.frag.createElement(a),'c("'+a+'")'})+");return n}")(s,b.frag)}function r(a){a||(a=b);var c=n(a);return s.shivCSS&&!g&&!c.hasCSS&&(c.hasCSS=!!l(a,"article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}")),k||q(a,c),a}var c="3.7.0",d=a.html5||{},e=/^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,f=/^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,g,h="_html5shiv",i=0,j={},k;(function(){try{var a=b.createElement("a");a.innerHTML="<xyz></xyz>",g="hidden"in a,k=a.childNodes.length==1||function(){b.createElement("a");var a=b.createDocumentFragment();return typeof a.cloneNode=="undefined"||typeof a.createDocumentFragment=="undefined"||typeof a.createElement=="undefined"}()}catch(c){g=!0,k=!0}})();var s={elements:d.elements||"abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",version:c,shivCSS:d.shivCSS!==!1,supportsUnknownElements:k,shivMethods:d.shivMethods!==!1,type:"default",shivDocument:r,createElement:o,createDocumentFragment:p};a.html5=s,r(b)}(this,b),e._version=d,e._prefixes=n,e._domPrefixes=q,e._cssomPrefixes=p,e.mq=z,e.hasEvent=A,e.testProp=function(a){return H([a])},e.testAllProps=J,e.testStyles=y,e.prefixed=function(a,b,c){return b?J(a,b,c):J(a,"pfx")},g.className=g.className.replace(/(^|\s)no-js(\s|$)/,"$1$2")+(f?" js "+v.join(" "):""),e}(this,this.document),function(a,b,c){function d(a){return"[object Function]"==o.call(a)}function e(a){return"string"==typeof a}function f(){}function g(a){return!a||"loaded"==a||"complete"==a||"uninitialized"==a}function h(){var a=p.shift();q=1,a?a.t?m(function(){("c"==a.t?B.injectCss:B.injectJs)(a.s,0,a.a,a.x,a.e,1)},0):(a(),h()):q=0}function i(a,c,d,e,f,i,j){function k(b){if(!o&&g(l.readyState)&&(u.r=o=1,!q&&h(),l.onload=l.onreadystatechange=null,b)){"img"!=a&&m(function(){t.removeChild(l)},50);for(var d in y[c])y[c].hasOwnProperty(d)&&y[c][d].onload()}}var j=j||B.errorTimeout,l=b.createElement(a),o=0,r=0,u={t:d,s:c,e:f,a:i,x:j};1===y[c]&&(r=1,y[c]=[]),"object"==a?l.data=c:(l.src=c,l.type=a),l.width=l.height="0",l.onerror=l.onload=l.onreadystatechange=function(){k.call(this,r)},p.splice(e,0,u),"img"!=a&&(r||2===y[c]?(t.insertBefore(l,s?null:n),m(k,j)):y[c].push(l))}function j(a,b,c,d,f){return q=0,b=b||"j",e(a)?i("c"==b?v:u,a,b,this.i++,c,d,f):(p.splice(this.i++,0,a),1==p.length&&h()),this}function k(){var a=B;return a.loader={load:j,i:0},a}var l=b.documentElement,m=a.setTimeout,n=b.getElementsByTagName("script")[0],o={}.toString,p=[],q=0,r="MozAppearance"in l.style,s=r&&!!b.createRange().compareNode,t=s?l:n.parentNode,l=a.opera&&"[object Opera]"==o.call(a.opera),l=!!b.attachEvent&&!l,u=r?"object":l?"script":"img",v=l?"script":u,w=Array.isArray||function(a){return"[object Array]"==o.call(a)},x=[],y={},z={timeout:function(a,b){return b.length&&(a.timeout=b[0]),a}},A,B;B=function(a){function b(a){var a=a.split("!"),b=x.length,c=a.pop(),d=a.length,c={url:c,origUrl:c,prefixes:a},e,f,g;for(f=0;f<d;f++)g=a[f].split("="),(e=z[g.shift()])&&(c=e(c,g));for(f=0;f<b;f++)c=x[f](c);return c}function g(a,e,f,g,h){var i=b(a),j=i.autoCallback;i.url.split(".").pop().split("?").shift(),i.bypass||(e&&(e=d(e)?e:e[a]||e[g]||e[a.split("/").pop().split("?")[0]]),i.instead?i.instead(a,e,f,g,h):(y[i.url]?i.noexec=!0:y[i.url]=1,f.load(i.url,i.forceCSS||!i.forceJS&&"css"==i.url.split(".").pop().split("?").shift()?"c":c,i.noexec,i.attrs,i.timeout),(d(e)||d(j))&&f.load(function(){k(),e&&e(i.origUrl,h,g),j&&j(i.origUrl,h,g),y[i.url]=2})))}function h(a,b){function c(a,c){if(a){if(e(a))c||(j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}),g(a,j,b,0,h);else if(Object(a)===a)for(n in m=function(){var b=0,c;for(c in a)a.hasOwnProperty(c)&&b++;return b}(),a)a.hasOwnProperty(n)&&(!c&&!--m&&(d(j)?j=function(){var a=[].slice.call(arguments);k.apply(this,a),l()}:j[n]=function(a){return function(){var b=[].slice.call(arguments);a&&a.apply(this,b),l()}}(k[n])),g(a[n],j,b,n,h))}else!c&&l()}var h=!!a.test,i=a.load||a.both,j=a.callback||f,k=j,l=a.complete||f,m,n;c(h?a.yep:a.nope,!!i),i&&c(i)}var i,j,l=this.yepnope.loader;if(e(a))g(a,0,l,0);else if(w(a))for(i=0;i<a.length;i++)j=a[i],e(j)?g(j,0,l,0):w(j)?B(j):Object(j)===j&&h(j,l);else Object(a)===a&&h(a,l)},B.addPrefix=function(a,b){z[a]=b},B.addFilter=function(a){x.push(a)},B.errorTimeout=1e4,null==b.readyState&&b.addEventListener&&(b.readyState="loading",b.addEventListener("DOMContentLoaded",A=function(){b.removeEventListener("DOMContentLoaded",A,0),b.readyState="complete"},0)),a.yepnope=k(),a.yepnope.executeStack=h,a.yepnope.injectJs=function(a,c,d,e,i,j){var k=b.createElement("script"),l,o,e=e||B.errorTimeout;k.src=a;for(o in d)k.setAttribute(o,d[o]);c=j?h:c||f,k.onreadystatechange=k.onload=function(){!l&&g(k.readyState)&&(l=1,c(),k.onload=k.onreadystatechange=null)},m(function(){l||(l=1,c(1))},e),i?k.onload():n.parentNode.insertBefore(k,n)},a.yepnope.injectCss=function(a,c,d,e,g,i){var e=b.createElement("link"),j,c=i?h:c||f;e.href=a,e.rel="stylesheet",e.type="text/css";for(j in d)e.setAttribute(j,d[j]);g||(n.parentNode.insertBefore(e,n),m(c,0))}}(this,document),Modernizr.load=function(){yepnope.apply(window,[].slice.call(arguments,0))};</script>
        <style type="text/css">/* Modular scale: http://modularscale.com/scale/?px1=16&px2=24&ra1=1.5&ra2=0 */
            /* apply a natural box layout model to all elements */
            *, *:before, *:after {
                -webkit-box-sizing: border-box;
                   -moz-box-sizing: border-box;
                        box-sizing: border-box;
            }

            body {
                font-size: 16px;
                font-family: Tahoma, sans-serif;
                line-height: <?=em($css_rhythm)?>;
            }

            a { color: <?=$css_brand_color?>; text-decoration: none; }
            a:hover { text-decoration: underline; }

            code { background: #eee; background: rgba(0,0,0, 0.125); border-radius: <?=em(3)?>; padding: 0 <?=em(5)?>;  }

            p { margin: <?=em($css_rhythm)?> 0; }

            .upper-alpha { list-style: upper-alpha; }
            .lower-alpha { list-style: lower-alpha; }

            .article {
                margin-left: auto;
                margin-right: auto;
                max-width: <?=em(480)?>;
            }

            .note {
                color: #999;
                font-size: <?=em(13)?>;
                font-style: italic;
                line-height: <?=em($css_rhythm, 13)?>;
            }

            .inner-container {
                margin: 0 auto;
                max-width: <?=em(768)?>;
            }

            .page-header {
                background: <?=$css_brand_color?>;
            }
            .page-header,
            .page-content {
                padding: <?=em($css_rhythm * 4)?> <?=em($css_rhythm)?>;
                position: relative;
            }

            .notice {
                left: 0;
                position: fixed;
                right: 0;
                top: 0;
                z-index: 9;
            }

            .error-notice,
            .success-notice,
            .warning-notice,
            .site-footer {
                font-size: <?=em(12)?>;
                line-height: <?=em($css_rhythm, 12)?>;
                padding: <?=em($css_rhythm, 12)?> <?=em($css_rhythm, 12)?>;
                text-align: center;
            }

            .site-footer {
                background: #eee;
            }

            .error-notice,
            .warning-notice,
            .success-notice {
                color: white;
                position: relative;
                z-index: 2;
            }

            .error-notice   { background: #900 }
            .warning-notice { background: #E80 }
            .success-notice { background: #066 }

            .unclick {
                background: rgba(0,0,0,.5);
                bottom :0;
                cursor: not-allowed;
                left: 0;
                position: fixed;
                right: 0;
                top: 0;
                z-index: 1;
            }

            .headline, .headline-tagline {
                text-align: center;
            }

            .headline {
                color: <?=$css_brand_color?>;
                font-family: 'Cutive Mono', monospace;
                font-size: <?=em(54)?>;
                font-weight: 500;
                line-height: <?=em($css_rhythm*2, 54)?>;
                margin: <?=em($css_rhythm * 2, 54)?> 0;
            }
            h2.headline { font-size: <?=em(36)?>;                         line-height: <?=em($css_rhythm * 2, 36)?>; }
            h3.headline { font-size: <?=em(24)?>;                         line-height: <?=em($css_rhythm,     24)?>; }
            h4.headline { font-size: <?=em(16)?>;     font-weight: bold;  line-height: <?=em($css_rhythm,     16)?>; }
            h5.headline { font-size: <?=em(10.667)?>; font-weight: bold;  line-height: <?=em($css_rhythm,     16)?>; }
            h6.headline { font-size: <?=em(10.667)?>; font-style: italic; line-height: <?=em($css_rhythm,     16)?>; }

            .headline-tagline {
                color: #999;
                font-style: italic;
                font-weight: 500;
            }

            .page-header .headline { color: white; margin: 0; }
            .page-header .headline-tagline { color: <?=$css_brand_color_lighter?>; margin: 0; }

            .install-overview {
                list-style: circle;
                margin: 0 auto;
                max-width: <?=em(320)?>;
            }

            .install-overview li {
                position: relative;
            }

            .install-overview .done,
            .install-overview .active {
                color: white;
                font-weight: bold;
            }
            .install-overview .active {
                list-style: disc;
            }

            .install-overview .done {
                list-style: none;
            }
            .install-overview .done::before {
                content: "\2713";
                position: absolute;
                left: -1.25em;
            }

            .page-header .install-overview {
                color: <?=$css_brand_color_lighter?>;
                bottom: <?=em(-2 * $css_rhythm)?>;
                position: relative;
                top: <?=em(2 *$css_rhythm)?>;
            }

            .button {
                -webkit-appearance: none;
                background: white;
                border: 1px solid <?=$css_brand_color?>;
                color: <?=$css_brand_color?>;
                display: inline-block;
                padding: <?=em($css_rhythm/2)?> <?=em($css_rhythm * 2)?>;
                text-align: center;
                text-decoration: none;
            }
            a.button {
                text-decoration: none;
            }
            .button:hover {
                background: <?=$css_brand_color?>;
                color: white;
            }
            .button-block {
                display: block;
                width: 100%;
            }
            .button-inline {
                padding: <?=em($css_rhythm/8)?> <?=em($css_rhythm/2)?>;
            }

            label,
            input[type="text"] {

            }

            label {
                font-size: <?=em(12)?>;
                font-weight: bold;
                line-heihgt: <?=em($css_rhythm, 12)?>;
                padding: <?=em($css_rhythm/2, 12)?> <?=em($css_rhythm/2, 12)?> 0 <?=em($css_rhythm/2, 12)?>
            }

            input[type="text"] {
                background: white;
                border: 1px solid #ccc;
                color: #666;
                padding: <?=em($css_rhythm*1.5)?> <?=em($css_rhythm/2)?> <?=em($css_rhythm/2)?>;
            }
            input[type="text"]:focus {
                border-color: #666;
                color: #333;
                outline: none;
            }

            form {
            }

            .form-field, .form-actions {
                margin: 0;
            }

            .form-field {
                background: #eee;
                padding: <?=em($css_rhythm)?> <?=em($css_rhythm)?>;
                position: relative;
            }

            .form-field label {
                color: #999;
                display: block;
                position: absolute;
            }

            .form-field input {
                width: 100%;
            }

            .form-field-help {
                color: #999;
                font-size: <?=em(12)?>;
                line-heihgt: <?=em($css_rhythm, 12)?>;
                padding: <?=em($css_rhythm/2, 12)?> <?=em($css_rhythm/2, 12)?>;
            }

            @media(min-width: <?=em(480)?>) {
                .form-field input {
                    padding-left: <?=em(($css_rhythm/2 + 160))?>;
                    padding-top: <?=em($css_rhythm/2)?>;
                }
            }
        </style>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="page">
            <div class="page-header">
                <div class="inner-container">
                    <h1 class="headline">Publisher</h1>
                    <p class="headline-tagline">Squarespace inspired website engine</p>
                    <ul class="install-overview">
                        <li class="<?=$vendors_installed ? 'done' : 'active'?>">Install dependencies</li>
                        <li class="<?=$vendors_installed ? 'active' :''?><?=$installed ? ' done' : ''?>">Activate App</li>
                        <li class="<?=$installed ? ' active' : ''?>">Done</li>
                    </ul>
                    <div class="notice">
                        <?php if (!$htaccess_exists): ?>
                        <div class="error-notice">
                            <b>ERROR:</b> Missing <code>/htaccess.txt</code> template. Cannot continue;
                        </div>
                        <div class="unclick"></div>
                        <?php endif;?>
                        <?php if ($bad_input): ?>
                        <div class="warning-notice">
                            <b>WARNING:</b> Bad folder name. Only <b>a-z</b>, <b>A-Z</b>, <b>0-9</b>, <b>-</b> or <b>_</b> are allowed.
                        </div>
                        <?php endif; ?>
                        <?php if ($failed_to_write): ?>
                        <div class="error-notice">
                            <b>ERROR:</b> Failed to write <code>/.htaccess</code>. Check if writable. Cannot continue;
                        </div>
                        <div class="unclick"></div>
                        <?php elseif ($installed): ?>
                        <div class="success-notice">
                            <b>INSTALLED:</b> Enjoy <i>Publisher</i>! <a href="" class="button button-inline">Continue &rarr;</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="page-content">
                <div class="inner-container">
                    <?php if ($vendors_installed && !$installed): ?>
                    <h2 class="headline">A one click installer</h2>
                    <h3 class="headline-tagline">Generate <code>/.htaccess</code> and rewrite links to <code>/apps/<b class="appname-clone">default</b>/public</code></h3>
                    <form class="install-form article" method="post">
                        <p>By submiting the form a new <code>.htaccess</code> file will be created in the root directory. This <code>.htaccess</code> will rewrite requests to the <code>/apps/<b class="appname-clone">default</b>/public/index.php</code></p>
                        <p>To <i>reinstall</i> <code>.htaccess</code> file, simply delete it and than navigate to this page in the browser again.</p>
                        <h3 class="headline">Ready to go?</h3>
                        <p class="form-field">
                            <label for="app">App to install:</label>
                            <input id="appname" type="text" name="app" value="<?=isset($appname) && !empty($appname) ? $appname : 'default'?>" autocomplete="off" />
                            <span class="form-field-help">Use one word and only <b>a-z</b>, <b>A-Z</b>, <b>0-9</b>, <b>-</b> or <b>_</b>.</span>
                        </p>
                        <p class="form-actions"><button class="button button-block">Install &rarr;</button></p>
                    </form>
                    <?php elseif ($vendors_installed && $installed): ?>
                    <h2 class="headline">Ready to launch</h2>
                    <h3 class="headline-tagline">Enjoy.</h3>
                    <?php else: ?>
                    <h2 class="headline">Step 0: Install dependencies</h2>
                    <h3 class="headline-tagline">The <code>/vendor</code> subdirectory does not yet exist.</h3>
                    <div class="article">
                        <p>There are 2 main reasons for this:</p>
                        <ol>
                            <li>You have cloned <b>Publisher</b> using <code>git</code> or...</li>
                            <li>you have downloaded a <code>.zip</code> file directly from <a href="https://github.com/attitude/publisher" target="_blank">project's repository</a></li>
                        </ol>
                        <h3>Solutions</h3>
                        <ol class="upper-alpha">
                            <li>If your environment is set up to use <a href="https://getcomposer.org/" target="_blank">Composer</a>, fire up Terminal and run <code>php composer.phar install</code>. If your are wish to clone full <b>Git</b> repositories use <code>php composer.phar install --prefer-source</code>. Refer to <a href="https://getcomposer.org/doc/01-basic-usage.md" target="_blank">Composer documentation</a> for additional info.<br>
                                <span class="note">In some occasions you might run into some <i>missing classes</i> PHP errors. Just run <code>composer update</code>.</span></li>
                            <li>Download <a href="https://github.com/attitude/publisher/releases/latest" target="_blank">a fukll release package</a>, which will include all files necessary and replace this installation.</li>
                        </ol>
                        <p>
                            <a href="" class="button button-block">Continue &rarr;</a>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="site-footer">
                <div class="inner-container">
                    Publisher &copy; 2014<?=date('Y') > 2014 ? ' &mdash; '.date('Y') : ''?> <a href="http://twitter.com/martin_adamko" target="_blank">Martin Adamko</a> Released under MIT Licence.
                </div>
            </div>
        </div>

        <script type="text/javascript">/*! jQLite JavaScript Library v1.1.1 | (c) 2010 Brett Fattori | MIT license */(function(){function B(){return+new Date}var D=function(a,b){if(a===""&&b)return b;var d=a.split(" "),c=d.shift(),e;if(c.charAt(0)=="#"){var g=i.getElementById(c.substring(1));e=g?[g]:[]}else{e=c.charAt(0)!=="."?c.split(".")[0]:"*";var h=c.split("."),j=null;if(e.indexOf("[")!=-1){j=e;e=e.substr(0,e.indexOf("["))}g=function(o){var n=arguments.callee,k;if(!(k=!n.needClass)){k=n.classes;if(o.className.length==0)k=false;else{for(var r=o.className.split(" "),l=k.length,p=0;p<k.length;p++)f.inArray(k[p],r)!=-1&&l--;k=l==0}}if(k=k){if(!(k=!n.needAttribute)){n=n.attributes;k=true;for(r=0;r<n.length;r++){l=n[r].split("=");p=l[0].indexOf("!")!=-1||l[0].indexOf("*")!=-1?l[0].charAt(l[0].length-1)+"=":"=";if(p!="=")l[0]=l[0].substring(0,l[0].length-1);switch(p){case "=":k&=o.getAttribute(l[0])===l[1];break;case "!=":k&=o.getAttribute(l[0])!==l[1];break;case "*=":k&=o.getAttribute(l[0]).indexOf(l[1])!=-1;break;default:k=false}}k=k}k=k}if(k)return o};for(var u=[],s=0;s<b.length;s++)for(var C=b[s].getElementsByTagName(e),v=0;v<C.length;v++)u.push(C[v]);h&&h.shift();e=[];g.classes=h;if(j!=null){var w=j.indexOf("[");s=j.lastIndexOf("]");w=j.substring(w+1,s).split("][")}g.attributes=j!=null?w:null;g.needClass=c.indexOf(".")!=-1&&h.length>0;g.needAttribute=j!=null;for(c=0;c<u.length;c++)g(u[c])&&e.push(u[c])}return D(d.join(" "),e)},Q=function(a,b){b=b||i;if(a.nodeType&&a.nodeType===E){a=i.body;if(a===null)return[i]}if(a.nodeType&&a.nodeType===m)return[a];if(a.jquery&&typeof a.jquery==="string")return a.toArray();if(b)b=F(b);if(f.isArray(a))return a;else if(typeof a==="string"){for(var d=[],c=0;c<b.length;c++){var e=[b[c]];if(!f.forceSimpleSelectorEngine&&e[0].querySelectorAll){e=e[0].querySelectorAll(a);for(var g=0;g<e.length;g++)d.push(e.item(g))}else d=d.concat(D(a,e))}return d}else return null},G=false;setTimeout(function(){var a=i.body;if(a){var b=i.createElement("script"),d="i"+(new Date).getTime();b.type="text/javascript";try{b.appendChild(i.createTextNode("window."+d+"=1;"))}catch(c){}a.insertBefore(b,a.firstChild);var e=true;if(window[d])delete window[d];else e=false;a.removeChild(b);G=e}else setTimeout(arguments.callee,33)},33);var H=function(a){var b=i.createElement("div");b.innerHTML=a;return{scripts:b.getElementsByTagName("script"),data:a}},I=function(a){a=a.replace(/-/g," ");a=a;var b=true;b=b||false;a=!a?"":a.toString().replace(/^\s*|\s*$/g,"");var d="";if(a.length<=0)a="";else{var c=false;d+=b?a.charAt(0):a.charAt(0).toUpperCase();for(b=1;b<a.length;b++){d+=c?a.charAt(b).toUpperCase():a.charAt(b).toLowerCase();var e=a.charCodeAt(b);c=e==32||e==45||e==46;if(e==99||e==67)if(a.charCodeAt(b-1)==77||a.charCodeAt(b-1)==109)c=true}a=d}return a.replace(/ /g,"")},J={click:"MouseEvents",dblclick:"MouseEvents",mousedown:"MouseEvents",mouseup:"MouseEvents",mouseover:"MouseEvents",mousemove:"MouseEvents",mouseout:"MouseEvents",contextmenu:"MouseEvents",keypress:"KeyEvents",keydown:"KeyEvents",keyup:"KeyEvents",load:"HTMLEvents",unload:"HTMLEvents",abort:"HTMLEvents",error:"HTMLEvents",resize:"HTMLEvents",scroll:"HTMLEvents",select:"HTMLEvents",change:"HTMLEvents",submit:"HTMLEvents",reset:"HTMLEvents",focus:"HTMLEvents",blur:"HTMLEvents",touchstart:"MouseEvents",touchend:"MouseEvents",touchmove:"MouseEvents"},K=function(a,b,d){if(f.isFunction(d)){if(typeof b==="string")b=b.toLowerCase();var c=J[b];if(b.indexOf("on")==0)b=b.substring(2);if(c){c=function(e){var g=arguments.callee,h=e.data||[];h.unshift(e);g=g.fn.apply(a,h);if(typeof g!="undefined"&&g===false){if(e.preventDefault&&e.stopPropagation){e.preventDefault();e.stopPropagation()}else{e.returnValue=false;e.cancelBubble=true}return false}return true};c.fn=d;a.addEventListener?a.addEventListener(b,c,false):a.attachEvent("on"+b,c)}else{if(!a._handlers)a._handlers={};c=a._handlers[b]||[];c.push(d);a._handlers[b]=c}}},f=function(a,b){return(new x).init(a,b)},i=window.document,y=Object.prototype.hasOwnProperty,z=Object.prototype.toString,L=Array.prototype.push,R=Array.prototype.slice,m=1,E=9,A=[],M=false,N=false,q;f.forceSimpleSelectorEngine=false;f.each=function(a,b){var d,c=0,e=a.length;if(e===undefined||f.isFunction(a))for(d in a){if(b.call(a[d],d,a[d])===false)break}else for(d=a[0];c<e&&b.call(d,c,d)!==false;d=a[++c]);return a};f.noop=function(){};f.isFunction=function(a){return z.call(a)==="[object Function]"};f.isArray=function(a){return z.call(a)==="[object Array]"};f.isPlainObject=function(a){if(!a||z.call(a)!=="[object Object]"||a.nodeType||a.setInterval)return false;if(a.constructor&&!y.call(a,"constructor")&&!y.call(a.constructor.prototype,"isPrototypeOf"))return false;var b;for(b in a);return b===undefined||y.call(a,b)};f.merge=function(a,b){var d=a.length,c=0;if(typeof b.length==="number")for(var e=b.length;c<e;c++)a[d++]=b[c];else for(;b[c]!==undefined;)a[d++]=b[c++];a.length=d;return a};f.param=function(a){var b="";a&&f.each(a,function(d,c){b+=(b.length!=0?"&":"")+c+"="+encodeURIComponent(d)});return b};f.evalScripts=function(a){for(var b=i.getElementsByTagName("head")[0]||i.documentElement,d=0;d<a.length;d++){var c=i.createElement("script");c.type="text/javascript";if(G)c.appendChild(i.createTextNode(a[d].text));else c.text=a[d].text;b.insertBefore(c,b.firstChild);b.removeChild(c)}};f.ready=function(){for(M=true;A.length>0;)A.shift()()};var t="jQuery"+B(),S=0,O={};f.noData={embed:true,object:true,applet:true};f.cache={};f.data=function(a,b,d){if(!(a.nodeName&&jQuery.noData[a.nodeName.toLowerCase()])){a=a==window?O:a;var c=a[t];c||(c=a[t]=++S);if(b&&!jQuery.cache[c])jQuery.cache[c]={};if(d!==undefined)jQuery.cache[c][b]=d;return b?jQuery.cache[c][b]:c}};f.removeData=function(a,b){a=a==window?O:a;var d=a[t];if(b){if(jQuery.cache[d]){delete jQuery.cache[d][b];b="";for(b in jQuery.cache[d])break;b||jQuery.removeData(a)}}else{try{delete a[t]}catch(c){a.removeAttribute&&a.removeAttribute(t)}delete jQuery.cache[d]}};f.ajaxSettings={url:location.href,data:{},type:"GET",async:true,username:null,password:null,sendFn:null,status:null,contentType:"application/x-www-form-urlencoded"};f.ajax={status:-1,statusText:"",responseText:null,responseXML:null,send:function(a,b){var d=jQuery.extend(a,f.ajaxSettings);if(d.url){var c=f.param(d.data);if(c.length!=0&&d.type==="GET")url+=(url.indexOf("?")==-1?"?":"&")+c;var e=new XMLHttpRequest;e.open(d.type,d.url,d.async,d.username,d.password);if(c.length!=0||a&&a.contentType)xhr.setRequestHeader("Content-Type",d.contentType);e.send(d.type==="POST"||d.type==="PUT"?c:null);if(d.async){c=function(g){var h=arguments.callee;g.status==200?f.ajax.complete(g,h.s,h.cb):f.ajax.error(g,h.s,h.cb)};c.cb=b;c.s=d;d=function(){var g=arguments.callee;g.req.readyState!=4?setTimeout(g,250):g.xcb(g.req)};d.req=e;d.xcb=c;setTimeout(d,250)}}},complete:function(a,b,d){f.ajax.status=b.status=a.status;var c=(a.getResponseHeader("content-type")||"").indexOf("xml")>=0?a.responseXML:a.responseText;f.ajax.responseText=a.responseText;f.ajax.responseXML=a.responseXML;f("body",i).trigger("ajaxComplete",[a,b]);f.isFunction(d)&&d(c,a.status)},error:function(a,b,d){f.ajax.status=b.status=a.status;f.ajax.statusText=a.statusText;f("body",i).trigger("ajaxError",[a,b]);f.isFunction(d)&&d(a.status,a.statusText)}};f.post=function(a,b,d){if(f.isFunction(b)){d=b;b={}}return f.ajax.send({type:"POST",url:a,data:b},d)};f.makeArray=function(a,b){var d=b||[];if(a!=null)a.length==null||typeof a==="string"||jQuery.isFunction(a)||typeof a!=="function"&&a.setInterval?L.call(d,a):f.merge(d,a);return d};f.inArray=function(a,b){for(var d=0;d<b.length;d++)if(b[d]===a)return d;return-1};f.trim=function(a){return a!=null?a.toString().replace(/^\s*|\s*$/g,""):""};var x=function(){};x.prototype={selector:"",context:null,length:0,jquery:"jqlite-1.1.2",init:function(a,b){if(!a)return this;if(a.nodeType){this.context=this[0]=a;this.length=1}else if(typeof a==="function")this.ready(a);else{var d=[];if(a.jquery&&typeof a.jquery==="string")d=a.toArray();else if(f.isArray(a))d=a;else if(typeof a==="string"&&f.trim(a).indexOf("<")==0&&f.trim(a).indexOf(">")!=-1){d=f.trim(a).toLowerCase();d=d.indexOf("<option")==0?"SELECT":d.indexOf("<li")==0?"UL":d.indexOf("<tr")==0?"TBODY":d.indexOf("<td")==0?"TR":"DIV";d=i.createElement(d);d.innerHTML=a;d=[d.removeChild(d.firstChild)]}else{if(a.indexOf(",")!=-1){d=a.split(",");for(var c=0;c<d.length;c++)d[c]=f.trim(d[c])}else d=[a];c=[];for(var e=0;e<d.length;e++)c=c.concat(Q(d[e],b));d=c}L.apply(this,d)}return this},pushStack:function(a){a=f(a);a.prevObj=this;a.context=this.context;return a},each:function(a){return f.each(this,a)},size:function(){return this.length},toArray:function(){return R.call(this,0)},ready:function(a){if(M)a();else{A.push(a);return this}},data:function(a,b){if(typeof a==="undefined"&&this.length)return jQuery.data(this[0]);else if(typeof a==="object")return this.each(function(){jQuery.data(this,a)});var d=a.split(".");d[1]=d[1]?"."+d[1]:"";if(b===undefined){if(data===undefined&&this.length)data=jQuery.data(this[0],a);return data===undefined&&d[1]?this.data(d[0]):data}else return this.each(function(){jQuery.data(this,a,b)})},removeData:function(a){return this.each(function(){jQuery.removeData(this,a)})},addClass:function(a){return this.each(function(){if(this.className.length!=0){var b=this.className.split(" ");if(f.inArray(a,b)==-1){b.push(a);this.className=b.join(" ")}}else this.className=a})},removeClass:function(a){return this.each(function(){if(this.className.length!=0){var b=this.className.split(" "),d=f.inArray(a,b);if(d!=-1){b.splice(d,1);this.className=b.join(" ")}}})},hasClass:function(a){if(this[0].className.length==0)return false;return f.inArray(a,this[0].className.split(" "))!=-1},isElementName:function(a){return this[0].nodeName.toLowerCase()===a.toLowerCase()},toggleClass:function(a){return this.each(function(){if(this.className.length==0)this.className=a;else{var b=this.className.split(" "),d=f.inArray(a,b);d!=-1?b.splice(d,1):b.push(a);this.className=b.join(" ")}})},hide:function(a){return this.each(function(){if(this.style&&this.style.display!=null)if(this.style.display.toString()!="none"){this._oldDisplay=this.style.display.toString()||(this.nodeName!="span"?"block":"inline");this.style.display="none"}f.isFunction(a)&&a(this)})},show:function(a){return this.each(function(){this.style.display=(this._oldDisplay&&this._oldDisplay!=""?this._oldDisplay:null)||(this.nodeName!="span"?"block":"inline");f.isFunction(a)&&a(this)})},css:function(a,b){if(typeof a==="string"&&b==null)return this[0].style[I(a)];else{a=typeof a==="string"?P(a,b):a;return this.each(function(){var d=this;typeof d.style!="undefined"&&f.each(a,function(c,e){e=typeof e==="number"?e+"px":e;var g=I(c);d.style[g]||(g=c);d.style[g]=e})})}},html:function(a){return a?this.each(function(){var b=H(a);this.innerHTML=b.data;f.evalScripts(b.scripts)}):this[0].innerHTML},attr:function(a,b){return typeof a==="string"&&b==null?this[0]?this[0].getAttribute(a):"":this.each(function(){a=typeof a==="string"?P(a,b):a;for(var d in a)this.setAttribute(d,a[d])})},eq:function(a){var b=this.toArray(),d=this.pushStack(b);d.context=d[0]=a<0?b[b.length+a]:b[a];d.length=1;return d},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},index:function(a){var b=-1;if(this.length!=0){var d=this[0];if(a){var c=f(a)[0];this.each(function(g){if(this===c){b=g;return false}})}else{a=this.parent()[0].firstChild;for(var e=[];a!=null;){a.nodeType===m&&e.push(a);a=a.nextSibling}f.each(a,function(g){if(this===d){b=g;return false}})}}return b},next:function(a){var b=[];if(a){var d=f(a);this.each(function(){for(var c=this.nextSibling;c!=null&&c.nodeType!==m;)c=c.nextSibling;if(c!=null){var e=false;d.each(function(){if(this==c){e=true;return false}});e&&b.push(c)}})}else this.each(function(){for(var c=this.nextSibling;c!=null&&c.nodeType!==m;)c=c.nextSibling;c!=null&&b.push(c)});return this.pushStack(b)},prev:function(a){var b=[];if(a){var d=f(a);this.each(function(){for(var c=this.previousSibling;c!=null&&c.nodeType!==m;)c=c.previousSibling;if(c!=null){var e=false;d.each(function(){if(this==c){e=true;return false}});e&&b.push(c)}})}else this.each(function(){for(var c=this.previousSibling;c!=null&&c.nodeType!==m;)c=c.previousSibling;c!=null&&b.push(c)});return this.pushStack(b)},parent:function(a){var b=[];if(a){var d=f(a);this.each(function(){var c=this.parentNode,e=false;d.each(function(){if(this==c){e=true;return false}});e&&b.push(c)})}else this.each(function(){b.push(this.parentNode)});return this.pushStack(b)},parents:function(a){var b=[];if(a){var d=f(a);this.each(function(){for(var c=this;c!=i.body;){d.each(function(){this==c&&b.push(c)});c=c.parentNode}})}else this.each(function(){for(var c=this;c!=i.body;){c=c.parentNode;b.push(c)}});return this.pushStack(b)},children:function(a){var b=[];if(a){var d=f(a);this.each(function(){for(var c=this.firstChild;c!=null;){c.nodeType==m&&d.each(function(){this===c&&b.push(c)});c=c.nextSibling}})}else this.each(function(){for(var c=this.firstChild;c!=null;){c.nodeType==m&&b.push(c);c=c.nextSibling}});return this.pushStack(b)},find:function(a){return a?this.pushStack(f(a,this)):this},append:function(a){a=F(a);return this.each(function(){for(var b=0;b<a.length;b++)this.appendChild(a[b])})},remove:function(a){return this.each(function(){a?$(a,this).remove():this.parentNode.removeChild(this)})},empty:function(){return this.each(function(){this.innerHTML=""})},val:function(a){if(a==null){var b=null;if(this&&this.length!=0&&typeof this[0].value!="undefined")b=this[0].value;return b}else return this.each(function(){if(typeof this.type!="undefined")if(this.type=="checkbox"||this.type=="radio")this.checked=a=="on"||a==1||a===true;else if(typeof this.value!="undefined")this.value=a})},end:function(){return this.prevObj||f(null)},bind:function(a,b){return this.each(function(){K(this,a,b)})},trigger:function(a,b){return this.each(function(){var d;var c;c=a;if(typeof c==="string")c=c.toLowerCase();var e=null,g=J[c]||"Event";if(i.createEvent){e=i.createEvent(g);e._eventClass=g;c&&e.initEvent(c,true,true)}if(i.createEventObject){e=i.createEventObject();if(c){e.type=c;e._eventClass=g}}c=e;if(c._eventClass!=="Event"){c.data=b;d=this.dispatchEvent(c)}else if(e=(this._handlers||{})[a])for(g=0;g<e.length;g++){var h=f.isArray(b)?b:[];h.unshift(c);h=e[g].apply(this,h);h=typeof h=="undefined"?true:h;if(!h)break}return d})},submit:function(a){return this.each(function(){if(f.isFunction(a))K(this,"onsubmit",a);else this.submit&&this.submit()})}};if(i.addEventListener)q=function(){i.removeEventListener("DOMContentLoaded",q,false);f.ready()};else if(i.attachEvent)q=function(){if(i.readyState==="complete"){i.detachEvent("onreadystatechange",q);f.ready()}};if(!N){N=true;if(i.readyState==="complete")return f.ready();if(i.addEventListener){i.addEventListener("DOMContentLoaded",q,false);window.addEventListener("load",f.ready,false)}else if(i.attachEvent){i.attachEvent("onreadystatechange",q);window.attachEvent("onload",f.ready)}}var P=function(a,b){var d={};d[a]=b;return d},F=function(a){if(a.nodeType&&(a.nodeType===m||a.nodeType===E))a=[a];else if(typeof a==="string")a=f(a).toArray();else if(a.jquery&&typeof a.jquery==="string")a=a.toArray();return a};if(typeof window.jQuery=="undefined"){window.jQuery=f;window.jQuery.fn=x.prototype;window.$=window.jQuery;window.now=B}jQuery.extend=jQuery.fn.extend=function(){var a=arguments[0]||{},b=1,d=arguments.length,c=false,e,g,h,j;if(typeof a==="boolean"){c=a;a=arguments[1]||{};b=2}if(typeof a!=="object"&&!jQuery.isFunction(a))a={};if(d===b){a=this;--b}for(;b<d;b++)if((e=arguments[b])!=null)for(g in e){h=a[g];j=e[g];if(a!==j)if(c&&j&&(jQuery.isPlainObject(j)||jQuery.isArray(j))){h=h&&(jQuery.isPlainObject(h)||jQuery.isArray(h))?h:jQuery.isArray(j)?[]:{};a[g]=jQuery.extend(c,h,j)}else if(j!==undefined)a[g]=j}return a};jQuery.each("click,dblclick,mouseover,mouseout,mousedown,mouseup,keydown,keypress,keyup,focus,blur,change,select,error,load,unload,scroll,resize,touchstart,touchend,touchmove".split(","),function(a,b){jQuery.fn[b]=function(d){return d?this.bind(b,d):this.trigger(b)}});jQuery.fn.extend({_load:jQuery.fn.load,load:function(a,b,d){if(typeof a!="string")return this._load(a);if(f.isFunction(b)){d=b;b={}}return this.each(function(){var c=function(e,g){var h=arguments.callee;if(e){var j=H(e);h.elem.innerHTML=j.data;f.evalScripts(j.scripts)}f.isFunction(h.cback)&&h.cback(e,g)};c.cback=d;c.elem=this;f.ajax.send({url:a,data:b},c)})}})})();</script>        <script>
        $(function() {
            var $input = $('#appname'),
                $clone = $('.appname-clone'),
                input_val;

            $input.bind('keyup', function() {
                input_val = $input.val();
                if (input_val.length===0) {
                    $clone.html('default');
                } else {
                    $clone.html(input_val);
                }
            });
        });
        </script>
    </body>
</html>
