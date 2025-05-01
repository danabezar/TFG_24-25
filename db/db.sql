DROP DATABASE IF EXISTS tfg;

CREATE DATABASE tfg;
COMMIT;

USE tfg;

-- 'user' table definition
CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `victories` INT DEFAULT 0 COMMENT 'Matches won by the user',
  `losses` INT DEFAULT 0 COMMENT 'Matches lost by the user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'class' table definition
CREATE TABLE `class` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'class_growths' table definition
CREATE TABLE `class_growths` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `health` INT DEFAULT 10,
  `strength` INT DEFAULT 10,
  `magic` INT DEFAULT 10,
  `skill` INT DEFAULT 10,
  `speed` INT DEFAULT 10,
  `luck` INT DEFAULT 10,
  `defense` INT DEFAULT 10,
  `resistance` INT DEFAULT 10,
  `class_id` bigint(20) unsigned UNIQUE NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_growths_class_id_foreign` (`class_id`) USING BTREE,
  CONSTRAINT `class_growths_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'skill' table definition
CREATE TABLE `skill` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `type` varchar(50) NOT NULL, 
  `attribute` varchar(50) NOT NULL,
  `value` INT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'class_skill' table definition
CREATE TABLE `class_skill` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) unsigned NOT NULL,
  `skill_id` bigint(20) unsigned NOT NULL,
  `level_required` INT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_skill_class_id_foreign` (`class_id`) USING BTREE,
  KEY `class_skill_skill_id_foreign` (`skill_id`) USING BTREE,
  CONSTRAINT `class_skill_FK_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `class_skill_FK_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'unit' table definition
CREATE TABLE `unit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `class_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_class_id_foreign` (`class_id`) USING BTREE,
  CONSTRAINT `unit_FK` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'unit_base_stats' table definition
CREATE TABLE `unit_base_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) unsigned UNIQUE NOT NULL,
  `level` INT DEFAULT 1,
  `health` INT DEFAULT 20,
  `strength` INT DEFAULT 5,
  `magic` INT DEFAULT 5,
  `skill` INT DEFAULT 5,
  `speed` INT DEFAULT 5,
  `luck` INT DEFAULT 5,
  `defense` INT DEFAULT 5,
  `resistance` INT DEFAULT 5,
  PRIMARY KEY (`id`),
  KEY `unit_base_stats_id_foreign` (`unit_id`) USING BTREE,
  CONSTRAINT `unit_base_stats_FK` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'unit_growths' table definition
CREATE TABLE `unit_growths` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) unsigned UNIQUE NOT NULL,
  `health` INT DEFAULT 10,
  `strength` INT DEFAULT 10,
  `magic` INT DEFAULT 10,
  `skill` INT DEFAULT 10,
  `speed` INT DEFAULT 10,
  `luck` INT DEFAULT 10,
  `defense` INT DEFAULT 10,
  `resistance` INT DEFAULT 10,
  PRIMARY KEY (`id`),
  KEY `unit_growths_class_id_foreign` (`unit_id`) USING BTREE,
  CONSTRAINT `unit_growths_FK` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'user_unit' table definition, depicting how a 'user' has a 'unit' and viceversa
CREATE TABLE `user_unit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `level` INT DEFAULT 1 NOT NULL,
  `experience` INT DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_unit_user_id_foreign` (`user_id`) USING BTREE,
  KEY `user_unit_unit_id_foreign` (`unit_id`) USING BTREE,
  CONSTRAINT `user_unit_FK_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `user_unit_FK_unit` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'user_unit_stats' table definition
CREATE TABLE `user_unit_stat_gains` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_unit_id` bigint(20) unsigned UNIQUE NOT NULL,
  `health` INT DEFAULT 0 NOT NULL,
  `strength` INT DEFAULT 0 NOT NULL,
  `magic` INT DEFAULT 0  NOT NULL,
  `skill` INT DEFAULT 0  NOT NULL,
  `speed` INT DEFAULT 0  NOT NULL,
  `luck` INT DEFAULT 0  NOT NULL,
  `defense`  INT DEFAULT 0  NOT NULL,
  `resistance`  INT DEFAULT 0  NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_unit_stat_gains_user_unit_id_foreign` (`user_unit_id`) USING BTREE,
  CONSTRAINT `user_unit_stat_gains_FK` FOREIGN KEY (`user_unit_id`) REFERENCES `user_unit` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'user_unit_skill' table definition
CREATE TABLE `user_unit_skill` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_unit_id` bigint(20) unsigned NOT NULL,
  `skill_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_unit_skill_user_unit_id_foreign` (`user_unit_id`) USING BTREE,
  KEY `user_unit_skill_skill_id_foreign` (`skill_id`) USING BTREE,
  CONSTRAINT `user_unit_skill_FK_1` FOREIGN KEY (`user_unit_id`) REFERENCES `user_unit` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `user_unit_skill_FK_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'user_unit_equipped_skill' table definition
