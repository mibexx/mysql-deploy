CREATE TABLE `mbx_deployments` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT,
	`timest` DATETIME NOT NULL,
	PRIMARY KEY (`ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


CREATE TABLE `mbx_deployments_files` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT,
	`deploymentId` INT(11) NOT NULL,
	`sqlfile` TEXT NOT NULL,
	`statements_done` INT(11) NOT NULL,
	`statements_all` INT(11) NOT NULL,
	PRIMARY KEY (`ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=5;


CREATE TABLE `mbx_deployments_logs` (
	`ID` INT(11) NOT NULL AUTO_INCREMENT,
	`deploymentId` INT(11) NOT NULL,
	`message` TEXT NOT NULL,
	`additional_informations` LONGTEXT NOT NULL,
	PRIMARY KEY (`ID`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

