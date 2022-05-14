<?php

namespace Aura\form;

use Aura\api\API;
use Aura\Aura;
use dktapps\pmforms\MenuForm;
use dktapps\pmforms\MenuOption;
use pocketmine\player\Player;

class MainForm extends MenuForm {

    private array $options = [];

    public function __construct(Player $player) {
        if (API::hasAura($player)) $this->options[] = new MenuOption(Aura::getInstance()->parse("aura-ui-button-deactivate"));
        else $this->options[] = new MenuOption(Aura::getInstance()->parse("aura-ui-button-activate"));

        parent::__construct(
            Aura::getInstance()->parse("aura-ui-title"),
            Aura::getInstance()->parse("aura-ui-text", ["status" => (API::hasAura($player) ? Aura::getInstance()->parse("aura-status-active") : Aura::getInstance()->parse("aura-status-inactive"))]),
            $this->options,
            function(Player $player, int $data): void {
                if (API::hasAura($player)) {
                    API::deactivateAura($player);
                    $player->sendMessage(Aura::getInstance()->parse("aura-deactivation-message"));
                } else {
                    if ($player->hasPermission(Aura::getInstance()->getUsePermission()->getName())) {
                        API::activateAura($player);
                        $player->sendMessage(Aura::getInstance()->parse("aura-activation-message"));
                    } else {
                        $player->sendMessage(Aura::getInstance()->parse("aura-no-permission"));
                    }
                }
            }
        );
    }
}