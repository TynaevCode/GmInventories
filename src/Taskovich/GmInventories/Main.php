<?php

namespace Taskovich\GmInventories;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
	private InventoriesManager $manager;

	protected function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->saveDefaultConfig();

		$this->manager = new InventoriesManager($this);
	}

	protected function onDisable(): void
	{
		$this->manager->saveAll();
	}

	public function getInventoriesManager(): InventoriesManager
	{
		return $this->manager;
	}

}