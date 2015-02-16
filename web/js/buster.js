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
