<?php

namespace Gebruederheitz\Wordpress\Documentation\Annotations;

/**
 * Class ShortcodeDocumentation
 *
 * @package Gebruederheitz\Wordpress\Documentation\Annotations
 * @Annotation
 * @Target("CLASS")
 */
class ShortcodeDocumentation
{
    /** @var string */
    public $shortcode;

    /** @var string */
    public $description;

    /** @var array */
    public $parameters;

    /** @var array<string> */
    public $examples;

    /**
     * @return string
     */
    public function getShortcode(): string
    {
        return $this->shortcode;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getExamples(): array
    {
        return $this->examples;
    }

    public function hasParameters(): bool
    {
        return (bool) count($this->parameters);
    }
}
