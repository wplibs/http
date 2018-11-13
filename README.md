WP HTTP Foundation [![Build Status](https://travis-ci.org/wplibs/http.svg?branch=master)](https://travis-ci.org/wplibs/http)
==================

## Installation

```
composer require wplibs/http:^1.0
```

## Usage

```php
<?php

Use WPLibs\Http\Request;

$request = Request::capture();

$name = $request->get('name');
```
