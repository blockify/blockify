<?php

global $blockify;
$blocks = $blockify->factory->getAllPackages();
echo 'needs updating';
return;

$blockTypeColors = array(
    'dev' => '#8e44ad',
    'pricing-table' => '#27ae60',
    'content' => '#2c3e50',
    'header' => '#3498db',
    'footer' => '#2980b9',
    'slider' => '#d35400'
);
$blockTypes = array(
    'misc' => array()
);
foreach ($blocks as $block) {
    $type = $block['json']['type'];
    if ($type == null) {
        $blockTypes['misc'][] = $block;
    } else {
        if (!array_key_exists($type, $blockTypes)) {
            $blockTypes[$type] = array();
        }
        $blockTypes[$type][] = $block;
    }
}

?>
<section class="blockify-dashboard">

    <header>
        <div class="branding col-sm-3">
            <div class="name">Blockify</div>
            <div class="description">Version <?= BLOCKIFY_VERSION ?></div>
        </div>
    </header>

    <section id="library">
        <aside class="col-sm-3">
<?php
            foreach ($blockTypes as $groupName => $group) {
?>
            <div class="group" style="background: <?= $blockTypeColors[$groupName]; ?>">
                <!--<header>
                    <?= $groupName; ?>
                </header>-->
<?php

                foreach( $group as $block ) {
?>
                    <div class="block">
                        <div class="name"><?= $block['json']['name']; ?></div>
                        <div class="basename"><?= $block['basename']; ?></div>
                        <div class="description"><?= $block['description']; ?></div>
                        <a href="#block-<?= $block['basename']; ?>"></a>
                    </div>
<?php
                }
?>
                </div>
<?php
            }
?>
        </aside>
        <div class="content col-md-9">
            <div class="block-tabs">
<?php
                foreach( $blocks as $block ) {
?>
                    <article class="block-info" id="block-<?= $block['basename']; ?>">
                        <div class="name"><?= $block['json']['name']; ?></div>
                        <div class="basename"><?= $block['basename']; ?></div>
                        <div class="description"><?= $block['description']; ?></div>
<?php
                        if( $block['screenshot'] != false ) :
                            $url = str_replace('\\', '/', str_replace(BLOCKIFY_PATH, BLOCKIFY_URL, $block['screenshot']));
?>
                            <img src="<?= $url; ?>" alt="<?= $block['json']['name']; ?>" />
<?php
                        endif;
?>
                    </article>
<?php
                }
?>
            </div>
        </div>
        <script type="text/javascript">
            (function($) {
                $('#library aside .block a').click(function(event) {
                    $('#library > aside .block').removeClass('active');
                    $(this).closest('.block').addClass('active')
                    $('#library .block-tabs .block-info').addClass('hidden');
                    $($(this).attr('href')).removeClass('hidden');
                }).first().trigger('click');
                $('body,html').css({
                    overflow: 'hidden',
                    height: '100%',
                    'min-height': '512px'
                });
            })(jQuery);
        </script>
    </section>
</section>
