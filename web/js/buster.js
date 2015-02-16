"use strict";

if (typeof(Buster) == 'undefined') {
    if ('undefined' == typeof window._bstr) {
        window._bstr = [];
    }

    var Buster = {
        he: true,
        dn: '',
        td: '',
        id: null
    };

    Buster.disableHttps = function() {
        this.he = false;
    };

    Buster.domainName = function(name) {
        this.dn = name;
    };

    Buster.identity = function(id) {
        this.id = id;
    };

    Buster.trackDomain = function(name) {
        this.td = name;
    };

    Buster.referrer = function() {
        return document.referrer;
    };

    Buster.host = function() {
        var location = document.location + '';

        return (this.he && location.toLowerCase().indexOf('https')) ? 'https' : 'http';
    };

    Buster.pageView = function(params) {
        var url = this.url(params || []);

        this.buildImage(url);
    };

    Buster.url = function(params) {
        return this.host() + '://' + this.td + '/pixel?' + Buster.joinParams(this.pixelParams( params ));
    };

    Buster.screenSize = function() {
        return '{"w":' + window.innerWidth + ',"h":' + window.innerHeight + '}';
    };

    Buster.buildImage = function(url) {
        var img = new Image();

        img.async = true;
        img.src = url;

        console.log(url);
    };

    Buster.joinParams = function(params) {
        var out = [],
            key;

        for (key in params) {
            params[key] = 'object' == typeof params[key] ? this.joinParams(params[key]) : params[key];

            out.push(key + '=' + encodeURIComponent(params[key]));
        }

        return out.join('&');
    };

    Buster.pixelParams = function(params) {
        var pixel = {
            i: this.id,
            d: this.dn,
            s: this.screenSize(),
            r: this.referrer()
        };

        for (var i in params) {
            pixel['p[' + i + ']'] = params[i];
        }

        return pixel;
    };

    Buster.isBot = function(a) {
        a || (a = navigator.userAgent);
        return a.match(/bot|spider|crawl|mediapartners|archive|slurp|agent|grab/i) ? !0 : !1
    };

    var BusterArray = function(data) {
        if (data && data.length) {
            for (var i = 0; i < data.length; i++) {
                this.push(data[i]);
            }
        }
    };

    BusterArray.prototype.push = function(data) {
        if (!data) {
            return;
        }

        if (Buster.isBot()) {
            return;
        }

        if ('object' == typeof data && data.length) {
            var name = data.splice(0, 1);

            if (Buster[name]) {
                Buster[name].apply(Buster, data);
            }
        }
    };

    window._bstr = new BusterArray(window._bstr);
}




//if (typeof(Buster) == 'undefined') {
//    var Buster = {
//        referrer: document.referrer,
//
//
//        _i: null,
//        dr: !1,
//        rq: [],
//        host: "http://buster.onevest.net",
//        hosts: "https://buster.onevest.net",
//        lc: {},
//        cp: "km_"
//    };
//
//
//
//    var BSTR = function(a) {
//        this.r = 1;
//        if (a && a.length)
//            for (var b = 0; b < a.length; b++) this.push(a[b])
//    };
//
//    BSTR.prototype.push = function(a) {
//        if (!a) return;
//        if ("object" == typeof a && a.length) {
//            var b = a.splice(0, 1);
//            Buster[b] && Buster[b].apply(Buster, a)
//        } else "function" == typeof a && a()
//    };
//
//    Buster.pageview = function() {
//        var img = new Image(1, 1),
//            url = Buster.host() + '/pixel',
//            params = {};
//
//        img.src = url + '?' + Buster.joinParams(params);
//    };
//
//    Buster.host = function() {
//        var location = document.location + '';
//
//        return location.toLowerCase().indexOf('https') ? Buster.hosts : Buster.host;
//    };
//
//    Buster.joinParams = function(params) {
//        var out = [],
//            key;
//
//        for (key in params) {
//            out.push(key + '=' + encodeURIComponent(params[key]));
//        }
//
//        return out.join('&');
//    };
//    Buster.isBot = function(a) {
//        a || (a = navigator.userAgent);
//        return a.match(/bot|spider|crawl|mediapartners|archive|slurp|agent|grab/i) ? !0 : !1
//    };
//    Buster.rq = function() {
//        console.log(window._bstr);
//    };
//    Buster.ikmq = function() {
//        !Buster.isBot() && (window._bstr.r || (Buster.rq(), window._bstr = new BSTR(window._bstr)))
//    };
//    Buster.drdy = !1;
//    Buster.odr = function() {
//        Buster.drdy || (Buster.drdy = !0, setTimeout(function() {
//            Buster.ikmq()
//        }, 1E3))
//    };
//    Buster.cdr = function() {
//        var a = document;
//        return "complete" == a.readyState || a.addEventListener && "loaded" == a.readyState ? (Buster.odr(), !0) : !1
//    };
//    Buster.cdr() || (Buster.idr = function() {
//        var a = document,
//            b = window;
//        a.addEventListener ? (a.addEventListener("DOMContentLoaded", Buster.odr, !0), a.addEventListener("readystatechange", Buster.cdr, !0), b.addEventListener("load", Buster.odr, !0)) : a.attachEvent && (a.attachEvent("onreadystatechange", Buster.cdr), b.attachEvent("onload", Buster.odr))
//    }, Buster.idr());
//}