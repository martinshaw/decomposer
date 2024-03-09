<?php

namespace Martinshaw\Decomposer\UI\Screens;

use Martinshaw\Decomposer\UI\Application;
use Martinshaw\Decomposer\UI\Component;
use Martinshaw\Decomposer\UI\Widgets\DirectoriesTable;
use Martinshaw\Decomposer\UI\Widgets\Logo;

use PhpTui\Term\Event;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Widget;

class TableScreen implements Component
{
    private Logo $logoWidget;

    private DirectoriesTable $tableWidget;

    public function __construct(
        private Application $app
    )
    {
        $this->logoWidget = new Logo($app);
        $this->tableWidget = new DirectoriesTable($app);
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
                $this->tableWidget->build()
            );
    }

    public function handleInput(Event $event): void
    {
        $this->logoWidget->handleInput($event);
        $this->tableWidget->handleInput($event);
    }

    /**
     * @param VendorDirectory[] $directories
     */
    public function setDirectories(array $directories)
    {
        $this->tableWidget->setDirectories($directories);
    }
}