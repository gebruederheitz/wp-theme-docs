<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Traits;

use Gebruederheitz\Wordpress\AdminPage\AdminPage;

/**
 * @deprecated
 *   Register the attribute through itself instead: ```DocumentationSection::register($this)```
 */
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
