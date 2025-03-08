<?php

namespace Taskovich\GmInventories;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\scheduler\ClosureTask;

class EventListener implements Listener
{

	public function __construct(private Main $main)
	{}

	public function onGameModeChange(PlayerGameModeChangeEvent $event): void
	{
		if ($event->isCancelled()) {
			return;
		}

		$player = $event->getPlayer();

		$manager = $this->main->getInventoriesManager();
		$manager->saveInventory($player);

		$this->main->getScheduler()->scheduleDelayedTask(new ClosureTask(
			fn () => $manager->loadInventory($player, $event->getNewGamemode())
		), 2);
	}

}