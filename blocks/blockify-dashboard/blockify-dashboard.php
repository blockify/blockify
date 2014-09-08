<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600,400,300' rel='stylesheet' type='text/css'>
<?php

global $blockify;

// Force disable container
$block->container = false;

$blocks = $blockify->factory->getAllPackages();
$grid = new BlockifyGridIterator($blocks, 1);

$block->open();
?>
<header>
    <div class="container">
        Local Blocks
    </div>
</header>
<main class="container">
<?php

foreach ($blocks as $current) {
    $private = array_key_exists('private', $current->json)
        && $current->json['private'] == true;
    ?>
    <article class="block-entry">
        <figure>
            <?php
                if (!is_null($current->icon)) {
                    $icon = str_replace('\\', '/', str_replace(BLOCKIFY_PATH, BLOCKIFY_URL, $current->icon));
                    echo "<img src=\"$icon\">";
                }
            ?>
        </figure>
        <div class="content">
            <h1>
                <?= $current->name; ?>
                <span><?= $current->version; ?></span>
                <?php if($private) echo '<span class="private">Private</span>'; ?>
            </h1>
            <p><?= $current->description ; ?></p>
        </div>
    </article>
    <?php
}

?>
</main>
<?php
$block->close();
