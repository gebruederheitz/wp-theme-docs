<?php
/**
 * @var \Gebruederheitz\Wordpress\AdminPage\AdminPage $page
 * @var \Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Generic $sections
 */
[$page, $sections] = $args;

function renderAnchor($section): string
{
    if (!empty($section->anchor)) {
        return ' id="' . esc_attr($section->anchor) . '"';
    }
    return '';
}
?>
<div class="test">
    <?php foreach ($sections->getSections() as $section): ?>
        <h2<?= renderAnchor($section) ?>><?= esc_html($section->title) ?></h2>
        <div class="section-content">
            <?= $sections->parseMarkdown($section) ?>
        </div>
    <?php endforeach; ?>
</div>
