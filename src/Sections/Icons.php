<?php

namespace Gebruederheitz\Wordpress\Documentation\Sections;

use Gebruederheitz\Wordpress\Documentation\DocumentationSectionInterface;

class Icons
    extends AbstractDocumentationSection
    implements DocumentationSectionInterface
{
    protected const OVERRIDE_PATH = 'template-parts/meta/docs/icons.php';

    protected $title = 'Verfügbare Icon-Partials';

    protected $partial = __DIR__ . '/../../templates/icons.php';

    protected $iconsPath = '/template-parts/svg/';

    public function setIconsPath(string $iconsPath)
    {
        $this->iconsPath = $iconsPath;
    }

    public function getIconList(): array
    {
        $iconDir = get_theme_file_path() . $this->iconsPath;
        $icons = glob($iconDir.'{**/*.php,*.php}', GLOB_BRACE);

        $escapedIconDir = preg_quote($iconDir, '/');
        $pattern = '/' . $escapedIconDir . '(.*)\.php/';

        return array_map(
            function($fullFilePath) use ($pattern) {
                return preg_filter($pattern, '$1', $fullFilePath);
            },
            $icons
        );
    }

    public function getIconPrettyName($iconSlug): string
    {
        $slugTail = preg_filter('/(?:.*\/)?(\w+\-?+)/', "$1", $iconSlug);
        $parts = explode('-', $slugTail);
        $upperFirstParts = array_map('ucfirst', $parts);

        return implode(' ', $upperFirstParts);
    }
}
