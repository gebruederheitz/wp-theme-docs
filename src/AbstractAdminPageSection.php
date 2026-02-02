<?php

namespace Gebruederheitz\Wordpress\AdminPage;

use function load_template;
use function locate_template;

abstract class AbstractAdminPageSection implements AdminPageSectionInterface
{
    abstract public function getTitle(): string;
    abstract protected function getDefaultPartial(): string;

    protected function getOverridePath(): string
    {
        return '';
    }

    public function render(AdminPage $page): void
    {
        load_template($this->getPartial(), false, [$page, $this]);
    }

    protected function getPartial(): string
    {
        $templatePathUsed = $this->getDefaultPartial();

        if ($overriddenTemplate = locate_template($this->getOverridePath())) {
            $templatePathUsed = $overriddenTemplate;
        }

        return $templatePathUsed;
    }

    public function onPageSections(array $sections): array
    {
        $sections[] = $this;

        return $sections;
    }
}
