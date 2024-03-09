<?php

namespace Martinshaw\Decomposer\UI\Widgets;

use Martinshaw\Decomposer\UI\Component;
use Martinshaw\Decomposer\UI\Application;

use PhpTui\Term\Event;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;

class KeyHintBar implements Component
{
    public function __construct(Application $app)
    {
        // Do nothing
    }

    public function build(): Widget
    {
        return ParagraphWidget::fromText(
            Text::fromString("Up/Down to navigate list   -   Enter to delete   -   q to quit")
        );
    }

    public function handleInput(Event $event): void
    {
        // Do nothing
    }
}
