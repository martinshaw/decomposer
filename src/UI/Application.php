<?php

namespace Martinshaw\Decomposer\UI;

use Martinshaw\Decomposer\UI\Component;
use PhpTui\Term\Actions;
use PhpTui\Term\ClearType;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Term\KeyModifiers;
use PhpTui\Term\Terminal;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Tui\Extension\Core\Widget\BlockWidget;
use PhpTui\Tui\Extension\Core\Widget\GridWidget;
use PhpTui\Tui\Model\Direction;
use PhpTui\Tui\Model\Display\Display;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Text\Title;
use PhpTui\Tui\Model\Widget\Borders;

class Application
{
    private Terminal $terminal;

    private Display $display;

    private Component $logoWidget;
    private Component $tableWidget;

    /**
     * @var VendorDirectory[]
     */
    private array $directories = [];

    public function __construct()
    {
        $this->terminal = Terminal::new();

        $this->display = DisplayBuilder::default()->fullscreen()->build();

        $this->logoWidget = new Logo();
        $this->tableWidget = new DirectoriesTable();
    }

    public function addDirectories(array $directories)
    {
        $this->directories = $directories;
    }

    public function run()
    {
        $this->display->clear();

        try {
            $this->terminal->execute(Actions::cursorHide());
            $this->terminal->execute(Actions::alternateScreenEnable());
            $this->terminal->execute(Actions::enableMouseCapture());
            $this->terminal->enableRawMode();

            $this->handleInput();
        } catch (\Throwable $e) {
            $this->terminal->disableRawMode();
            $this->terminal->execute(Actions::cursorShow());
            $this->terminal->execute(Actions::alternateScreenDisable());
            $this->terminal->execute(Actions::disableMouseCapture());
            $this->terminal->execute(Actions::clear(ClearType::All));

            throw $e;
        }
    }

    private function draw()
    {
        $this->display->draw(
            GridWidget::default()
                ->direction(Direction::Vertical)
                ->constraints(
                    Constraint::min(10),
                    Constraint::min(1),
                )
                ->widgets(
                    $this->logoWidget->build(),
                    $this->tableWidget->build()
                )
        );
    }

    private function handleInput()
    {
        while (true) {
            while (null !== $event = $this->terminal->events()->next()) {
                $this->logoWidget->handle($event);
                $this->tableWidget->handle($event);
            }

            $this->draw();

            usleep(50_000);
        }

        $this->terminal->disableRawMode();
        $this->terminal->execute(Actions::cursorShow());
        $this->terminal->execute(Actions::alternateScreenDisable());
        $this->terminal->execute(Actions::disableMouseCapture());

        return 0;
    }
}
