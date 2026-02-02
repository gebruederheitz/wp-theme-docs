<?php

$obj = new class {
    use \Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\WithDocumentationSection;

    public function __construct()
    {
        $this->addDocumentation();
    }
};

$obj = new class {
    use \Gebruederheitz\Wordpress\AdminPage\Documentation\Traits\WithShortcodeDocumentation;

    public function __construct()
    {
        $this->addDocumentation();
    }
};
