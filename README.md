# Wordpress Theme Documentation Maker

_Add a Wordpress theme documentation page to the editor._

---

## Installation

via composer:
```shell
> composer require gebruederheitz/wp-theme-docs
```

Make sure you have Composer autoload or an alternative class loader present.

## Usage

```php
# functions.php (or controller class)
use Gebruederheitz\Wordpress\Documentation\DocumentationPage;

new DocumentationPage();
```

This will set up the basic documentation page user the "Appearance" tab in
the Wordpress backend with the translated title `__('Theme-Hilfe', 'ghwp')`
(`/wordpress/wp-admin/themes.php?page=ghwp-help`).

In its most basic state, this renders a page empty except for the title. You
will need to [register sections](#adding-sections) to add some content.


### Changing the title and the template used

You can change the title displayed on the documentation page by passing a string
as the constructor's first argument:

```php
new \Gebruederheitz\Wordpress\Documentation\DocumentationMenu('Theme Docs!');
```

You can override the default template by creating a file in your theme's root
directory under `template-parts/meta/docs/documentation-page.php`.

Alternatively you may override this default path by supplying a second argument
to the constructor:

```php
new DocumentationMenu(null, 'template-parts/special/docs.php');
```

### Changing the menu's location, slug and label

For even more customization you'll have to extend the `DocumentationMenu` class
to override some constants.

```php
class MyDocumentationPage extends DocumentationMenu {
    public const MENU_SLUG = 'namespace-theme-help';
    protected const MENU_LOCATION = 'themes.php';
    protected const MENU_TITLE = 'Theme-Hilfe';
    protected const MENU_TITLE_NAMESPACE = 'ghwp';
}
```


### Adding sections

#### Section registration & custom sections

Any section you add must implement the `DocumentationSectionInterface`. Use the
filter hook to add your section to the page:

```php
use Gebruederheitz\Wordpress\Documentation\DocumentationMenu ;

function ghwp_add_doc_section(array $sections) {
    $sections[] = new MyDocSection();
    
    return $sections; 
}

add_filter(DocumentationMenu::HOOK_SECTIONS, 'ghwp_add_doc_section');
```

For convenience, you can extend the `AbstractDocumentationSection`, where the
hook is automatically called and the interface is already implemented, only 
requiring you to set two properties to get it to run.

```php
use Gebruederheitz\Wordpress\Documentation\Sections\AbstractDocumentationSection;

class MyDocsSection extends AbstractDocumentationSection {
    protected $title = 'Documentation for my amazing feature';
    protected $partial = __DIR__. '/../template-parts/meta/docs/feature.php'
    
    /* Optional: Expose public methods for your template to use */
    public function getSomeData() {
        return [/* ... */];
    }
}

/* 
    AbstractDocumentationSection extends Gebruederheitz\SimpleSingleton, so
    instead of constructing a new instance ourselves, we just make sure an
    instance has been created at some point:
*/
MyDocsSection::getInstance();
```

This sets up a section with the title `Documentation for my amazing feature` 
rendering from the template partial at the given path.
The template is passed the instance of `DocumentationMenu` and the current
section:

```php
<?php # template-parts/meta/docs/feature.php
    [$docs, $section] = $args;
?>
<ul>
    <?php foreach ($section->getSomeData() as $datum): ?>
        <li>
            <!-- Some content -->
        </li>
    <?php endforeach; ?>
</ul>
```

Instead of setting the `$partial` property (or even extending the abstract class), 
you can also implement a custom rendering method:

```php
use Gebruederheitz\Wordpress\Documentation\DocumentationSectionInterface
use Gebruederheitz\Wordpress\Documentation\Sections\AbstractDocumentationSection;

class MySecondDocsSection extends AbstractDocumentationSection {
    protected $title = 'Documentation for my amazing feature';
    
    public function render(DocumentationMenu $docs) 
    {
        get_template_part('template-parts/meta/docs/documentation', 'page', [$docs, $this]);
    }
}

MySecondDocsSection::getInstance();


class MyThirdDocsSection implements DocumentationSectionInterface {
    
    /*
     * In this case we'll have to take care of registerin the section with the
     * DocumentationPage ourselves:
     */
    public function __construct() 
    {
       add_filter(DocumentationMenu::HOOK_SECTIONS, [$this, 'onDocumentationSections'])
    }
    
    public function onDocumentationSections(array $sections): array
    {
        $sections[] = $this;
        return $sections;
    } 
    
    public function getTitle(): string 
    {
        return 'My Third Docs Section is the best to date!';
    }
    
    public function render(DocumentationMenu $docs) 
    {
        get_template_part('template-parts/meta/docs/documentation', 'page', [$docs, $this]);
    }
    
}

new MyThirdDocsSection();
```


#### Included sections

The sections included in this package all extend `AbstractDocumentationSection`,
so they can simply be set up by instantiating the singleton:

```php
use Gebruederheitz\Wordpress\Documentation\Sections\Shortcodes;
use Gebruederheitz\Wordpress\Documentation\Sections\Icons;

Shortcodes::getInstance();
Icons::getInstance();
```

This will automatically register them and add them to the help page.



### Shortcode Documentation Annotations

This module will automatically create a shortcode documentation for users based
on the annotation class `ShortcodeDocumentation`. It is based on Doctrine's
annotation reader.

#### Documenting shortcodes

This assumes you add your shortcodes via individual classes for each shortcode
and have both the `DocumentationMenu` page class and the `Shortcodes` section
class up and running.

##### Available annotation properties:

| Property | Type |  Description |
| --- | --- | --- |
| `shortcode` | string | The actual shortcode that you use for registration. |
| `description` | string | A short description of what your shortcode is good for. |
| `parameters` | map | A map of parameter names and their descriptions, so users will know which parameters to pass and when. |
| `examples` | string list | A list of strings that will be wrapped in `<pre><code></code></pre>`, providing your users some examples of the shortcode's usage. |


The simplest way to document your Shortcode is to use the trait provided:

```php
use Gebruederheitz\Wordpress\Documentation\Traits\withShortcodeDocumentation;
use Gebruederheitz\Wordpress\Documentation\Annotations\ShortcodeDocumentation;

/**
 * Class MyShortcode
 *
 * @package Ghwp\Shortcode
 *
 * @ShortcodeDocumentation(
 *     shortcode="ghwp-my-shortcode",
 *     description="Renders a thing.",
 *     parameters={
 *       "id": "The post ID you wish to display."
 *     },
 *     examples={
 *       "[ghwp-my-shortcode id=123 /]"
 *     }
 *  )
 */
class MyShortcode {
    use withShortcodeDocumentation;
    
    /* With a dynamic class instance... */
    public function __construct() 
    {
        self::addDocumentation();
        add_shortcode('ghwp-my-shortcode', [$this, 'renderShortcode'));
    }
    
    /* ...or with a static class / method */
    public static function init() 
    {
        self::addDocumentation();
        add_shortcode('ghwp-my-shortcode', [self::class, 'renderShortcode']);
    }
}

MyShortcode::init();
/* or*/
new MyShortcode();
```

Alternatively, you can register your annotation yourself:

```php
use Gebruederheitz\Wordpress\Documentation\Annotations\ShortcodeDocumentation;
use Gebruederheitz\Wordpress\Documentation\Sections\Shortcodes;

/**
 * @ShortcodeDocumentation(
 *     shortcode="ghwp-my-shortcode",
 *     description="Renders a thing.",
 *     parameters={
 *       "id": "The post ID you wish to display."
 *     },
 *     examples={
 *       "[ghwp-my-shortcode id=123 /]"
 *     }
 *  )
 */
class MyShortcode {
    
    /* With a dynamic class instance... */
    public function __construct() 
    {
        add_filter(Shortcodes::HOOK_SHORTCODE_DOCS, [$this, 'onShortcodeDocs']);
        add_shortcode('ghwp-my-shortcode', [$this, 'renderShortcode'));
    }
    
    public function onShortcodeDocs(array $docs) 
    {
        $docs[] = self::class;
        
        return $docs;
    }
}
```


#### Changing the template partial for the section

As with all section classes extending `AbstractDocumentationSection`, you have
two options of modifying the default output:

##### Using the default override template

Create a file in your theme's root directory under `template-parts/meta/docs/shortcodes.php`.
This template will be used instead of the provided default.

The section template will be called with two arguments: an instance each of
`DocumentationMenu` and the section (`Shortcodes`):

```php
<?php # template-parts/meta/docs/shortcodes.php
    [$docs, $section] = $args;
?>
```

##### Using a different template partial location

```php
Shortcodes::getInstance()->setTemplatePath(
    'template-parts/special/shortcode-docs.php',
);
```


### List of SVG icon partials

This module will render a list of all icons that can be used by editors (for 
instance via shortcodes). By default it searches `template-parts/svg/` in your
theme's root directory recursively for `.php` files, assuming they are templates
containing SVG markup.

This is most useful in conjunction with a shortcode that can render these SVG
partials, for instance into button blocks.

#### Setting a different path for SVG partials

```php
Icons::getInstance()->setIconsPath('template-parts/icons/');
```



## Development

### Dependencies

- PHP >= 7.4
- [Composer 2.x](https://getcomposer.org)
- [NVM](https://github.com/nvm-sh/nvm) and nodeJS LTS (v16.x)
- Nice to have: GNU Make (or drop-in alternative)
