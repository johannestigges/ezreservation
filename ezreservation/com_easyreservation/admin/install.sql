--
-- drop tables if existing
--
DROP TABLE IF EXISTS `#__ezr_reservable`;
DROP TABLE IF EXISTS `#__ezr_reservation`;
DROP TABLE IF EXISTS `#__ezr_occupation`;
DROP TABLE IF EXISTS `#__ezr_protocol`;

--
-- create table of reservble objects
--
CREATE TABLE IF NOT EXISTS `#__ezr_reservable` (
  `id` 					int(11) 		NOT NULL AUTO_INCREMENT,
  `name` 				varchar(255) 	DEFAULT NULL,
  `description` 		varchar(10000) 	DEFAULT NULL,
  PRIMARY KEY (`id`)
);

--
-- create table of reservations
--
CREATE TABLE IF NOT EXISTS `#__ezr_reservation` (
  `id` 					int(11) 		NOT NULL AUTO_INCREMENT,
  `name` 				varchar(255) 	DEFAULT NULL,
  `description`			varchar(10000)	DEFAULT NULL,
  `reservation_type` 	int(4) 			NOT NULL,
  `user_id` 			int(11) 		DEFAULT NULL,
  `start_time`			datetime 		NOT NULL,
  `end_time` 			datetime 		NOT NULL,
  `id_reservable` 		int(11) 		NOT NULL,
  `start_day`			datetime 		DEFAULT NULL,
  `end_day` 			datetime 		DEFAULT NULL, 
  `created` 			datetime 		NOT NULL,
  PRIMARY KEY (`id`)
);

--
-- create table of occupations
--
CREATE TABLE IF NOT EXISTS `#__ezr_occupation` (
  `id_reservable` 		int(11) 		NOT NULL,
  `start_time` 			datetime 		NOT NULL,
  `id_reservation` 		int(11) 		NOT NULL,
 PRIMARY KEY (`id_reservable`,`start_time`)
);

--
-- create table of reservation protocols
--
CREATE TABLE IF NOT EXISTS `#__ezr_protocol` (
  `id_reservation` 		int(11) 		NOT NULL,
  `created` 			datetime 		NOT NULL,
  `user_id` 			int(11) 		NOT NULL,
  `description` 		varchar(10000) 	DEFAULT NULL,
 PRIMARY KEY (`id_reservation`,`created`)
 );
 
--
-- load some sample data into tables 
-- 
INSERT 
INTO `#__ezr_reservable` 
	 	( `id`	, `name` 		, `description` 	)
VALUES 	( 1		, 'Platz 1'		, 'Hallenplatz 1' 	),
       	( 2		, 'Platz 2'		, 'Hallenplatz 2' 	)
;