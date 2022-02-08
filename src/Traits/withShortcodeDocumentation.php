<?php

namespace Gebruederheitz\Wordpress\Documentation\Traits;

use Gebruederheitz\Wordpress\Documentation\Sections\Shortcodes;

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
