<?php
    /**
     * @var \Gebruederheitz\Wordpress\AdminPage\AdminPage $page
     * @var \Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcodes $shortcodes
     */
    [$page, $shortcodes] = $args;
?>
<table class="wp-list-table widefat striped table-view-list">
    <thead>
    <tr>
        <th><?= __('Shortcode', $page->getI18nNamespace()) ?></th>
        <th colspan="2"><?= __('Parameter', $page->getI18nNamespace()) ?></th>
        <th><?= __('Beschreibung', $page->getI18nNamespace()) ?></th>
        <th><?= __('Beispiele', $page->getI18nNamespace()) ?></th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach ($shortcodes->getDocumentedShortcodes() as $doc) {
            $shortcodes->renderRow($doc);
        }
    ?>
    </tbody>
</table>
