<?php

namespace Martinshaw\Decomposer\UI\Widgets;

use Martinshaw\Decomposer\UI\Application;
use Martinshaw\Decomposer\UI\Component;
use Martinshaw\Decomposer\VendorDirectory;

use PhpTui\Term\Event;
use PhpTui\Term\Event\CodedKeyEvent;
use PhpTui\Term\KeyCode;
use PhpTui\Tui\Extension\Core\Widget\Table\TableCell;
use PhpTui\Tui\Extension\Core\Widget\Table\TableRow;
use PhpTui\Tui\Extension\Core\Widget\Table\TableState;
use PhpTui\Tui\Extension\Core\Widget\TableWidget;
use PhpTui\Tui\Model\Layout\Constraint;
use PhpTui\Tui\Model\Style;
use PhpTui\Tui\Model\Text\Line;
use PhpTui\Tui\Model\Widget;

class DirectoriesTable implements Component
{
    /**
     * @var VendorDirectory[]
     */
    private array $directories = [];
    /**
     * @var VendorDirectory[]
     */
    private array $selectedDirectories = [];

    private TableState $state;
    private int $selectedIndex = 0;

    public function __construct(
        protected Application $app
    ) {
        $this->state = new TableState();
    }

    public function build(): Widget
    {
        return TableWidget::default()
            ->state($this->state)
            ->select($this->selectedIndex)
            ->highlightSymbol(' (Enter to delete) -> ')
            ->highlightStyle(Style::default()->black()->onCyan())
            ->widths(
                Constraint::percentage(15),
                Constraint::percentage(85),
            )
            ->header(
                TableRow::fromCells(
                    TableCell::fromString('Size'),
                    TableCell::fromString('Path'),
                )
            )
            ->rows(...array_map(function (VendorDirectory $directory) {
                $directoryIsSelected = in_array($directory, $this->selectedDirectories);

                $caption = $directory->getPath();
                if ($directoryIsSelected) $caption .= ' [is deleting ...]';
                if ($directory->getCannotBeDeleted()) $caption .= ' [no permission - try sudo]';

                return TableRow::fromCells(
                    TableCell::fromLine(Line::fromString($directory->getSizeAsHumanReadable())),
                    TableCell::fromLine(Line::fromString($caption)),
                );
            }, $this->directories));
    }

    public function handleInput(Event $event): void
    {
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Down) {
                $this->selectedIndex = ($this->selectedIndex < count($this->directories) - 1) ? ($this->selectedIndex + 1) : 0;
            }
            if ($event->code === KeyCode::Up) {
                $this->selectedIndex = ($this->selectedIndex > 0) ? $this->selectedIndex - 1 : count($this->directories) - 1;
            }
            if ($event->code === KeyCode::Enter) {
                if (in_array($this->directories[$this->selectedIndex], $this->selectedDirectories)) return;
                if ($this->directories[$this->selectedIndex] === null) return;

                $this->selectedDirectories[] = $this->directories[$this->selectedIndex];
                $this->app->deleteSelectedDirectory($this->directories[$this->selectedIndex]);
            }
        }
    }

    /**
     * @param VendorDirectory[] $directories
     */
    public function setDirectories(array $directories)
    {
        $this->selectedDirectories = [];
        $this->directories = array_values($directories);

        $this->state = new TableState();
        $this->selectedIndex = ($this->selectedIndex < count($this->directories)) ?
            $this->selectedIndex : (count($this->directories) - 1);
    }
}
