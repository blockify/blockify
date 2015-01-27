# Blockify 2.0

Blockify requires stable PHP versions >= 5.3

---

## Installing
### Manually

Clone this repo into DocumentRoot/blockify and create an index.php for the test code.

A simple directory structure might look like this:
```
DocumentRoot/
├── blockify/
│   └── ...
├── images/
│   └── logo.png
└── index.php
```
Now you’re able to load Blockify into your PHP project:

```php
require 'blockify/load.php';
```
### Alternative Method

During the beta only a manual download is available, but in the future we may add an alternative, i.e. Composer.

Environment
If the blockify directory cannot be accessed with //127.0.0.1/blockify you need to set the BLOCKIFY_URL constant before loading Blockify:

```php
define('BLOCKIFY_URL', 'http://blockify.co/blockify');
require 'blockify/load.php';
```

---

## Test Run
Let’s test Blockify by loading the test block with index.php:

```php
<?php require 'blockify/load.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blockify Test</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/css/bootstrap.min.css">
    <!-- CSS from the blocks -->
    <?php blockify_css(); ?>
  </head>
  <body>
    <?php
        // Print the test block
        echo new \Blockify\Block('test');
    ?>
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <!-- Include all compiled Bootstrap plugins -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- Holder.js for placeholder images when developing -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/holder/2.3.1/holder.min.js"></script>
    <!-- JavaScript from the blocks -->
    <?php blockify_js(); ?>
  </body>
</html>
```

---

## That’s it!
You’ve loaded Blockify and a test block.

Now dive into the [Using Blocks](http://blockify.co/docs/using-blocks/) guide to start the cool stuff.
