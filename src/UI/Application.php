<?php

namespace Martinshaw\Decomposer\UI;

use Martinshaw\Decomposer\UI\Component;
use Martinshaw\Decomposer\UI\Screens\LoadingScreen;
use Martinshaw\Decomposer\UI\Screens\TableScreen;
use Martinshaw\Decomposer\VendorDirectoriesWalker;
use Martinshaw\Decomposer\VendorDirectory;
use Martinshaw\Decomposer\VendorDirectoryDeleter;
use PhpTui\Term\Actions;
use PhpTui\Term\ClearType;
use PhpTui\Term\Event;
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

    private LoadingScreen $loadingScreen;
    private TableScreen $tableScreen;

    private bool $isFirstRender = true;

    /**
     * @var VendorDirectory[]
     */
    private array $directories = [];
    private bool $directoriesHaveLoaded = false;

    public function __construct(
        private string $rootPath,
    ) {
        $this->terminal = Terminal::new();

        $this->display = DisplayBuilder::default()->fullscreen()->build();

        $this->loadingScreen = new LoadingScreen($this);
        $this->tableScreen = new TableScreen($this);
    }

    private function draw()
    {
        $this->display->draw(
            $this->isFirstRender ? $this->loadingScreen->build() : $this->tableScreen->build()
        );
    }

    private function onFirstRender()
    {
        if ($this->directoriesHaveLoaded) return;

        $this->directories = (new VendorDirectoriesWalker())->walk($this->rootPath);
        $this->tableScreen->setDirectories($this->directories);
        $this->directoriesHaveLoaded = true;
    }

    private function handleInput(Event $event)
    {
        $this->tableScreen->handleInput($event);
    }

    private function trapInput()
    {
        $this->terminal->execute(Actions::cursorHide());
        $this->terminal->execute(Actions::alternateScreenEnable());
        $this->terminal->execute(Actions::enableMouseCapture());
        $this->terminal->enableRawMode();
    }

    private function untrapInput()
    {
        $this->terminal->disableRawMode();
        $this->terminal->execute(Actions::cursorShow());
        $this->terminal->execute(Actions::alternateScreenDisable());
        $this->terminal->execute(Actions::disableMouseCapture());
        $this->terminal->execute(Actions::clear(ClearType::All));
    }

    public function run()
    {
        $this->display->clear();

        try {
            $this->trapInput();
            $this->renderLoop();
        } catch (\Throwable $e) {
            $this->untrapInput();
            throw $e;
        }
    }

    private function renderLoop()
    {
        while (true) {
            while (null !== $event = $this->terminal->events()->next()) {
                if ($event instanceof CharKeyEvent) {
                    if ($event->modifiers === KeyModifiers::NONE) {
                        if ($event->char === 'q') {
                            break 2;
                        }
                    }
                }

                // if ($event instanceof CodedKeyEvent) {
                //     if ($event->code === KeyCode::Tab) {
                //         $this->activePage = $this->activePage->next();
                //     }
                //     if ($event->code === KeyCode::BackTab) {
                //         $this->activePage = $this->activePage->previous();
                //     }
                // }

                $this->handleInput($event);
            }

            $this->draw();

            if ($this->isFirstRender) {
                $this->onFirstRender();
                $this->isFirstRender = false;
            }

            usleep(50_000);
        }

        $this->untrapInput();

        return 0;
    }

    public function deleteSelectedDirectory(VendorDirectory $directory)
    {
        // Draw one more time before hanging the UI, to display the 'is deleting ...' message
        $this->draw();

        $deleter = new VendorDirectoryDeleter();
        $deleter->delete($directory);

        $this->directories = array_filter(
            $this->directories,
            function ($current) use ($directory) {
                return $current->getPath() !== $directory->getPath();
            }
        );
        $this->tableScreen->setDirectories($this->directories);
    }
}
