<?php declare(strict_types=1);

namespace Taskovich\GmInventories;

use pocketmine\plugin\PluginBase;
use Taskovich\GmInventories\manager\InventoriesManager;

class Main extends PluginBase
{

	/**
	 * @var InventoriesManager
	 */
	private InventoriesManager $inv_manager;

	/**
	 * @return void
	 */
	protected function onEnable(): void
	{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->inv_manager = new InventoriesManager($this);
		$this->saveDefaultConfig();
	}

	/**
	 * @return void
	 */
	protected function onDisable(): void
	{
		$this->inv_manager->saveAll();
	}

	/**
	 * @return InventoriesManager
	 */
	public function getInventoriesManager(): InventoriesManager
	{
		return $this->inv_manager;
	}

}