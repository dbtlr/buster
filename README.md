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

### Todo

1. Add in a database backend adapter system.
3. Add in a domain cookie to track unique users.
2. Add in a reporting API to pull data back out.
  1. Report by date range
  2. Report by ip address
  3. Report by custom parameter
  4. Report by identity
  5. Report outlining highest traffic individuals
