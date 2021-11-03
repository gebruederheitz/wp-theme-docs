<?php

namespace Gebruederheitz\Wordpress\Documentation;

class DocumentationMenu
{
    public const MENU_SLUG = 'ghwp-help';

    public const HOOK_SECTIONS = 'ghwp_filter_documentation_sections';

    protected const OVERRIDE_PATH = 'template-parts/meta/docs/documentation-page.php';

    protected const MENU_LOCATION = 'themes.php';

    protected const MENU_TITLE = 'Theme-Hilfe';

    protected const MENU_TITLE_NAMESPACE = 'ghwp';

    protected const PAGE_TEMPLATE_PATH = __DIR__ . '/../templates/documentation-page.php';

    protected $title = 'Hilfe zum /ghWP Theme';

    protected $overridePath = '';

    public function __construct(?string $title = null, ?string $overridePath = null)
    {
        if (!empty($title)) {
            $this->title = $title;
        }

        $this->overridePath = $overridePath ?: static::OVERRIDE_PATH;

        add_action('admin_menu', [$this, 'onAdminMenu']);
    }

    public function getSections(): array
    {
        return apply_filters(static::HOOK_SECTIONS, []);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function onAdminMenu()
    {
        $this->registerSubmenu();
    }

    public function renderSubmenu()
    {
        $templatePathUsed = static::PAGE_TEMPLATE_PATH;

        if ($overriddenTemplate = locate_template($this->overridePath)) {
            $templatePathUsed = $overriddenTemplate;
        }

        load_template($templatePathUsed, false, [$this]);
    }

    protected function registerSubmenu()
    {
        add_submenu_page(
            static::MENU_LOCATION,
            __(static::MENU_TITLE, static::MENU_TITLE_NAMESPACE),
            __(static::MENU_TITLE, static::MENU_TITLE_NAMESPACE),
            'edit_posts',
            static::MENU_SLUG,
            [$this, 'renderSubmenu']
        );
    }
}