CREATE TABLE `user_unit_equipped_skill` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_unit_id` bigint(20) unsigned NOT NULL,
  `user_unit_skill_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_unit_equipped_skill_user_unit_id_foreign` (`user_unit_id`) USING BTREE,
  KEY `user_unit_equipped_skill_user_unit_skill_id_foreign` (`user_unit_skill_id`) USING BTREE,
  CONSTRAINT `user_unit_equipped_skill_FK_1` FOREIGN KEY (`user_unit_id`) REFERENCES `user_unit` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `user_unit_equipped_skill_FK_2` FOREIGN KEY (`user_unit_skill_id`) REFERENCES `user_unit_skill` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 'user' table default entries
INSERT INTO user (username, password) VALUES 
('Daniel','$2y$10$LpMHZpYiPB5bvfpe82kqquQxLn5bVYYU1jCkxSjbP.HBAKO6K0UAS'),
('Jesús','$2y$10$nOWI1dcWqFgewI3WmspnA.0JB.rs33aBRhKmsIS59LR9DDX/ezfMO');

COMMIT; 


-- 'class' table default entries
INSERT INTO class (name, type) VALUES
('Mercenary', 'Starter'),
('Myrmidon', 'Starter'),
('Archer', 'Starter'),
('Cavalier', 'Starter'),
('Wyvern Rider', 'Starter'),
('Mage', 'Starter'),
('Hero', 'Promoted'),
('Berserker', 'Promoted'),
('Swordmaster', 'Promoted'),
('Assassin', 'Promoted'),
('Sniper', 'Promoted'),
('Paladin', 'Promoted'),
('Great Knight', 'Promoted'),
('Wyvern Lord', 'Promoted'),
('Malig Knight', 'Promoted'),
('Sorcerer', 'Promoted'),
('Dark Knight', 'Promoted');

COMMIT;


-- 'class_growths' table default entries
INSERT INTO class_growths (health, strength, magic, skill, speed, luck, defense, resistance, class_id) VALUES
(10, 10, 0, 10, 5, 10, 10, 0, 1), -- Mercenary
(5, 10, 0, 5, 15, 5, 5, 10, 2), -- Myrmidon
(0, 5, 0, 15, 10, 5, 0, 10, 3), -- Archer
(10, 10, 0, 5, 10, 10, 15, 5, 4), -- Cavalier
(10, 15, 0, 10, 5, 5, 15, 0, 5), -- Wyvern Rider
(5, 0, 10, 5, 10, 5, 0, 15, 6), -- Mage
(15, 15, 0, 15, 10, 15, 15, 5, 7), -- Hero
(20, 20, 0, 10, 15, 5, 5, 5, 8), -- Berserker
(10, 10, 0, 15, 20, 10, 5, 10, 9), -- Swordmaster
(5, 15, 0, 20, 15, 15, 5, 15, 10), -- Assasin
(10, 10, 0, 20, 15, 10, 5, 10, 11), -- Sniper
(15, 10, 10, 10, 10, 15, 15, 10, 12), -- Paladin
(20, 15, 0, 15, 0, 10, 20, 0, 13), -- Great Knight
(20, 20, 0, 15, 10, 10, 15, 5, 14), -- Wyvern Lord
(15, 10, 15, 10, 10, 5, 10, 10, 15), -- Malig Knight
(5, 0, 20, 10, 15, 10, 0, 15, 16), -- Sorcerer
(10, 10, 10, 15, 10, 15, 15, 10, 17); -- Dark Knight

COMMIT;


-- 'skill' table default entries
INSERT INTO skill (name, type, attribute, value) VALUES
("Quick Draw", "Attacker Boost", "Attack", 4),
("Strong Riposte", "Defender Boost", "Attack", 3);

COMMIT;


-- 'class_skill' table default entries
INSERT INTO class_skill (class_id, skill_id, level_required) VALUES
(1, 2, 10), (3, 1, 10);

COMMIT;


-- 'unit' table default entries
INSERT INTO unit (name, class_id) VALUES
("Edelgard", 5),
("Shamir", 11),
("Alois", 8);

COMMIT;


-- 'unit_base_stats' table default entries
INSERT INTO unit_base_stats (unit_id, level, health, strength, magic, skill, speed, luck, defense, resistance) VALUES 
(1, 1, 29, 13, 6, 6, 8, 5, 6, 4),
(2, 11, 33, 18, 8, 21, 14, 17, 12, 8),
(3, 21, 50, 27, 8, 12, 15, 12, 18, 8);

COMMIT;


-- 'unit_growths' table default entries
INSERT INTO unit_growths (unit_id, health, strength, magic, skill, speed, luck, defense, resistance) VALUES
(1, 40, 55, 45, 45, 40, 30, 35, 35),
(2, 35, 40, 20, 55, 40, 55, 20, 15),
(3, 45, 45, 20, 35, 40, 30, 40, 20);

COMMIT;


-- 'user_unit' table default entries
INSERT INTO user_unit (user_id, unit_id) VALUES
(1, 1), (1, 2), (1, 3), (2, 3);

COMMIT;


-- 'user_unit_stat_gains' table default entries
INSERT INTO user_unit_stat_gains (user_unit_id) VALUES
(1), (2), (3), (4);

COMMIT;


-- 'user_unit_skill' table default entries
INSERT INTO user_unit_skill (user_unit_id, skill_id) VALUES 
(2, 1);

COMMIT;


-- 'user_unit_equipped_skill' table default entries
INSERT INTO user_unit_equipped_skill (user_unit_id, user_unit_skill_id) VALUES
(2, 1);

COMMIT;