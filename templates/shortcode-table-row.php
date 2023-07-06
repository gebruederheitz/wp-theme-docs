<?php
    /** @var Gebruederheitz\Wordpress\AdminPage\Documentation\Annotations\ShortcodeDocumentation $doc */
    [$doc] = $args;

    $parameters = $doc->getParameters();
    $parameterCount = count($parameters);
    $rowspanValue = $parameterCount ?: '1';
    $rowspan = "rowspan=\"$rowspanValue\"";
    $index = 0;
?>
<tr>
    <td <?= $rowspan ?>>
        <pre><code><?= $doc->getShortcode() ?></code></pre>
    </td>
    <?php if ($doc->hasParameters()): ?>
    <?php foreach ($doc->getParameters() as $parameterName => $parameterDescription): ?>
    <?php if ($index > 0): ?></tr><tr><?php endif; ?>
    <td><?= $parameterName ?></td>
    <td><?= $parameterDescription ?></td>
    <?php if ($index === 0): ?>
        <td <?= $rowspan ?>>
            <p>
                <?= $doc->getDescription() ?>
            </p>
        </td>
        <td <?= $rowspan ?>>
            <ul>
                <?php foreach ($doc->getExamples() as $example): ?>
                    <li><pre><code lang="html"><?= $example ?></code></pre></li>
                <?php endforeach; ?>
            </ul>
        </td>
    <?php endif; ?>
    <?php ++$index; ?>
    <?php endforeach; ?>
    <?php else: ?>
        <td colspan="2">--</td>
    <?php endif; ?>
    </td>

    <?php if (!$parameterCount): ?>
        <td <?= $rowspan ?>>
            <p>
                <?= $doc->getDescription() ?>
            </p>
        </td>
        <td <?= $rowspan ?>>
            <ul>
                <?php foreach ($doc->getExamples() as $example): ?>
                    <li><pre><code lang="html"><?= $example ?></code></pre></li>
                <?php endforeach; ?>
            </ul>
        </td>
    <?php endif; ?>
</tr>
