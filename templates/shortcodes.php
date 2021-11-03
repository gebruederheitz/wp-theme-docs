<?php
    /**
     * @var \Gebruederheitz\Wordpress\Documentation\DocumentationMenu $docs
     * @var \Gebruederheitz\Wordpress\Documentation\Sections\Shortcodes $shortcodes
     */
    [$docs, $shortcodes] = $args;
?>
<table class="wp-list-table widefat fixed striped table-view-list">
    <thead>
    <tr>
        <th><?= __('Shortcode', 'ghwp') ?></th>
        <th colspan="2"><?= __('Parameter', 'ghwp') ?></th>
        <th><?= __('Beschreibung', 'ghwp') ?></th>
        <th> __('Beispiele', 'ghwp') ?></th>
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
