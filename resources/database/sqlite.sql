-- #! sqlite

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
		ON CONFLICT(id) DO
		UPDATE SET main = excluded.main, armor = excluded.armor, offhand = excluded.offhand;
	-- #}

	-- #{ get_all
		-- # :gamemode string
		SELECT * FROM :gamemode;
	-- #}
-- #}