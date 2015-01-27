<link href='http://fonts.googleapis.com/css?family=Open+Sans:700,600,400,300' rel='stylesheet' type='text/css'>
<div class="block-test">
  <h1>It's Alive!</h1>
  <p>If you haven't used blockify before, check out the <a href="http://blockify.co/docs/" target="_blank">docs</a>!</p>
  <h1>Developer Info</h1>
  <ul>
    <li>Blockify Version: <?= BLOCKIFY_VERSION ?></li>
    <li>PHP Version: <?= phpversion() ?></li>
    <?php $read_perms = is_readable( BLOCKIFY_BLOCKS_PATH ); ?>
    <li<?= $read_perms ? '' : ' class="has-error"' ?>>
    Blocks Directory: <?= BLOCKIFY_BLOCKS_PATH ?>
    <?= $read_perms ? '' : '<span>Cannot read directory, Please fix <a href="#">permissions</a>!</span>' ?>
  </li>
</ul>
</div>
