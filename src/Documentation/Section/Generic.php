<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Section;

use Gebruederheitz\Wordpress\AdminPage\AbstractAdminPageSection;
use Gebruederheitz\Wordpress\AdminPage\AdminPageSectionInterface;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\DocumentationSection;

class Generic extends AbstractAdminPageSection implements
    AdminPageSectionInterface
{
    public function __construct(private DocumentationSection $docs)
    {
    }

    public function getTitle(): string
    {
        return $this->docs->title;
    }

    public function renderContent(): string
    {
        if (empty($this->docs->description)) {
            return '';
        }

        if (
            $this->docs->markdown &&
            class_exists('\FastVolt\Helper\Markdown')
        ) {
            return \FastVolt\Helper\Markdown::new(sanitize: true)
                ->setContent($this->docs->description)
                ->getHtml();
        }

        return $this->docs->description;
    }

    public function renderAnchor(): string
    {
        if (!empty($this->docs->anchor)) {
            return '<a href="#" class="ghwp-invisible-anchor" id="' .
                $this->docs->anchor .
                '"></a>';
        }
        return '';
    }

    protected function getDefaultPartial(): string
    {
        return __DIR__ . '/../../../templates/generic.php';
    }
}
