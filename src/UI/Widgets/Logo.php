<?php

namespace Martinshaw\Decomposer\UI\Widgets;

use Martinshaw\Decomposer\UI\Component;
use Martinshaw\Decomposer\UI\Application;

use PhpTui\Term\Event;
use PhpTui\Tui\Model\Widget;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;

class Logo implements Component
{
    public function __construct(Application $app)
    {
        // Do nothing
    }

    public function build(): Widget
    {
        $ascii = <<<EOT
        ▓█████▄ ▓█████  ▄████▄   ▒█████   ███▄ ▄███▓ ██▓███   ▒█████    ██████ ▓█████  ██▀███  
        ▒██▀ ██▌▓█   ▀ ▒██▀ ▀█  ▒██▒  ██▒▓██▒▀█▀ ██▒▓██░  ██▒▒██▒  ██▒▒██    ▒ ▓█   ▀ ▓██ ▒ ██▒
        ░██   █▌▒███   ▒▓█    ▄ ▒██░  ██▒▓██    ▓██░▓██░ ██▓▒▒██░  ██▒░ ▓██▄   ▒███   ▓██ ░▄█ ▒
        ░▓█▄   ▌▒▓█  ▄ ▒▓▓▄ ▄██▒▒██   ██░▒██    ▒██ ▒██▄█▓▒ ▒▒██   ██░  ▒   ██▒▒▓█  ▄ ▒██▀▀█▄  
        ░▒████▓ ░▒████▒▒ ▓███▀ ░░ ████▓▒░▒██▒   ░██▒▒██▒ ░  ░░ ████▓▒░▒██████▒▒░▒████▒░██▓ ▒██▒
         ▒▒▓  ▒ ░░ ▒░ ░░ ░▒ ▒  ░░ ▒░▒░▒░ ░ ▒░   ░  ░▒▓▒░ ░  ░░ ▒░▒░▒░ ▒ ▒▓▒ ▒ ░░░ ▒░ ░░ ▒▓ ░▒▓░
         ░ ▒  ▒  ░ ░  ░  ░  ▒     ░ ▒ ▒░ ░  ░      ░░▒ ░       ░ ▒ ▒░ ░ ░▒  ░ ░ ░ ░  ░  ░▒ ░ ▒░
         ░ ░  ░    ░   ░        ░ ░ ░ ▒  ░      ░   ░░       ░ ░ ░ ▒  ░  ░  ░     ░     ░░   ░ 
           ░       ░  ░░ ░          ░ ░         ░                ░ ░        ░     ░  ░   ░     
         ░             ░                                                                       
        EOT;

        return ParagraphWidget::fromText(
            Text::fromString($ascii)
        );
    }

    public function handleInput(Event $event): void
    {
        // Do nothing
    }
}
