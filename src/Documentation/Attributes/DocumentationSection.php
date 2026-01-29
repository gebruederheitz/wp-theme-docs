<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes;

use Attribute;
use Gebruederheitz\Wordpress\AdminPage\AdminPage;

#[Attribute(Attribute::TARGET_CLASS)]
class DocumentationSection
{
    public function __construct(
        public string $title,
        public string $description,
        public ?string $anchor = null,
        public bool $markdown = false
    ) {
    }

    public static function register(object $instance): void
    {
        add_filter(
            AdminPage::HOOK_DOC_SECTIONS,
            fn($classes) => [...$classes, get_class($instance)],
        );
    }
}
