<?php

namespace Martinshaw\Decomposer\UI;

use PhpTui\Term\Event;
use PhpTui\Tui\Model\Widget;

interface Component
{
    public function __construct(Application $app);

    public function build(): Widget;

    public function handle(Event $event): void;
}
