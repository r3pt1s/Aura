<?php

namespace Aura;

use Aura\command\AuraCommand;
use Aura\listener\EventListener;
use Aura\task\KnockbackTask;
use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\plugin\PluginBase;

class Aura extends PluginBase {

    private static self $instance;
    private Permission $usePermission;
    private Permission $bypassPermission;
    private float|int $radius;
    private float|int $knockbackPower;
    public array $noFallDamage = [];

    protected function onEnable(): void {
        self::$instance = $this;

        $this->saveDefaultConfig();
        $this->usePermission = new Permission($this->getConfig()->get("aura-use-permission", "aura.use"));
        $this->bypassPermission = new Permission($this->getConfig()->get("aura-bypass-permission", "aura.bypass"));
        $this->radius = (($got = $this->getConfig()->get("radius")) !== null ? (is_numeric($got) ? (($val = floatval($got)) > 0 ? $val : 1) : 1) : 1);
        $this->knockbackPower = (($got = $this->getConfig()->get("knockbackPower")) !== null ? (is_numeric($got) ? (($val = floatval($got)) > 0 ? $val : 1) : 1) : 1);

        DefaultPermissions::registerPermission($this->bypassPermission, [PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR)]);
        DefaultPermissions::registerPermission($this->usePermission, [PermissionManager::getInstance()->getPermission(DefaultPermissions::ROOT_OPERATOR)]);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("aura", new AuraCommand());
        $this->getScheduler()->scheduleRepeatingTask(new KnockbackTask(), 1);
    }

    public function parse(string $key, array $parameters = []): string {
        $got = $this->getConfig()->get($key, $key);
        foreach ($parameters as $key => $value) $got = str_replace("%" . $key . "%", $value, $got);
        return $got;
    }

    public function getUsePermission(): Permission {
        return $this->usePermission;
    }

    public function getBypassPermission(): Permission {
        return $this->bypassPermission;
    }

    public function getRadius(): float|int {
        return $this->radius;
    }

    public function getKnockbackPower(): float|int {
        return $this->knockbackPower;
    }

    public static function getInstance(): Aura {
        return self::$instance;
    }
}