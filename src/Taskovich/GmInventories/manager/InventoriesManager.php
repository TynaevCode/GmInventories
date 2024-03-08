<?php declare(strict_types=1);

namespace Taskovich\GmInventories\manager;

use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\Config;

use Taskovich\GmInventories\Main;

class InventoriesManager
{

	/** @var Main */
	private Main $main;

	/** @var Config */
	private Config $data_config;

	/** @var mixed[] */
	private array $data = [];

	/**
	 * @param Main $main
	 */
	function __construct(Main $main)
	{
		$this->main = $main;
		$this->data_config = new Config($this->main->getDataFolder() . "inventories.json");

		foreach($this->data_config->getAll() as $nick => $data) {
			$this->data[$nick] = unserialize($data);
		}
	}

	/**
	 * @return void
	 */
	public function saveAll(): void
	{
		foreach($this->data as $nick => $data) {
			$this->data[$nick] = serialize($data);
		}

		$this->data_config->setAll($this->data);
		$this->data_config->save();
	}

	/**
	 * @param Player $player 
	 * @return void
	 */
	public function saveInventory(Player $player): void
	{
		$gamemode = $player->getGamemode();

		if($gamemode == GameMode::SPECTATOR())
			return;

		$nick = strtolower($player->getName());
		$gamemode = $gamemode->getEnglishName();
		$this->data[$nick][$gamemode] = [
			"main" => $player->getInventory()->getContents(),
			"offhand" => $player->getOffHandInventory()->getContents(),
			"armor" => $player->getArmorInventory()->getContents()
		];
	}

	/**
	 * @param Player $player 
	 * @param GameMode $gamemode 
	 * @return mixed[]
	 */
	public function loadInventory(Player $player, GameMode $gamemode): array
	{
		$nick = strtolower($player->getName());
		$gamemode = $player->getGamemode()->getEnglishName();
		return $this->data[$nick][$gamemode] ?? [];
	}

}