<?php
/*!
 * Blockify (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/62design/blockify/blob/master/LICENSE)
 */

$renderBlockPage = function ($name) {
    ?> <!DOCTYPE html>
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
        <!-- Print the block -->
        <?php block($name); ?>

        <!-- jQuery -->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <!-- Include all compiled Bootstrap plugins -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <!-- Holder.js for placeholder images when developing -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/holder/2.3.1/holder.min.js"></script>
        <!-- JavaScript from the blocks -->
        <?php blockify_js(); ?>
      </body>
    </html>
    <?php
};

if (BLOCKIFY_DEV === true) {
    global $blockify;
    switch(true)
    {
        case array_key_exists('api/list', $_GET):
            header('Content-type: text/plain');
            echo json_encode($blockify->factory->getAllPackages());
            die();
        case array_key_exists('dash', $_GET) ||
        array_key_exists('api/dash', $_GET) ||
        array_key_exists('dashboard', $_GET) ||
        array_key_exists('api/dashboard', $_GET):
            $renderBlockPage('blockify-dashboard');
            die();
        case array_key_exists('api/render', $_GET):
            $renderBlockPage($_GET['api/render']);
            die();
    }
}
