<?php

namespace Gebruederheitz\Wordpress\AdminPage\Helper;

use Gebruederheitz\Wordpress\AdminPage\Documentation\Attributes\ShortcodeDocumentation;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class AttributeReader
{
    private ReflectionClass $reflectionClass;

    public function __construct(string $className)
    {
        $this->reflectionClass = new ReflectionClass($className);
    }

    public function getShortcodeDocumentationReader(): ShortcodeDocumentation|null
    {
        /** @var array<ReflectionAttribute<ShortcodeDocumentation>> $attributes */
        $attributes = $this->reflectionClass->getAttributes(
            ShortcodeDocumentation::class,
        );

        if (!empty($attributes)) {
            return $attributes[0]->newInstance();
        }

        return null;
    }

    /**
     * @throws ReflectionException
     */
    public static function getShortcodeDocumentation(
        string $className
    ): ShortcodeDocumentation|null {
        $reflectionClass = new ReflectionClass($className);
        /** @var array<ReflectionAttribute<ShortcodeDocumentation>> $attributes */
        $attributes = $reflectionClass->getAttributes(
            ShortcodeDocumentation::class,
        );

        if (!empty($attributes)) {
            return $attributes[0]->newInstance();
        }

        return null;
    }
}
