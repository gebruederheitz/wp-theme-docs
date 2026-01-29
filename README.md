# Wordpress Theme Admin Page and Documentation Maker

_Add simply admin pages or a Wordpress theme documentation page to the editor._

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
use Gebruederheitz\Wordpress\Documentation\AdminPage;

new AdminPage('my-extra-admin-page', 'Extra Tools', 'tools.php');
// or use the factory method
AdminPage::factory('my-extra-admin-page', 'Extra Tools', 'tools.php');
```

This will set up a basic admin page under the "Tools" tab in
the Wordpress backend with the translated title `__('Extra Tools', 'ghwp')`
(`/wordpress/wp-admin/tools.php?page=my-extra-admin-page`).

In its most basic state, this renders a page empty except for the title. You
will need to [register sections](#adding-sections) to add some content.

### Constructor (and factory) arguments

```php
AdminPage::__construct(
     string $menuSlug,
     ?string $title = null,
     ?string $menuLocation = 'themes.php',
     ?string $menuTitle = null,
     ?string $overridePath = null,
     ?string $i18nNamespace = 'ghwp'
 ): AdminPage
```

| argument | description |
| --- | --- |
| menuSlug | Where the menu will be available under $menuLocation (`?page={{this}}`) |
| title | A title displayed as `<h1>` at the top of the page, will be run through `__()`. |
| menuLocation | Where to append the new submenu. Popular places are `themes.php` or `tools.php`. |
| menuTitle | An alternative text for the menu entry. Defaults to `$title`. |
| overridePath | An alternative path where consumers can put overrides templates to use instead of `template-parts/meta/docs/documentation-page.php`. |
| i18nNamespace | The namespace used for translations for menu titles, titles and within the default templates (`__(somestring, 'ghwp')`) |

### Adding sections

#### Section registration & custom sections

Any section you add must implement the `AdminPageSectionInterface`. You can
use the method `addSection()` or the filter hook to add your section to a page:

##### using `addSection()`
```php
use Gebruederheitz\Wordpress\Documentation\AdminPage ;
$page = new AdminPage('extras');
$page->addSection(new MySection());

// Using AdminPage's factory method:
AdminPage::factory('extras')->addSection($mySection);

// Adding multiple sections with addSection() or addSections()
AdminPage::factory('extras')
    ->addSection($mySection)
    ->addSection($otherSection);
AdminPage::factory('extras')
    ->addSections([
        new MySection(),
        new OtherSection(),
    ]);
```

##### using the hook
```php
use Gebruederheitz\Wordpress\Documentation\AdminPage ;
$page = new AdminPage('extras');

function ghwp_add_doc_section(array $sections) {
    $sections[] = new MyDocSection();
    
    return $sections; 
}

add_filter($page->getSectionsHook(), 'ghwp_add_doc_section');
// OR, using 'ghwp_filter_sections_' and the page's slug:
add_filter('ghwp_filter_sections_extras', 'ghwp_add_doc_section');
```

For convenience, you can extend the `AbstractAdminPageSection`, which already
has the interface implemented, only requiring you to implement three abstract
getters to get it to run.

```php
use Gebruederheitz\Wordpress\AdminPage\AbstractAdminPageSection;

class MyDocsSection extends AbstractAdminPageSection {
    public function getTitle() : string{
        return 'Documentation for my amazing feature';
    }
    
    protected function getDefaultPartial() : string{
        return __DIR__. '/../template-parts/meta/docs/feature.php';
    }
    /* Optional: Define an override path that can be used instead of your default */
    protected function getOverridePath() : string{
        return get_theme_file_path('template-parts/meta/overrides/feature.php');
    }
    
    /* Optional: Expose public methods for your template to use */
    public function getSomeData() {
        return [/* ... */];
    }
}
```

The above example sets up a section with the title `Documentation for my amazing feature` 
rendering from the template partial at the given path.
The template is passed the instance of `AdminPage` and the current
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
use Gebruederheitz\Wordpress\AdminPage\AdminPageSectionInterface
use Gebruederheitz\Wordpress\AdminPage\AbstractAdminPageSection;

class MySecondDocsSection extends AbstractAdminPageSection {
    /* ... */
    
    /**
     * @override
     */
    public function render(AdminPage $docs) 
    {
        get_template_part('template-parts/meta/docs/documentation', 'page', [$docs, $this]);
    }
}

/** Example of a section not extending the abstract base implementation */
class MyThirdDocsSection implements AdminPageSectionInterface {
    public function onPageSections(array $sections): array
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
```

