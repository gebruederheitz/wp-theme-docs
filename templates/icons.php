<?php
    /**
     * @var \Gebruederheitz\Wordpress\Documentation\DocumentationMenu $docs
     * @var \Gebruederheitz\Wordpress\Documentation\Sections\Icons $icons
     */
    [$docs, $icons] = $args;
?>
<style>
    .wp-core-ui button.button {
        display: flex;
        align-items: center;
        padding: 12px 24px;
    }
    .wp-core-ui button.button svg {
        height: 32px;
        width: 32px;
        margin-right: 12px;
    }
</style>
<table class="wp-list-table widefat fixed striped table-view-list">
    <thead>
    <tr>
        <th><?= __('Partial-Name', 'ghwp') ?></th>
        <th><?= __('Vorschau', 'ghwp') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($icons->getIconList() as $icon): ?>
        <tr>
            <td style="vertical-align: middle;">
                <input type="text" readonly value="<?= $icon ?>" />
            </td>
            <td>
                <button type="button" class="button">
                    <div class="button__left-icon">
                        <?php get_template_part('template-parts/svg/' . stripslashes($icon)); ?>
                    </div>
                    <?= $icons->getIconPrettyName($icon) ?>
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
