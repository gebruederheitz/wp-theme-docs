<?php
    // GHWP "Help Center" – Basic In-Site Documentation
    /** @var Gebruederheitz\Wordpress\Documentation\AdminPage $docs */
    [$docs] = $args;
?>
<style>

    html, body {
        scroll-behavior: smooth;
    }

    h1.ghwp-docs-title {
        font-size: 1.8rem;
        margin-bottom: 3rem;
        padding-top: 1rem;
    }

    .ghwp-docs-section {
        position: relative;
    }

    h2.ghwp-docs-section,
    h3.ghwp-docs-section {
        margin: 5rem 0 2rem 0;
    }

    h2.ghwp-docs-section {
        border-bottom: 1px solid #15a;
        color: #15a;
        font-size: 1.5rem;
        padding-bottom: 1rem;
        padding-left: 1rem;
        scroll-margin-block-start: 5rem;
    }

    .back-to-top {
        position: absolute;
        right: 1rem;
        text-decoration: none;
        top: 0;
    }

    #top {
        scroll-margin-block-start: 5rem;
    }
</style>
<h1 class="ghwp-docs-title"><?= $docs->getTitle() ?></h1>

<ul id="top">
<?php foreach ($docs->getSections() as $i => $section): ?>
    <li>
        <a href="#section-<?= $i ?>"><?= $section->getTitle() ?></a>
    </li>
<?php endforeach; ?>
</ul>

<?php foreach ($docs->getSections() as $i => $section): ?>
    <div class="ghwp-docs-section">
        <h2 id="section-<?= $i ?>" class="ghwp-docs-section"><?= $section->getTitle() ?? '' ?></h2>
        <a href="#top" class="back-to-top">^ back to top</a>
        <?php $section->render($docs) ?>
    </div>
<?php endforeach; ?>

