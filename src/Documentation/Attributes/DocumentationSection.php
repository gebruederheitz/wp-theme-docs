<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes;

use Attribute;
use Gebruederheitz\Wordpress\AdminPage\AdminPage;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class DocumentationSection
{
    public function __construct(
        public string $title,
        public string $description,
        public ?string $anchor = null,
        public bool $markdown = false
    ) {
    }

    /**
     * @param object|class-string $identifier
     */
    public static function register(object|string $identifier): void
    {
        if (is_object($identifier)) {
            $class = get_class($identifier);
        } else {
            $class = $identifier;
        }

        add_filter(
            AdminPage::HOOK_DOC_SECTIONS,
            fn($classes) => [...$classes, $class],
        );
    }
}
