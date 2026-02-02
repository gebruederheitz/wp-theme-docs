<?php

use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\ShortcodeDocumentation;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\DocumentationSection;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\WithShortcodeDocumentation;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\WithDocumentationSection;

/**
 * @package demo
 */
/*
Plugin Name: demo
Plugin URI: https://gebruederheitz.de
Description: demo plugin for testing docs
Version: 1.0
Requires at least: 6.9
Requires PHP: 8.2
Author: Andreas Gallus <andreas.gallus@gebruederheitz.de>
Author URI: https://gebruederheitz.de
License: GPLv2 or later
Text Domain: ghwp
*/

#[
    ShortcodeDocumentation(
        shortcode: 'my-shortcode',
        description: 'A dummy class representing a shortcode with documentation.',
        parameters: [
            'param1' => 'Description for param1',
            'param2' => 'Description for param2',
        ],
        examples: [
            '[my-shortcode param1="value1" param2="value2"]',
            '[my-shortcode param1="anotherValue"]',
        ]
    )
]
class MyShortcode
{
    use WithShortcodeDocumentation;

    public function __construct()
    {
        $this->addDocumentation();
    }
}

#[
    ShortcodeDocumentation(
        shortcode: 'my-other-shortcode',
        description: 'A dummy class representing a shortcode with documentation.',
    )
]
class MyOtherShortcode
{
    public function __construct()
    {
        ShortcodeDocumentation::register($this);
    }
}

#[
    DocumentationSection(
        title: 'My Documented Module',
        description: <<< 'EOF'
        This module demonstrates how to document a module using documentation sections.
        
         - We can use Markdown
         - Like this list
         - or **bold text** and _italic text_
         
        ```js
        window.alert('Documenting things is fun!');
        ```

        EOF,
        anchor: 'my-documented-module',
        markdown: true
    )
]
#[DocumentationSection('Second Section for the same module!', 'Let\'s go crazy with these docs!')]
class MyDocumentedModule
{
    use WithDocumentationSection;

    public function __construct()
    {
        $this->addDocumentation();
    }
}

#[
    DocumentationSection(
        title: 'Another Documented Module',
        description: 'Hello.'
    )
]
class OtherDocumentedModule
{
    public static function init(): void
    {
        DocumentationSection::register(self::class);
    }
}

new MyShortcode();
new MyOtherShortcode();
new MyDocumentedModule();
OtherDocumentedModule::init();
