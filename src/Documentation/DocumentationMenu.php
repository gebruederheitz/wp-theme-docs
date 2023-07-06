<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation;

use Gebruederheitz\Wordpress\AdminPage\AdminPage;

class DocumentationMenu extends AdminPage
{
    public const MENU_SLUG = 'ghwp-help';

    protected const MENU_LOCATION = 'themes.php';

    protected const MENU_TITLE = 'Theme-Help';

    protected const MENU_TITLE_NAMESPACE = 'ghwp';

    public function __construct(
        ?array $sections = [],
        ?string $title = null,
        ?string $overridePath = null
    ) {
        parent::__construct(
            static::MENU_SLUG,
            $title ?: 'Help for the /ghWP theme',
            static::MENU_LOCATION,
            static::MENU_TITLE,
            $overridePath,
            static::MENU_TITLE_NAMESPACE,
        );

        add_action('admin_menu', [$this, 'onAdminMenu']);

        if (!empty($sections)) {
            $this->addSections($sections);
        }
    }
}