### The theme documentation page

```php
# functions.php (or controller class)
use Gebruederheitz\Wordpress\AdminPage\Documentation\DocumentationMenu;

new DocumentationMenu();
```

This will set up the basic documentation page under the "Appearance" tab in
the Wordpress backend with the translated title `__('Theme-Help', 'ghwp')`
(`/wordpress/wp-admin/themes.php?page=ghwp-help`).

It's simply a preconfigured `AdminPage`, so you will need to add sections. In
this case, you can add them right through the constructor by passing an array
as the first argument:

```php
new DocumentationMenu([$sectionOne, $sectionTwo]);
```

> **Warning**
> 
> Due to how PHP inheritance works, AdminPage's static `::factory()` method is
> available on DocumentationMenu, but it should not be used, as it will return
> a simple AdminPage object, not a DocumentationMenu instance.


#### DocumentationMenu: Changing the title and the template used

You can change the title displayed on the documentation page by passing a string
as the constructor's second argument:

```php
new \Gebruederheitz\Wordpress\Documentation\DocumentationMenu(null, 'Theme Docs!');
```

You can override the default template by creating a file in your theme's root
directory under `template-parts/meta/docs/documentation-page.php`.

Alternatively you may override this default path by supplying a second argument
to the constructor:

```php
new DocumentationMenu(null, null, 'template-parts/special/docs.php');
```


#### Included sections

The sections included in this package all extend `AbstractAdminPageSection`.

```php
use Gebruederheitz\Wordpress\AdminPage\Documentation\DocumentationMenu;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcodes;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Icons;

new DocumentationMenu(
    'My Theme',
    [
        new Shortcodes(),
        new Icons(),
    ]   
);
// or
$docs = new DocumentationMenu();
$docs->addSection(new Shortcodes())->addSection(new Icons());
// or
$docs->addSections([new Shortcodes(), new Icons()]);
// or
add_filter($docs->getSectionsHook(), function(array $sections): array {
    $sections[] = new Shortcodes();
    
    return $sections;
});
```

This will register the sections and add them to the help page.



#### Shortcode Documentation Annotations

This module will automatically create a shortcode documentation for users based
on the annotation class `ShortcodeDocumentation`. It is based on Doctrine's
annotation reader.

##### Documenting shortcodes

This assumes you add your shortcodes via individual classes for each shortcode
and have both the `DocumentationMenu` page class and the `Shortcodes` section
class up and running.

###### Available annotation properties:

| Property | Type |  Description |
| --- | --- | --- |
| `shortcode` | string | The actual shortcode that you use for registration. |
| `description` | string | A short description of what your shortcode is good for. |
| `parameters` | map | A map of parameter names and their descriptions, so users will know which parameters to pass and when. |
| `examples` | string list | A list of strings that will be wrapped in `<pre><code></code></pre>`, providing your users some examples of the shortcode's usage. |


The simplest way to document your Shortcode is to use the trait provided:

```php
use Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\withShortcodeDocumentation;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Annotations\ShortcodeDocumentation;

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
use Gebruederheitz\Wordpress\AdminPage\Documentation\Annotations\ShortcodeDocumentation;
use Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcodes;

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


##### Changing the template partial for the section

As with all section classes extending `AbstractAdminPageSection`, you have
two options of modifying the default output:

###### Using the default override template

Create a file in your theme's root directory under `template-parts/meta/docs/shortcodes.php`.
This template will be used instead of the provided default.

The section template will be called with two arguments: an instance each of
`DocumentationMenu` and the section (`Shortcodes`):

```php
<?php # template-parts/meta/docs/shortcodes.php
    [$docs, $section] = $args;
?>
```

###### Using a different template partial location

You'll need to extend the `Shortcodes` class and provide an alternative getter:

```php
class MyShortcodes extends Shortcodes {
    // skip this if you want to keep the default partial and only
    // change the override location
    protected function getDefaultPartial(): string
    {
        return __DIR__ . '/../../templates/shortcodes.php';
    }

