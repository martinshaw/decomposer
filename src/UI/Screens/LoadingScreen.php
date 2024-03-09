<?php

namespace Martinshaw\Decomposer\UI\Screens;

use Martinshaw\Decomposer\UI\Application;
use Martinshaw\Decomposer\UI\Component;
use Martinshaw\Decomposer\UI\Widgets\LoadingText;
use Martinshaw\Decomposer\UI\Widgets\Logo;

use PhpTui\Term\Event;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Widget;

class LoadingScreen implements Component
{
    private Logo $logoWidget;
    private LoadingText $loadingTextWidget;

    public function __construct(
        private Application $app
    )
    {
        $this->logoWidget = new Logo($app);
        $this->loadingTextWidget = new LoadingText($app);
    }

    public function build(): Widget
    {
        return GridWidget::default()
            ->direction(Direction::Vertical)
            ->constraints(
                Constraint::min(10),
                Constraint::min(1),
            )
            ->widgets(
                $this->logoWidget->build(),
                $this->loadingTextWidget->build()
            );
    }

    public function handleInput(Event $event): void
    {
        $this->logoWidget->handleInput($event);
        $this->loadingTextWidget->handleInput($event);
    }
}