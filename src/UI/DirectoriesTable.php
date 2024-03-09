<?php

namespace Martinshaw\Decomposer\UI;

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

    private TableState $state;
    private int $selected = 0;

    public function __construct()
    {
        $this->state = new TableState();
    }

    public function build(): Widget
    {
        return TableWidget::default()
            ->state($this->state)
            ->select($this->selected)
            ->highlightSymbol(' (Space to delete) -> ')
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
                return TableRow::fromCells(
                    // TableCell::fromLine(Line::fromSpan(
                    //     Span::fromString($event[1])->fg(match ($event[1]) {
                    //         'INFO' => AnsiColor::Green,
                    //         'WARNING' => AnsiColor::Yellow,
                    //         'CRITICAL' => AnsiColor::Red,
                    //         default => AnsiColor::Cyan,
                    //     }),
                    // )),
                    TableCell::fromLine(Line::fromString($directory->getSizeAsHumanReadable())),
                    TableCell::fromLine(Line::fromString($directory->getPath())),
                );
            }, $this->directories));
    }

    public function handle(Event $event): void
    {
        if ($event instanceof CodedKeyEvent) {
            if ($event->code === KeyCode::Down) {
                $this->selected++;
            }
            if ($event->code === KeyCode::Up) {
                if ($this->selected > 0) {
                    $this->selected--;
                }
            }
        }
    }

    /**
     * @param VendorDirectory[] $directories
     */
    public function setDirectories(array $directories)
    {
        $this->directories = $directories;

        $this->state = new TableState();
        $this->selected = 0;
    }
}
