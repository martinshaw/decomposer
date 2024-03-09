<?php

namespace Martinshaw\Decomposer\UI;

use Martinshaw\Decomposer\UI\Screens\EmptyScreen;
use Martinshaw\Decomposer\UI\Screens\TableScreen;
use Martinshaw\Decomposer\UI\Screens\LoadingScreen;
use Martinshaw\Decomposer\VendorDirectory;
use Martinshaw\Decomposer\VendorDirectoryDeleter;
use Martinshaw\Decomposer\VendorDirectoriesWalker;

use PhpTui\Term\Event;
use PhpTui\Term\Actions;
use PhpTui\Term\Terminal;
use PhpTui\Term\ClearType;
use PhpTui\Term\KeyModifiers;
use PhpTui\Tui\DisplayBuilder;
use PhpTui\Term\Event\CharKeyEvent;
use PhpTui\Tui\Model\Display\Display;

class Application
{
    private Terminal $terminal;

    private Display $display;

    private Screen $activeScreen;

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

        $this->activeScreen = new LoadingScreen($this);
    }

    private function draw()
    {
        $this->display->draw(
            $this->activeScreen->build()
        );
    }

    private function onFirstRender()
    {
        if ($this->directoriesHaveLoaded) return;

        $this->directories = (new VendorDirectoriesWalker())->walk($this->rootPath);
        $this->directoriesHaveLoaded = true;

        $this->activeScreen = count($this->directories) > 0 ? new TableScreen($this) : new EmptyScreen($this);
        $this->activeScreen->setDirectories($this->directories);
    }

    private function handleInput(Event $event)
    {
        $this->activeScreen->handleInput($event);
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

        try {
            $deleter = new VendorDirectoryDeleter();
            $deleter->delete($directory);

            if ($deleter->getDeletedSuccessfully() === false) throw new \Exception('Deletion failed');

            $this->directories = array_filter(
                $this->directories,
                function ($current) use ($directory) {
                    return $current->getPath() !== $directory->getPath();
                }
            );
        } catch (\Exception $exception) {
            // Deletion may fail due to file permissions, etc. We don't want to crash the whole app
            $this->directories = array_map(
                function ($current) use ($directory) {
                    if ($current->getPath() === $directory->getPath()) $current->setCannotBeDeleted(true);
                    return $current;
                },
                $this->directories
            );
        }

        $this->activeScreen->setDirectories($this->directories);
    }
}
