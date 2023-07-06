<?php

namespace Gebruederheitz\Wordpress\AdminPage;

use Gebruederheitz\Wordpress\AdminPage\AdminPage;
use Gebruederheitz\Wordpress\AdminPage\Documentation\DocumentationMenu;
use Gebruederheitz\Wordpress\AdminPage\AdminPageSectionInterface;

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

    public function render(AdminPage $page)
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
