<?php

namespace Gebruederheitz\Wordpress\AdminPage;

use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\DocumentationSection;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Generic;
use Gebruederheitz\Wordpress\AdminPage\Helper\AttributeReader;
use ReflectionException;

class AdminPage
{
    /**
     * @hook ghwp_filter_documentation_sections: Classes with DocumentationSection attributes.
     */
    public const HOOK_DOC_SECTIONS = 'ghwp_filter_documentation_sections';

    protected const HOOK_SECTIONS_PREFIX = 'ghwp_filter_sections_';

    protected const DEFAULT_OVERRIDE_PATH = 'template-parts/meta/docs/documentation-page.php';

    protected const PAGE_TEMPLATE_PATH =
        __DIR__ . '/../templates/documentation-page.php';

    /** @var string */
    protected $title = 'Extras';

    /** @var string */
    protected $menuSlug;

    /** @var string */
    protected $menuLocation = 'themes.php';

    /** @var string */
    protected $menuTitle;

    /** @var string */
    protected $overridePath = '';

    /** @var string */
    protected $i18nNamespace = 'ghwp';

    public static function factory(
        string $menuSlug,
        ?string $title = null,
        ?string $menuLocation = 'themes.php',
        ?string $menuTitle = null,
        ?string $overridePath = null,
        ?string $i18nNamespace = 'ghwp'
    ): AdminPage {
        return new AdminPage(
            $menuSlug,
            $title,
            $menuLocation,
            $menuTitle,
            $overridePath,
            $i18nNamespace,
        );
    }

    public function __construct(
        string $menuSlug,
        ?string $title = null,
        ?string $menuLocation = 'themes.php',
        ?string $menuTitle = null,
        ?string $overridePath = null,
        ?string $i18nNamespace = 'ghwp'
    ) {
        $this->menuSlug = $menuSlug;
        $this->overridePath = $overridePath ?: static::DEFAULT_OVERRIDE_PATH;
        $this->i18nNamespace = $i18nNamespace;

        if (!empty($title)) {
            $this->title = $title;
        }

        $this->menuTitle = $menuTitle ?: $this->title;

        if (!empty($menuLocation)) {
            $this->menuLocation = $menuLocation;
        }

        add_action('admin_menu', [$this, 'onAdminMenu']);
    }

    public function getSectionsHook(): string
    {
        return self::HOOK_SECTIONS_PREFIX . $this->menuSlug;
    }

    public function addSection(AdminPageSectionInterface $section): self
    {
        add_filter($this->getSectionsHook(), [$section, 'onPageSections']);

        return $this;
    }

    /**
     * @param array<AdminPageSectionInterface> $sections
     */
    public function addSections(array $sections): self
    {
        $hook = $this->getSectionsHook();
        foreach ($sections as $section) {
            add_filter($hook, [$section, 'onPageSections']);
        }

        return $this;
    }

    public function getI18nNamespace(): string
    {
        return $this->i18nNamespace;
    }

    public function getSections(): array
    {
        return apply_filters($this->getSectionsHook(), []);
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

    public function processGenericSections(): self
    {
        $sectionAttributes = $this->getGenericSections();
        $sections = array_map(function (
            DocumentationSection $sectionAttribute
        ) {
            return new Generic($sectionAttribute);
        },
        $sectionAttributes);

        $this->addSections($sections);

        return $this;
    }

    /**
     * @return array<DocumentationSection>
     * @throws ReflectionException
     */
    private function getGenericSections(): array
    {
        $annotatedClasses = apply_filters(self::HOOK_DOC_SECTIONS, []);

        sort($annotatedClasses);

        return array_map(function ($className) {
            return AttributeReader::getDocumentationSection($className);
        }, $annotatedClasses);
    }

    protected function registerSubmenu()
    {
        add_submenu_page(
            $this->menuLocation,
            __($this->menuTitle, $this->i18nNamespace),
            __($this->menuTitle, $this->i18nNamespace),
            'edit_posts',
            $this->menuSlug,
            [$this, 'renderSubmenu'],
        );
    }
}
