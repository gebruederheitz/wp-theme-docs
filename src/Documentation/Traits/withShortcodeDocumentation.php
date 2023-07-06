<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Traits;

use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcodes;

use function add_filter;

trait withShortcodeDocumentation
{
    public static function addDocumentation(): void
    {
        add_filter(Shortcodes::HOOK_SHORTCODE_DOCS, [
            static::class,
            'onShortcodeDocs',
        ]);
    }

    public static function onShortcodeDocs(array $documentedClasses): array
    {
        $documentedClasses[] = static::class;
        return $documentedClasses;
    }
}
