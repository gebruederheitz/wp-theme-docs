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
            Shortcodes::HOOK_SHORTCODE_DOCS,
            fn($classes) => [...$classes, $class],
        );
    }

    public function hasParameters(): bool
    {
        return (bool) count($this->parameters);
    }
}
