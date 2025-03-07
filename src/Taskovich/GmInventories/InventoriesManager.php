<?php

namespace Taskovich\GmInventories;

use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use Taskovich\GmInventories\Main;

class InventoriesManager
{

	private Config $data_config;
	private array $data = [];

	public function __construct(private Main $main)
	{
		foreach ($this->data_config->getAll() as $id => $data)
		{
			$this->data[$id] = unserialize($data);
		}
	}

	public function saveAll(): void
	{

	}

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
		$id = match ($this->main->getConfig()->get("data_type")) {
			"xuid" => $player->getXuid(),
			"uuid" => $player->getUniqueId()->toString(),
			"name" => strtolower($player->getName()),
			default => null
		};

		return $id ?? strtolower($player->getName());
	}
}