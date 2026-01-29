<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

use Gebruederheitz\Wordpress\AdminPage\AdminPage;
use Gebruederheitz\Wordpress\AdminPage\Documentation\DocumentationMenu;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcodes;

AdminPage::factory(
    menuSlug: 'test',
    title: 'Admin Page Test',
    menuLocation: 'tools.php',
    menuTitle: 'Admin Page Test',
    i18nNamespace: '',
);
$docs = new DocumentationMenu(
    sections: [new Shortcodes()],
    processGenericSections: true,
);
