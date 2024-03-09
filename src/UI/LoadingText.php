<?php

namespace Martinshaw\Decomposer\UI;

use Martinshaw\Decomposer\UI\Component;
use PhpTui\Term\Event;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Widget;

class LoadingText implements Component
{
    public function build(): Widget
    {
        return ParagraphWidget::fromText(
            Text::fromString("Loading vendor directories ...")
        );
    }

    public function handle(Event $event): void
    {
        // Do nothing
    }
}
