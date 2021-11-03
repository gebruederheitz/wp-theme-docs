<?php

namespace Gebruederheitz\Wordpress\Documentation;

interface DocumentationSectionInterface
{
    public function getTitle(): string;

    public function render(DocumentationMenu $docs);
}
