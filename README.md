Buster Tracking
===============

A quick Silex application for providing custom application tracking.

### HTML Code To Enable

Add this code into the ```<head>``` tag of your application. 

- Replace yourdomain.com with the domain of your application.
- Replace trackerdomain.com with the domain that you install your tracker on.
- Optionally you can provide a unique user identifier, to help distinguish your users.

```html
<script type="text/javascript">
    var _bstr = _bstr || [];

    _bstr.push(['domainName', 'yourdomain.com']);
    _bstr.push(['trackDomain', 'trackerdomain.com']);
    _bstr.push(['identity', 'optional user identifier']);
    _bstr.push(['pageView']);

    (function() {
        var se = document.createElement('script'); se.type = 'text/javascript'; se.async = true;
        se.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'trackerdomain.com/js/buster.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(se, s);
    })();
</script>
```
