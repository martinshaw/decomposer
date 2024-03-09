<?php

namespace Martinshaw\Decomposer\UI\Screens;

use Martinshaw\Decomposer\UI\Application;
use Martinshaw\Decomposer\UI\Screen;
use Martinshaw\Decomposer\UI\Widgets\EmptyText;
use Martinshaw\Decomposer\UI\Widgets\Logo;
use Martinshaw\Decomposer\UI\Widgets\PaddingTextLine;

use PhpTui\Term\Event;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Widget;

class EmptyScreen implements Screen
{
    private Logo $logoWidget;
    private EmptyText $emptyTextWidget;

    public function __construct(
        private Application $app
    )
    {
        $this->logoWidget = new Logo($app);
        $this->emptyTextWidget = new EmptyText($app);
    }

    public function build(): Widget
    {
        return GridWidget::default()
            ->direction(Direction::Vertical)
            ->constraints(
                Constraint::min(10),
                Constraint::min(1),
                Constraint::max(1000),
            )
            ->widgets(
                $this->logoWidget->build(),
                (new PaddingTextLine($this->app))->build(),
                $this->emptyTextWidget->build()
            );
    }

    public function handleInput(Event $event): void
    {
        $this->logoWidget->handleInput($event);
        $this->emptyTextWidget->handleInput($event);
    }

    public function setDirectories(array $directories): void
    {
        // Do nothing
    }
}