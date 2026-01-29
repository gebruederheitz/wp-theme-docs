<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Section;

use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\ShortcodeDocumentation;
use Gebruederheitz\Wordpress\AdminPage\AdminPageSectionInterface;
use Gebruederheitz\Wordpress\AdminPage\Helper\AttributeReader;
use Gebruederheitz\Wordpress\AdminPage\AbstractAdminPageSection;

use function apply_filters;
use function load_template;

class Shortcodes extends AbstractAdminPageSection implements
    AdminPageSectionInterface
{
    /**
     * @hook ghwp_filter_documented_shortcodes: Classes with ShortcodeDocumentation annotations.
     */
    public const HOOK_SHORTCODE_DOCS = 'ghwp_filter_documented_shortcodes';

    protected $title = 'Available Shortcodes';

    public function __construct(?string $title = null)
    {
        if (!empty($title)) {
            $this->title = $title;
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    protected function getDefaultPartial(): string
    {
        return __DIR__ . '/../../../templates/shortcodes.php';
    }

    protected function getOverridePath(): string
    {
        return 'template-parts/meta/docs/shortcodes.php';
    }

    public function getDocumentedShortcodes(): array
    {
        $annotatedShortcodeClasses = apply_filters(
            self::HOOK_SHORTCODE_DOCS,
            [],
        );

        sort($annotatedShortcodeClasses);

        return array_map(function ($className) {
            return AttributeReader::getShortcodeDocumentation($className);
        }, $annotatedShortcodeClasses);
    }

    public function renderRow(ShortcodeDocumentation $doc): void
    {
        load_template(
            __DIR__ . '/../../../templates/shortcode-table-row.php',
            false,
            [$doc],
        );
    }
}
