"use strict";

if (typeof(Buster) == 'undefined') {
    var Buster = {
        referrer: document.referrer,


        _i: null,
        dr: !1,
        rq: [],
        host: "http://buster.onevest.net",
        hosts: "https://buster.onevest.net",
        lc: {},
        cp: "km_"
    };

    "undefined" == typeof window._bstr && (window._bstr = []);

    var BSTR = function(a) {
        this.r = 1;
        if (a && a.length)
            for (var b = 0; b < a.length; b++) this.push(a[b])
    };

    BSTR.prototype.push = function(a) {
        if (!a) return;
        if ("object" == typeof a && a.length) {
            var b = a.splice(0, 1);
            Buster[b] && Buster[b].apply(Buster, a)
        } else "function" == typeof a && a()
    };

    Buster.pageview = function() {
        var img = new Image(1, 1),
            url = Buster.host() + '/pixel',
            params = {};

        img.src = url + '?' + Buster.joinParams(params);
    };

    Buster.host = function() {
        var location = document.location + '';

        return location.toLowerCase().indexOf('https') ? Buster.hosts : Buster.host;
    };

    Buster.joinParams = function(params) {
        var out = [],
            key;

        for (key in params) {
            out.push(key + '=' + encodeURIComponent(params[key]));
        }

        return out.join('&');
    };
    Buster.isBot = function(a) {
        a || (a = navigator.userAgent);
        return a.match(/bot|spider|crawl|mediapartners|archive|slurp|agent|grab/i) ? !0 : !1
    };
    Buster.rq = function() {
        console.log(window._bstr);
    };
    Buster.ikmq = function() {
        !Buster.isBot() && (window._bstr.r || (Buster.rq(), window._bstr = new BSTR(window._bstr)))
    };
    Buster.drdy = !1;
    Buster.odr = function() {
        Buster.drdy || (Buster.drdy = !0, setTimeout(function() {
            Buster.ikmq()
        }, 1E3))
    };
    Buster.cdr = function() {
        var a = document;
        return "complete" == a.readyState || a.addEventListener && "loaded" == a.readyState ? (Buster.odr(), !0) : !1
    };
    Buster.cdr() || (Buster.idr = function() {
        var a = document,
            b = window;
        a.addEventListener ? (a.addEventListener("DOMContentLoaded", Buster.odr, !0), a.addEventListener("readystatechange", Buster.cdr, !0), b.addEventListener("load", Buster.odr, !0)) : a.attachEvent && (a.attachEvent("onreadystatechange", Buster.cdr), b.attachEvent("onload", Buster.odr))
    }, Buster.idr());
}