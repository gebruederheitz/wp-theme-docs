<?php

namespace Gebruederheitz\Wordpress\Documentation\Helper;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use Gebruederheitz\Wordpress\Documentation\Annotations\ShortcodeDocumentation;
use ReflectionClass;

class AnnotationReader
{
    private $reader;

    private $reflectionClass;

    public function __construct(string $className)
    {
        $this->reflectionClass = new ReflectionClass($className);
        $this->reader = new DoctrineAnnotationReader();
    }

    public function getDocumentationReader(): ShortcodeDocumentation
    {
        return $this->reader->getClassAnnotation(
            $this->reflectionClass,
            ShortcodeDocumentation::class,
        );
    }

    public static function getDocumentation(
        string $className
    ): ShortcodeDocumentation {
        $reflectionClass = new ReflectionClass($className);
        $reader = new DoctrineAnnotationReader();
        return $reader->getClassAnnotation(
            $reflectionClass,
            ShortcodeDocumentation::class,
        );
    }
}
