<?php

namespace Gebruederheitz\Wordpress\AdminPage;

interface AdminPageSectionInterface
{
    public function getTitle(): string;

    public function render(AdminPage $page);

    /**
     * @param array<AdminPageSectionInterface> $sections
     *
     * @return array<AdminPageSectionInterface>
     */
    public function onPageSections(array $sections): array;
}
