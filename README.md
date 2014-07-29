Supercharge your workflow with Blockify
========

Blockify makes PHP web development faster and easier by allowing you to save, reuse and share your code across multiple projects.

###Build your website using blocks
Think of blocks as web components with back-end capabilities, that can be easily integrated into your website and combined with each-other, allowing you to quickly develop awesome websites.

###How blocks are used
The blocks themselves are called in with a single line of PHP and are configurable with plenty of options to help get the look and feel you're going for. Content can be added by passing data into the block manually, or automatically from your favorite CMS.

###Schema.org and blocks
When using Blockify, your content is marked up with schema.org microdata, allowing search engines to identify how your site is structured which improves the display of your search results. Also, Blockify nicely integrates with the Bootstrap framework for a completely responsive design with minimal effort.

##Main Features

###Open Source
Blockify is an open source project and will be available on GitHub, licensed under the GNU General Public License v3.

###Powered by schema.org
Blockify uses JSON-LD with the schema.org vocabulary to structure data, this creates a smooth experience when working between different blocks and helps block engineers markup their blocks with microdata.

###LESS, SASS and CoffeeScript
In addition to vanilla CSS and JS, Blockify includes support for the popular preprocessors, LESS, SASS and CoffeeScript with the use of Grunt.

###Bootstrap
Blockify works great when mixed with Bootstrap. Although Blockify uses Bootstrap in the documentation and starter blocks, you don't have to use it in your project.

Blockify requires stable PHP versions &gt;= 5.3

## Installing

### Manually

Download and extract the [Latest Blockify Build](https://github.com/blockify/blockify/releases/download/0.1.2/blockify-0.1.2.zip) into your DocumentRoot, your&nbsp;directory structure might&nbsp;look like this:

```DocumentRoot/
├── blockify/
│   └── ...
├── images/
│   ├── logo.png
│   └── logo@<span class="hljs-number">2</span>x.png
└── index.php
```


Now you’re able to load Blockify into your PHP project:

```
<?php
require 'blockify/load.php';
```
### Alternative Method

During the beta only a manual download is available, but in the future we may add an alternative, i.e. Composer.

## Environment

If the `blockify` directory cannot be accessed with `//127.0.0.1/blockify` you need to set&nbsp;the `BLOCKIFY_URL` constant before loading&nbsp;Blockify:

Also see: [Named Constants](http://blockify.co/api/named-constants/ "Named Constants")

## Test Run

Let’s test Blockify by loading the test block with **index.php**:
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
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <!-- CSS from the blocks -->
    <?php blockify_css(); ?>
  </head>
  <body>
    <!-- Print the test block -->
    <?php block('blockify-test'); ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <!-- Include all compiled Bootstrap plugins -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <!-- Holder.js for placeholder images when developing -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/holder/2.3.1/holder.min.js"></script>
    <!-- JavaScript from the blocks -->
    <?php blockify_js(); ?>
  </body>
</html>
```

## That’s it!

You’ve loaded Blockify and a test block.

Now dive into [Using Blocks](http://blockify.co/documentation/using-blocks/ "Using Blocks") to start the cool stuff.