    protected function getOverridePath(): string
    {
        return 'template-parts/meta/docs/shortcodes.php';
    }
}
```


#### List of SVG icon partials

This module will render a list of all icons that can be used by editors (for 
instance via shortcodes). By default it searches `template-parts/svg/` in your
theme's root directory recursively for `.php` files, assuming they are templates
containing SVG markup.

This is most useful in conjunction with a shortcode that can render these SVG
partials, for instance into button blocks.

##### Setting a different path for SVG partials

Same as [with the Shortcodes section](#using-a-different-template-partial-location).


## Development

### Dependencies

- PHP >= 8.0
- optional: DDEV
- [Composer 2.x](https://getcomposer.org)
- [asdf tool version manager](https://asdf-vm.com) with nodeJS LTS (v24.x)
- [go-task / Taskfiles](https://taskfile.dev)


## Migration

### v2 to v3

The traits now use the more conventional PascalCase naming, so `withShortcodeDocumentation`
is now `WithShortcodeDocumentation`.
We've also removed the deprecated Doctrine\Annotations and replaced them with native
PHP attributes. If you were using the ShortcodeDocumentation annotation, you'll
need to change your code to use the attribute instead:

```php
- use Gebruederheitz\Wordpress\AdminPage\Documentation\Annotations\ShortcodeDocumentation;
+ use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\ShortcodeDocumentation;
- use Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\withShortcodeDocumentation;
+ use Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\WithShortcodeDocumentation;

- /**
-  * @ShortcodeDocumentation(
-  *   shortcode="ghwp-my-shortcode", 
-  *   description="Renders a thing.",
-  *   parameters={
-  *       "id": "The post ID you wish to display."
-  *   },
-  *   examples={
-  *     "[ghwp-my-shortcode id=123 /]"
-  *   }
-  * )
-  */
+ #[ShortcodeDocumentation(
+   shortcode: "ghwp-my-shortcode",
+   description: "Renders a thing.",
+   parameters: [
+       "id" => "The post ID you wish to display."
+   ],
+   examples: [
+       "[ghwp-my-shortcode id=123 /]"
+   ]
+ )]
+
class MyShortcode {
-    use withShortcodeDocumentation;
+    use WithShortcodeDocumentation;
    
    public function __construct() 
    {
        $this->addDocumentation();
        add_shortcode('ghwp-my-shortcode', [$this, 'renderShortcode'));
    }

```

### v1 to v2

 - The PSR-4 namespaces changed; so instead of using `Gebruederheitz\Wordpress\Documentation\DocumentationPage`
   you will need to use `Gebruederheitz\Wordpress\AdminPage\Documentation\DocumentationPage` etc.
 - `AdminPageSectionInterface` replaces `DocumentationSectionInterface`, `AbstractAdminPageSections`
   replaces `AbstractDocumentationSection`.
 - For a complete list of namespace and class name changes refer to the table below.
 - Sections do not extend `Singleton` anymore, so you will have to construct them
   like any regular class instead of calling `::getInstance()`.
 - The filter hook for documentation sections has changed from `ghwp_filter_documentation_sections`
   to `ghwp_filter_sections_ghwp-help`. You can not use `DocumentationPage::HOOK_SECTIONS`
   anymore; either use the literal hook name, get the name via `$documentationPageInstance->getSectionsHook()`,
   or (preferred) use `$documentationPageInstance->addSection(AdminPageSectionInterface`

| changed | v1 | v2 |
| --- | --- | --- |
| moved | Gebruederheitz\Wordpress\Documentation\DocumentationPage | Gebruederheitz\Wordpress\AdminPage\Documentation\DocumentationPage
| renamed | Gebruederheitz\Wordpress\Documentation\DocumentationSectionInterface | Gebruederheitz\Wordpress\AdminPage\AdminPageSectionInterface
| renamed, moved | Gebruederheitz\Wordpress\Documentation\Sections\AbstractDocumentationSection | Gebruederheitz\Wordpress\AdminPage\AbstractAdminPageSection
| moved | Gebruederheitz\Wordpress\Documentation\Sections\Shortcode | Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Shortcode
| moved | Gebruederheitz\Wordpress\Documentation\Sections\Icons | Gebruederheitz\Wordpress\AdminPage\Documentation\Section\Icons
| moved | Gebruederheitz\Wordpress\Documentation\Annotations\ShortcodeDocumentation | Gebruederheitz\Wordpress\AdminPage\Documentation\Annotations\ShortcodeDocumentation
| moved | Gebruederheitz\Wordpress\Documentation\Traits\withShortcodeDocumentation | Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\withShortcodeDocumentation
| --> | Gebruederheitz\Wordpress\Documentation\Helper\AnnotationReader | Gebruederheitz\Wordpress\AdminPage\Helper\AnnotationReader
| new | -- | Gebruederheitz\Wordpress\AdminPage\AdminPage
