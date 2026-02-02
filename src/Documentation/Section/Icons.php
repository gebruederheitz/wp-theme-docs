<?php

namespace Gebruederheitz\Wordpress\AdminPage\Documentation\Section;

use Gebruederheitz\Wordpress\AdminPage\AdminPageSectionInterface;
use Gebruederheitz\Wordpress\AdminPage\AbstractAdminPageSection;

use function get_theme_file_path;

class Icons extends AbstractAdminPageSection implements
    AdminPageSectionInterface
{
    protected string $title = 'Available Icon-Partials';

    protected string $iconsPath = '/template-parts/svg/';

    public function __construct(?string $title = null)
    {
        if (!empty($title)) {
            $this->title = $title;
        }
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    protected function getDefaultPartial(): string
    {
        return __DIR__ . '/../../../templates/icons.php';
    }

    protected function getOverridePath(): string
    {
        return 'template-parts/meta/docs/icons.php';
    }

    public function setIconsPath(string $iconsPath)
    {
        $this->iconsPath = $iconsPath;
    }

    public function getIconList(): array
    {
        $iconDir = get_theme_file_path() . $this->iconsPath;
        $icons = glob($iconDir . '{**/*.php,*.php}', GLOB_BRACE);

        $escapedIconDir = preg_quote($iconDir, '/');
        $pattern = '/' . $escapedIconDir . '(.*)\.php/';

        return array_map(function ($fullFilePath) use ($pattern) {
            return preg_filter($pattern, '$1', $fullFilePath);
        }, $icons);
    }

    public function getIconPrettyName($iconSlug): string
    {
        $slugTail = preg_filter('/(?:.*\/)?(\w+\-?+)/', "$1", $iconSlug);
        $parts = explode('-', $slugTail);
        $upperFirstParts = array_map('ucfirst', $parts);

        return implode(' ', $upperFirstParts);
    }
}
