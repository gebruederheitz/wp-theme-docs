<?php
/**
 * @var array{0: \Gebruederheitz\Wordpress\AdminPage\AdminPage, 1: \Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Generic} $args
 * @var \Gebruederheitz\Wordpress\AdminPage\AdminPage $page
 * @var \Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Generic $genericSection
 */
[$page, $genericSection] = $args; ?>
<?= $genericSection->renderAnchor() ?>
<div class="section-content">
    <?= $genericSection->renderContent() ?>
</div>
