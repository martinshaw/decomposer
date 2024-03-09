<?php

namespace Martinshaw\Decomposer\UI\Widgets;

use Martinshaw\Decomposer\UI\Application;
use Martinshaw\Decomposer\UI\Component;

use PhpTui\Term\Event;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Widget;

class PaddingTextLine implements Component
{
    public function __construct(Application $app)
    {
        // Do nothing
    }

    public function build(): Widget
    {
        return ParagraphWidget::fromText(Text::fromString(" "));
    }

    public function handleInput(Event $event): void
    {
        // Do nothing
    }
}
