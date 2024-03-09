<?php

namespace Martinshaw\Decomposer\UI;

use Martinshaw\Decomposer\UI\Component;
use PhpTui\Term\Event;
use PhpTui\Tui\Extension\Core\Widget\ParagraphWidget;
use PhpTui\Tui\Model\Text\Text;
use PhpTui\Tui\Model\Widget;

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

    public function handle(Event $event): void
    {
        // Do nothing
    }
}
