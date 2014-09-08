<?php

$block->openTag();

?>
<div class="container">
<?php

echo $block->model->createElement('h1', 'name');
echo $block->model->createElement('p', 'description');

$block->content();

?>
</div>
<?php

$block->closeTag();
