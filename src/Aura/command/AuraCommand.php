<?php

namespace Aura\command;

use Aura\Aura;
use Aura\form\MainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class AuraCommand extends Command {

    public function __construct() {
        parent::__construct("aura", Aura::getInstance()->parse("aura-command-description"), "");
        $this->setPermission(Aura::getInstance()->getUsePermission()->getName());
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if ($sender->hasPermission($this->getPermission())) {
                $sender->sendForm(new MainForm($sender));
            } else {
                $sender->sendMessage(Aura::getInstance()->parse("aura-no-permission"));
            }
        }
        return true;
    }
}