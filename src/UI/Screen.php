<?php

namespace Martinshaw\Decomposer\UI;

use PhpTui\Term\Event;
use PhpTui\Tui\Model\Widget;

interface Screen
{
    public function __construct(Application $app);

    public function build(): Widget;

    public function handleInput(Event $event): void;

    public function setDirectories(array $directories): void;
}
