/*
 Erste TEST-SQL-Datei
 */


-- create
CREATE TABLE `mbx_exp_users` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(50) NULL DEFAULT NULL,
	`password` VARCHAR(50) NULL DEFAULT NULL,
	PRIMARY KEY (`ID`)
)
ENGINE=InnoDB;

-- insert
INSERT INTO mbx_exp_users (username, password) VALUES('test', 'test123');

-- select
SELECT
  *
FROM
  mbx_exp_users
WHERE
  username = 'test'; -- Get Special User

-- update
UPDATE mbx_exp_users SET username = "tested" WHERE username = "test";

-- alter table
ALTER TABLE `mbx_exp_users`
	ADD COLUMN `email` VARCHAR(50) NULL DEFAULT NULL AFTER `password`;



-- DEPLOYMENT/UNDO

DROP TABLE mbx_exp_users;