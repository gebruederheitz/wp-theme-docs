<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Traits;

use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Generic;

trait WithDocumentationSection
{
    public static function addDocumentation(): void
    {
        add_filter(Generic::HOOK_DOC_SECTIONS, [
            static::class,
            'onDocSections',
        ]);
    }

    public static function onDocSections(array $documentedClasses): array
    {
        return [...$documentedClasses, static::class];
    }
}
