<?php

namespace Aura\task;

use Aura\api\API;
use Aura\Aura;
use pocketmine\entity\Living;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class KnockbackTask extends Task {

    public function onRun(): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (API::hasAura($player)) {
                if (!$player->hasPermission(Aura::getInstance()->getUsePermission()->getName())) {
                    API::deactivateAura($player);
                    continue;
                }

                foreach ($player->getWorld()->getEntities() as $entity) {
                    if ($entity instanceof Player) {
                        if ($entity->getName() == $player->getName()) continue;
                        if (!$entity->hasPermission(Aura::getInstance()->getBypassPermission()->getName())) {
                            if ($player->getPosition()->distance($entity->getPosition()) <= Aura::getInstance()->getRadius()) {
                                $entity->knockBack(
                                    $entity->getPosition()->getX() - $player->getPosition()->getX(),
                                    $entity->getPosition()->getZ() - $player->getPosition()->getZ(),
                                    Aura::getInstance()->getKnockbackPower(),
                                    0.7
                                );
                            }
                        }
                    } else if ($entity instanceof Living) {
                        if ($player->getPosition()->distance($entity->getPosition()) <= Aura::getInstance()->getRadius()) {
                            $entity->knockBack(
                                $entity->getPosition()->getX() - $player->getPosition()->getX(),
                                $entity->getPosition()->getZ() - $player->getPosition()->getZ(),
                                Aura::getInstance()->getKnockbackPower(),
                                0.7
                            );
                        }
                    }
                }
            }
        }
    }
}