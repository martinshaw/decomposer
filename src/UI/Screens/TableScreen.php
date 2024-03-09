<?php

namespace Martinshaw\Decomposer\UI\Screens;

use Martinshaw\Decomposer\UI\Screen;
use Martinshaw\Decomposer\UI\Application;
use Martinshaw\Decomposer\UI\Widgets\Logo;
use Martinshaw\Decomposer\UI\Widgets\KeyHintBar;
use Martinshaw\Decomposer\UI\Widgets\PaddingTextLine;
use Martinshaw\Decomposer\UI\Widgets\DirectoriesTable;

use PhpTui\Term\Event;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;

class TableScreen implements Screen
{
    private Logo $logoWidget;
    private DirectoriesTable $tableWidget;
    private KeyHintBar $keyHintBarWidget;

    public function __construct(
        private Application $app
    )
    {
        $this->logoWidget = new Logo($app);
        $this->tableWidget = new DirectoriesTable($app);
        $this->keyHintBarWidget = new KeyHintBar($app);
    }

    public function build(): Widget
    {
        return GridWidget::default()
            ->direction(Direction::Vertical)
            ->constraints(
                Constraint::min(10),
                Constraint::min(1),
                Constraint::max(1000), 
                Constraint::min(1),
            )
            ->widgets(
                $this->logoWidget->build(),
                (new PaddingTextLine($this->app))->build(),
                $this->tableWidget->build(),
                $this->keyHintBarWidget->build(),
            );
    }

    public function handleInput(Event $event): void
    {
        $this->logoWidget->handleInput($event);
        $this->tableWidget->handleInput($event);
        $this->keyHintBarWidget->handleInput($event);
    }

    /**
     * @param VendorDirectory[] $directories
     */
    public function setDirectories(array $directories): void
    {
        $this->tableWidget->setDirectories($directories);
    }
}