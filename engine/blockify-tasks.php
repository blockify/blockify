<?php
/*!
 * Blockify (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/62design/blockify/blob/master/LICENSE)
 */

function renderBlockPage($basename)
{
    ?><!DOCTYPE html>
        <html>
        <head>
            <title>Blockify Render <?php echo $basename; ?></title>
        <?php  if ($basename == 'blockify-dashboard'): ?>
            <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700,700italic' rel='stylesheet' type='text/css'>
        <?php endif;  ?>
            <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
            <?php bl_head(); ?>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
            <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/holder/2.3.1/holder.min.js"></script>
        </head>
        <body>
        <?php
            block($basename);
            bl_footer();
        ?>
        </body>
    </html>
    <?php
}

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
            renderBlockPage('blockify-dashboard');
            die();
        case array_key_exists('api/render', $_GET):
            renderBlockPage($_GET['api/render']);
            die();
    }
}
?>
