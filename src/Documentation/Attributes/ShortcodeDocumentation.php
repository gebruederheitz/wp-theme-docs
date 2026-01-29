<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ShortcodeDocumentation
{
    /**
     * @param array<string, string> $parameters
     * @param array<string> $examples
     */
    public function __construct(
        public string $shortcode,
        public string $description,
        public array $parameters,
        public array $examples
    ) {
    }

    public function hasParameters(): bool
    {
        return (bool) count($this->parameters);
    }
}
