<?php
    $block->open();
?>
    <div class="row">
        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
            <?php
            $block->document->tag('h1', 'name');
            $block->document->tag('h4', 'description');

            if (!empty($block->options['buttons'])) {
                block('blockify-buttons', $block->options['buttons'], false, false);
            }
            ?>
        </div>
        <div class="col-sm-10 col-sm-offset-1">
            <div class="window">
                <header>
                    <div class="circle red"></div>
                    <div class="circle yellow"></div>
                    <div class="circle green"></div>
                </header>
                <div class="page">
                    <?php $block->document->tag('img', 'image', ['class'=>'img-responsive']); ?>
                </div>
            </div>
        </div>
    </div>
<?php
    $block->close();
