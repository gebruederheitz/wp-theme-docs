<?php

namespace Gebruederheitz\Wordpress\Documentation\Sections;

use Gebruederheitz\SimpleSingleton\Singleton;
use Gebruederheitz\Wordpress\Documentation\DocumentationMenu;
use Gebruederheitz\Wordpress\Documentation\DocumentationSectionInterface;

abstract class AbstractDocumentationSection extends Singleton implements
    DocumentationSectionInterface
{
    protected const OVERRIDE_PATH = '';

    protected $title = '';

    protected $partial = '';

    protected $overridePath = '';

    protected function __construct()
    {
        parent::__construct();
        $this->overridePath = static::OVERRIDE_PATH;
        add_filter(DocumentationMenu::HOOK_SECTIONS, [
            $this,
            'onDocumentationSections',
        ]);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function render(DocumentationMenu $docs)
    {
        load_template($this->getPartial(), false, [$docs, $this]);
    }

    protected function getPartial(): string
    {
        $templatePathUsed = $this->partial;

        if ($overriddenTemplate = locate_template($this->overridePath)) {
            $templatePathUsed = $overriddenTemplate;
        }

        return $templatePathUsed;
    }

    public function onDocumentationSections(array $sections): array
    {
        $sections[] = $this;
        return $sections;
    }

    public function setTemplatePath(string $overridePath = null)
    {
        $this->overridePath = $overridePath;
    }
}
