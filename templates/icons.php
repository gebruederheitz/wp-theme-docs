<?php
    /**
     * @var \Gebruederheitz\Wordpress\AdminPage\AdminPage $page
     * @var \Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Icons $icons
     */
    [$page, $icons] = $args;
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
        <th><?= __('Partial-Name', $page->getI18nNamespace()) ?></th>
        <th><?= __('Preview', $page->getI18nNamespace()) ?></th>
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
