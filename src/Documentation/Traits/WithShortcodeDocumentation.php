<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Traits;

use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcodes;

use function add_filter;

/**
 * @deprecated Register the attribute through itself instead:
 *            ```ShortcodeDocumentation::register($this)```
 */
trait WithShortcodeDocumentation
{
    public static function addDocumentation(): void
    {
        add_filter(
            Shortcodes::HOOK_SHORTCODE_DOCS,
            fn($classes) => [...$classes, static::class],
        );
    }
}
