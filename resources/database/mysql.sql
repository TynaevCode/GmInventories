-- #! mysql

-- #{ table
	-- #{ init_survival
		CREATE TABLE IF NOT EXISTS Survival (
			id VARCHAR(255) PRIMARY KEY,
			main TEXT,
			armor TEXT,
			offhand TEXT
		);
	-- #}

	-- #{ init_creative
		CREATE TABLE IF NOT EXISTS Creative (
			id VARCHAR(255) PRIMARY KEY,
			main TEXT,
			armor TEXT,
			offhand TEXT
		);
	-- #}

	-- #{ init_adventure
		CREATE TABLE IF NOT EXISTS Adventure (
			id VARCHAR(255) PRIMARY KEY,
			main TEXT,
			armor TEXT,
			offhand TEXT
		);
	-- #}
-- #}

-- #{ Survival
	-- #{ get
		-- # :id string
		SELECT * FROM Survival WHERE id = :id;
	-- #}

	-- #{ save
		-- # :id string
		-- # :main string
		-- # :armor string
		-- # :offhand string
		INSERT INTO Survival (id, main, armor, offhand)
		VALUES (:id, :main, :armor, :offhand)
		ON DUPLICATE KEY UPDATE main = VALUES(main), armor = VALUES(armor), offhand = VALUES(offhand);
	-- #}

	-- #{ get_all
		SELECT * FROM Survival;
	-- #}
-- #}

-- #{ Creative
	-- #{ get
		-- # :id string
		SELECT * FROM Creative WHERE id = :id;
	-- #}

	-- #{ save
		-- # :id string
		-- # :main string
		-- # :armor string
		-- # :offhand string
		INSERT INTO Creative (id, main, armor, offhand)
		VALUES (:id, :main, :armor, :offhand)
		ON DUPLICATE KEY UPDATE main = VALUES(main), armor = VALUES(armor), offhand = VALUES(offhand);
	-- #}

	-- #{ get_all
		SELECT * FROM Creative;
	-- #}
-- #}

-- #{ Adventure
	-- #{ get
		-- # :id string
		SELECT * FROM Adventure WHERE id = :id;
	-- #}

	-- #{ save
		-- # :id string
		-- # :main string
		-- # :armor string
		-- # :offhand string
		INSERT INTO Adventure (id, main, armor, offhand)
		VALUES (:id, :main, :armor, :offhand)
		ON DUPLICATE KEY UPDATE main = VALUES(main), armor = VALUES(armor), offhand = VALUES(offhand);
	-- #}

	-- #{ get_all
		SELECT * FROM Adventure;
	-- #}
-- #}
