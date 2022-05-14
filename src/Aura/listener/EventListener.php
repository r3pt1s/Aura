<?php

namespace Aura\listener;

use Aura\Aura;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class EventListener implements Listener {

    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();

        if ($entity instanceof Player) {
            if ($event->getCause() == $event::CAUSE_FALL) {
                if (isset(Aura::getInstance()->noFallDamage[$entity->getName()])) {
                    unset(Aura::getInstance()->noFallDamage[$entity->getName()]);
                    $event->cancel();
                }
            }
        }
    }

    public function onCheck(PlayerGameModeChangeEvent $event) {
        $player = $event->getPlayer();
        $old = $player->getGamemode();
        $new = $event->getNewGamemode();

        if ($old === GameMode::SURVIVAL() || $old === GameMode::ADVENTURE()) {
            if ($new === GameMode::CREATIVE() || $new === GameMode::SPECTATOR()) {
                if (isset(Aura::getInstance()->noFallDamage[$player->getName()])) {
                    unset(Aura::getInstance()->noFallDamage[$player->getName()]);
                }
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();

        if (isset(Aura::getInstance()->noFallDamage[$player->getName()])) unset(Aura::getInstance()->noFallDamage[$player->getName()]);
    }
}