<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Section;

use Gebruederheitz\Wordpress\AdminPage\AbstractAdminPageSection;
use Gebruederheitz\Wordpress\AdminPage\AdminPageSectionInterface;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\DocumentationSection;
use Gebruederheitz\Wordpress\AdminPage\Helper\AttributeReader;

class Generic extends AbstractAdminPageSection implements
    AdminPageSectionInterface
{
    /**
     * @hook ghwp_filter_documentation_sections: Classes with DocumentationSection attributes.
     */
    public const HOOK_DOC_SECTIONS = 'ghwp_filter_documentation_sections';

    public function __construct()
    {
    }

    /**
     * @return array<DocumentationSection>
     */
    public function getSections(): array
    {
        $annotatedClasses = apply_filters(self::HOOK_DOC_SECTIONS, []);

        sort($annotatedClasses);

        return array_map(function ($className) {
            return AttributeReader::getDocumentationSection($className);
        }, $annotatedClasses);
    }

    public function getTitle(): string
    {
        return 'Modules';
    }

    public function parseMarkdown(DocumentationSection $section): string
    {
        if (empty($section->description)) {
            return '';
        }

        if ($section->markdown && class_exists('\FastVolt\Helper\Markdown')) {
            return \FastVolt\Helper\Markdown::new(sanitize: true)
                ->setContent($section->description)
                ->getHtml();
        }

        return $section->description;
    }

    protected function getDefaultPartial(): string
    {
        return __DIR__ . '/../../../templates/generic.php';
    }
}
