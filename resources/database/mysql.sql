-- #! mysql

-- #{ table
	-- #{ init
		-- # :gamemode string
		CREATE TABLE IF NOT EXISTS :gamemode (
			id TEXT AUTO_INCREMENT PRIMARY KEY,
			main TEXT,
			armor TEXT,
			offhand TEXT
		);
	-- #}
-- #}

-- #{ data
	-- #{ get
		-- # :gamemode string
		-- # :id string
		SELECT * FROM :gamemode WHERE id = :id;
	-- #}

	-- #{ save
		-- # :gamemode string
		-- # :id string
		-- # :main string
		-- # :armor string
		-- # :offhand string
		INSERT INTO :gamemode (id, main, armor, offhand)
		VALUES (:id, :main, :armor, :offhand)
		ON DUPLICATE KEY UPDATE main = VALUES(main), armor = VALUES(armor), offhand = VALUES(offhand);
	-- #}

	-- #{ get_all
		-- # :gamemode string
		SELECT * FROM :gamemode;
	-- #}
-- #}