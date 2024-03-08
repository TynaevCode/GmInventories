<?php declare(strict_types=1);

namespace Taskovich\GmInventories;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\scheduler\ClosureTask;

class EventListener implements Listener
{

	/**
	 * @var Main
	 */
	private Main $main;

	/**
	 * @param Main $main
	 */
	function __construct(Main $main)
	{
		$this->main = $main;
	}

	/**
	 * @param PlayerGameModeChangeEvent $event 
	 * @return void
	 */
	public function onGameModeChange(PlayerGameModeChangeEvent $event): void
	{
		if($event->isCancelled())
			return;

		$manager = $this->main->getInventoriesManager();
		$player = $event->getPlayer();
		$manager->saveInventory($player);
		$this->main->getScheduler()->scheduleDelayedTask(new ClosureTask(
			function() use($manager, $player, $event)
			{
				$data = $manager->loadInventory($player, $event->getNewGamemode());
				foreach($data as $name => $content) {
					match($name) {
						"main" => $player->getInventory()->setContents($content),
						"offhand" => $player->getOffHandInventory()->setContents($content),
						"armor" => $player->getArmorInventory()->setContents($content),
						default => null,
					};
				}
			}
		), 2);
	}

}