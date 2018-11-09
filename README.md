WP-HTTP 
=========

```php
<?php

Use WPLibs\Http\Request;

$request = Request::capture();

$name = $request->get('name');
```
