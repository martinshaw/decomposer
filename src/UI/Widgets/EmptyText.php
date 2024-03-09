<?php

namespace Martinshaw\Decomposer\UI\Widgets;

use Martinshaw\Decomposer\UI\Component;
use Martinshaw\Decomposer\UI\Application;

use PhpTui\Term\Event;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;

class EmptyText implements Component
{
    public function __construct(Application $app)
    {
        // Do nothing
    }

    public function build(): Widget
    {
        return ParagraphWidget::fromText(
            Text::fromString("Cannot find any PHP projects with vendor directories within the current working directory.\n\nTry running this command at the root of your projects or sites directory.\n\nPress q to quit.")
        );
    }

    public function handleInput(Event $event): void
    {
        // Do nothing
    }
}
