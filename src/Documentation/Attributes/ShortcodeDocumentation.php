<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes;

use Attribute;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcodes;

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

    public static function register(object $instance): void
    {
        add_filter(
            Shortcodes::HOOK_SHORTCODE_DOCS,
            fn($classes) => [...$classes, get_class($instance)],
        );
    }

    public function hasParameters(): bool
    {
        return (bool) count($this->parameters);
    }
}
