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
		$this->data_config = new Config($main->getDataFolder() . "inventories.json");

		foreach ($this->data_config->getAll() as $id => $data)
		{
			$this->data[$id] = unserialize($data);
		}
	}

	/**
	 * @return void
	 */
	public function saveAll(): void
	{
		$tmp_data = [];

		foreach ($this->data as $id => $data)
		{
			$tmp_data[$id] = serialize($data);
		}

		$this->data_config->setAll($tmp_data);
		$this->data_config->save();
	}

	/**
	 * @param Player $player 
	 * @return void
	 */
	public function saveInventory(Player $player): void
	{
		$gamemode = $player->getGamemode();

		if ($gamemode === GameMode::SPECTATOR())
		{
			return;
		}

		$id = $this->getId($player);
		$gamemode = $gamemode->getEnglishName();
		$this->data[$id][$gamemode] = [
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
		$id = $this->getId($player);
		$gamemode = $player->getGamemode()->getEnglishName();
		return $this->data[$id][$gamemode] ?? [
			"main" => [],
			"offhand" => [],
			"armor" => []
		];
	}

	private function getId(Player $player): string
	{
		$id = match ($this->main->getConfig()->get("data_type"))
		{
			"xuid" => $player->getXuid(),
			"uuid" => $player->getUniqueId()->toString(),
			default => strtolower($player->getName())
		};

		return empty($id) ? strtolower($player->getName()) : $id;
	}
}