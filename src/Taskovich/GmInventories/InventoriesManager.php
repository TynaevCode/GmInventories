<?php

namespace Taskovich\GmInventories;

use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;

class InventoriesManager
{

	private const GAMEMODES = ["Survival", "Creative", "Adventure"];

	private DataConnector $db;
	private array $inventories = [];

	public function __construct(private Main $main)
	{
		$this->db = libasynql::create(
			$main,
			$main->getConfig()->get("database"),
			[
				"mysql"  => "database/mysql.sql",
				"sqlite" => "database/sqlite.sql"
			]
		);
		$this->loadAll();
	}

	public function saveAll(): void
	{
		foreach ($this->inventories as $id => $gmInventories) {
			foreach ($gmInventories as $gamemode => $contents) {
				$serialized = array_map(
					fn(array $items) => serialize(array_map(fn(Item $item) => $item->nbtSerialize(), $items)),
					$contents
				);

				$serialized["id"] = $id;
				$this->db->executeInsert("{$gamemode}.save", $serialized);
			}
		}

		$this->db->waitAll();
	}

	public function loadAll(): void
	{
		$this->db->executeGeneric("table.init_survival");
		$this->db->executeGeneric("table.init_creative");
		$this->db->executeGeneric("table.init_adventure");

		$this->db->waitAll();

		foreach (self::GAMEMODES as $gamemode) {
			$this->db->executeSelect("{$gamemode}.get_all", [],
				function (array $rows) use ($gamemode) {
					foreach ($rows as $row) {
						$id = $row["id"];
						unset($row["id"]);

						foreach ($row as $type => $serializedItems) {
							$itemsData = unserialize($serializedItems);

							$this->inventories[$id][$gamemode][$type] = array_map(
								fn(CompoundTag $tag) => Item::nbtDeserialize($tag),
								$itemsData
							);
						}
					}
				}
			);
		}

		$this->db->waitAll();
	}

	public function saveInventory(Player $player): void
	{
		$gm = $player->getGamemode()->getEnglishName();

		if (!in_array($gm, self::GAMEMODES, true)) {
			return;
		}

		$id = $this->getId($player);

		$this->inventories[$id][$gm] = [
			"main" => $player->getInventory()->getContents(),
			"offhand" => $player->getOffHandInventory()->getContents(),
			"armor" => $player->getArmorInventory()->getContents()
		];
	}

	public function loadInventory(Player $player, GameMode $gameMode): void
	{
		$gm = $gameMode->getEnglishName();

		if (!in_array($gm, self::GAMEMODES, true)) {
			return;
		}

		$id = $this->getId($player);

		$inventory = $this->inventories[$id][$gm] ?? [
			"main" => [],
			"armor" => [],
			"offhand" => []
		];

		foreach ($inventory as $type => $items) {
			match ($type) {
				"main" => $player->getInventory()->setContents($items),
				"armor" => $player->getArmorInventory()->setContents($items),
				"offhand" => $player->getOffHandInventory()->setContents($items),
				default => null
			};
		}
	}

	private function getId(Player $player): string
	{
		return match ($this->main->getConfig()->get("data_type")) {
			"xuid" => $player->getXuid(),
			"uuid" => $player->getUniqueId()->toString(),
			"name" => strtolower($player->getName()),
			default => strtolower($player->getName())
		};
	}
}
