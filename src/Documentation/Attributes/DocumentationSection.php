<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes;

use Attribute;

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
}
