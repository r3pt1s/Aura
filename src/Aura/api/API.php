<?php

namespace Aura\api;

use pocketmine\player\Player;

class API {

    private static array $aura = [];

    public static function activateAura(Player $player) {
        if (!self::hasAura($player)) self::$aura[$player->getName()] = $player->getName();
    }

    public static function deactivateAura(Player $player) {
        if (self::hasAura($player)) unset(self::$aura[$player->getName()]);
    }

    public static function hasAura(Player $player): bool {
        return isset(self::$aura[$player->getName()]);
    }
}