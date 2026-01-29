<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Traits;

use Gebruederheitz\Wordpress\AdminPage\AdminPage;

trait WithDocumentationSection
{
    public static function addDocumentation(): void
    {
        add_filter(
            AdminPage::HOOK_DOC_SECTIONS,
            fn($classes) => [...$classes, static::class],
        );
    }
}
