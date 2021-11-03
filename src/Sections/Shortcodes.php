<?php

namespace Gebruederheitz\Wordpress\Documentation\Sections;

use Gebruederheitz\Wordpress\Documentation\Annotations\ShortcodeDocumentation;
use Gebruederheitz\Wordpress\Documentation\DocumentationSectionInterface;
use Gebruederheitz\Wordpress\Documentation\Helper\AnnotationReader;

class Shortcodes
    extends AbstractDocumentationSection
    implements DocumentationSectionInterface
{
    /**
     * @hook ghwp_filter_documented_shortcodes: Classes with ShortcodeDocumentation annotations.
     */
    public const HOOK_SHORTCODE_DOCS = 'ghwp_filter_documented_shortcodes';

    protected const OVERRIDE_PATH = 'template-parts/meta/docs/shortcodes.php';

    protected $title = 'Verfügbare Shortcodes';

    protected $partial = __DIR__ . '/../../templates/shortcodes.php';

    public function getDocumentedShortcodes(): array
    {
        $annotatedShortcodeClasses = apply_filters(
            self::HOOK_SHORTCODE_DOCS,
            [],
        );

        sort($annotatedShortcodeClasses);

        return array_map(
            function($className) { return AnnotationReader::getDocumentation($className);},
            $annotatedShortcodeClasses
        );
    }

    public function renderRow(ShortcodeDocumentation $doc)
    {
        load_template(__DIR__ . '/../../templates/shortcode-table-row.php', false, [$doc]);
    }
}
