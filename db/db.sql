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
  `master_seals` INT DEFAULT 0 COMMENT 'Number of promotions ready to use the user has',
  `lottery_vouchers` INT DEFAULT 0 COMMENT 'Number of tries at the lottery available',
  `master_seal_lock` INT DEFAULT 0 COMMENT 'Dictates whether the User can or not get Master Seals',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'class' table definition
CREATE TABLE `class` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `type` varchar(50) NOT NULL,
  `dmg_type` tinyint(1) NOT NULL,
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

-- 'class_promotion' table definition
CREATE TABLE `class_promotion` (
  `starter_id` bigint(20) unsigned NOT NULL,
  `promoted_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`starter_id`, `promoted_id`),
  KEY `class_promotion_starter_id_foreign` (`starter_id`) USING BTREE,
  KEY `class_promotion_promoted_id_foreign` (`promoted_id`) USING BTREE,
  CONSTRAINT `class_promotion_starter_id_FK` FOREIGN KEY (`starter_id`) REFERENCES `class` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `class_promotion_promoted_id_FK` FOREIGN KEY (`promoted_id`) REFERENCES `class` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'skill' table definition
CREATE TABLE `skill` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) UNIQUE NOT NULL,
  `type` varchar(50) NOT NULL, 
  `attribute` varchar(50) NOT NULL,
  `value` INT NOT NULL,
  `description` varchar(255),
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
  CONSTRAINT `class_skill_FK_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `class_skill_FK_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
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
  CONSTRAINT `unit_base_stats_FK` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
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
  CONSTRAINT `unit_growths_FK` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'user_unit' table definition, depicting how a 'user' has a 'unit' and viceversa
CREATE TABLE `user_unit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `unit_id` bigint(20) unsigned NOT NULL,
  `class_id` bigint(20) unsigned NOT NULL,
  `level` INT DEFAULT 1 NOT NULL,
  `experience` INT DEFAULT 0 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_unit_user_id_foreign` (`user_id`) USING BTREE,
  KEY `user_unit_unit_id_foreign` (`unit_id`) USING BTREE,
  KEY `user_unit_class_id_foreign` (`class_id`) USING BTREE,
  CONSTRAINT `user_unit_FK_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `user_unit_FK_unit` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `user_unit_FK_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON UPDATE CASCADE
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
  CONSTRAINT `user_unit_stat_gains_FK` FOREIGN KEY (`user_unit_id`) REFERENCES `user_unit` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'user_unit_skill' table definition
CREATE TABLE `user_unit_skill` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_unit_id` bigint(20) unsigned NOT NULL,
  `skill_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_unit_skill_user_unit_id_foreign` (`user_unit_id`) USING BTREE,
  KEY `user_unit_skill_skill_id_foreign` (`skill_id`) USING BTREE,
  CONSTRAINT `user_unit_skill_FK_1` FOREIGN KEY (`user_unit_id`) REFERENCES `user_unit` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `user_unit_skill_FK_2` FOREIGN KEY (`skill_id`) REFERENCES `skill` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 'user_unit_equipped_skill' table definition
CREATE TABLE `user_unit_equipped_skill` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_unit_id` bigint(20) unsigned NOT NULL,
  `user_unit_skill_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_unit_equipped_skill_user_unit_id_foreign` (`user_unit_id`) USING BTREE,
  KEY `user_unit_equipped_skill_user_unit_skill_id_foreign` (`user_unit_skill_id`) USING BTREE,
  CONSTRAINT `user_unit_equipped_skill_FK_1` FOREIGN KEY (`user_unit_id`) REFERENCES `user_unit` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `user_unit_equipped_skill_FK_2` FOREIGN KEY (`user_unit_skill_id`) REFERENCES `user_unit_skill` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 'user' table default entries
INSERT INTO user (username, password) VALUES 
('Daniel','$2y$10$LpMHZpYiPB5bvfpe82kqquQxLn5bVYYU1jCkxSjbP.HBAKO6K0UAS'),
('Admin', '$2y$10$lidQVNZ4ofFK2jkOXerwL.As6xuOizEml8gWgTwypdjmiawFIMXkK'),
('Rogelia','$2y$10$VYVoUs2VWwHq/0lRtrUC9ufmj5XTPfUg2xmrDRr77LpDHC.nvjSMS'),
('Gervasio', '$2y$10$qkhsxXVijEIbWlDbMjC8SO1AYRpZb.IKtwpfe8ZKAQHLcj2M3SUtK');

COMMIT; 


-- 'class' table default entries
INSERT INTO class (name, type, dmg_type) VALUES
('Mercenary', 'Starter', 0),
('Myrmidon', 'Starter', 0),
('Archer', 'Starter', 0),
('Cavalier', 'Starter', 0),
('Wyvern Rider', 'Starter', 0),
('Mage', 'Starter', 1),
('Hero', 'Promoted', 0),
('Berserker', 'Promoted', 0),
('Swordmaster', 'Promoted', 0),
('Assassin', 'Promoted', 0),
('Sniper', 'Promoted', 0),
('Paladin', 'Promoted', 0),
('Great Knight', 'Promoted', 0),
('Wyvern Lord', 'Promoted', 0),
('Sorcerer', 'Promoted', 1);

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
(5, 0, 20, 10, 15, 10, 0, 15, 15); -- Sorcerer

COMMIT;

-- 'class_promotion' table default entries
INSERT INTO class_promotion (starter_id, promoted_id) VALUES
(1, 7), (1, 8),
(2, 9), (2, 10),
(3, 11),
(4, 12), (4, 13),
(5, 14),
(6, 15);

COMMIT;


-- 'skill' table default entries
INSERT INTO skill (name, type, attribute, value, description) VALUES
("HP +5", "Stat Boost", "Health", 5, "Grants +5 to Unit's Health Points"),
("Strong Riposte", "Defender Boost", "Attack", 3, "If unit does not attack first, deal 3 additional damage"),
("Speed +2", "Stat Boost", "Speed", 2, "Grants +2 to Unit's Speed"),
("Avoid +10", "Stat Boost", "Avoid", 10, "Grants +10 to Unit's Avoid"),
("Skill +2", "Stat Boost", "Skill", 2, "Grants +2 to Unit's Skill"),
("Quick Draw", "Attacker Boost", "Attack", 4, "If unit attacks first, deal 4 additional damage"),
("Defense +2", "Stat Boost", "Defense", 2, "Grants +2 to Unit's Defense"),
("Toughness", "Defender Boost", "Reduction", 4, "If unit does not attack first, receive 4 less damage"),
("Strength +2", "Stat Boost", "Strength", 2, "Grants +2 to Unit's Strength"),
("Death Blow", "Attacker Boost", "Crit", 20, "If unit attacks first, grants +20 Crit"),
("Magic +2", "Stat Boost", "Magic", 2, "Grants +2 to Unit's Magic"),
("Hit +20", "Stat Boost", "Hit", 20, "Grants +20 to Unit's Hit"),
("Veteran Intuition", "Stat Boost", "Dodge", 30, "Grants +30 to Unit's Dodge"),
("Grisly Rage", "Defender Boost", "Speed", 6, "If unit does not attack first, grants +6 to Unit's Speed"),
("Duelist's Blow", "Attacker Boost", "Avoid", 20, "If unit attacks first, grants +20 to Unit's Avoid"),
("Crit +30", "Stat Boost", "Crit", 30, "Grants +30 to Unit's Crit"),
("Certain Blow", "Attacker Boost", "Hit", 30, "If unit attacks first, grants +30 to Unit's Hit"),
("Warding Blow", "Attacker Boost", "Resistance", 10, "If unit attacks first, grants +10 to Unit's Resistance"),
("Armored Blow", "Attacker Boost", "Defense", 10, "If unit attacks first, grants +10 to Unit's Defense"),
("Sturdy Hide", "Defender Boost", "Defense", 6, "If unit does not attack first, grants +6 to Unit's Defense"),
("Fiendish Blow", "Attacker Boost", "Magic", 6, "If unit attacks first, grants +6 to Unit's Magic");

COMMIT;


-- 'class_skill' table default entries
INSERT INTO class_skill (class_id, skill_id, level_required) VALUES
(1, 1, 1), (1, 2, 10), 
(2, 3, 1), (2, 4, 10),
(3, 5, 1), (3, 6, 10),
(4, 7, 1), (4, 8, 10),
(5, 9, 1), (5, 10, 10),
(6, 11, 1), (6, 12, 10),
(7, 13, 5),
(8, 14, 5),
(9, 15, 5),
(10, 16, 5),
(11, 17, 5),
(12, 18, 5),
(13, 19, 5),
(14, 20, 5),
(15, 21, 5);

COMMIT;


-- 'unit' table default entries
INSERT INTO unit (name, class_id) VALUES
("Edelgard", 5),
("Dimitri", 4),
("Claude", 3),
("Felix", 2), 
("Hapi", 6),
("Shamir", 3),
("Alois", 8);

COMMIT;


-- 'unit_base_stats' table default entries
INSERT INTO unit_base_stats (unit_id, level, health, strength, magic, skill, speed, luck, defense, resistance) VALUES 
(1, 1, 29, 13, 6, 6, 8, 5, 6, 4),
(2, 1, 28, 12, 4, 7, 7, 5, 7, 4),
(3, 1, 26, 11, 5, 8, 8, 7, 6, 4),
(4, 1, 26, 10, 5, 6, 9, 5, 5, 3),
(5, 3, 27, 7, 12, 9, 7, 4, 4, 8),
(6, 11, 33, 18, 8, 21, 14, 17, 12, 8),
(7, 1, 50, 27, 8, 12, 15, 12, 18, 8);

COMMIT;


-- 'unit_growths' table default entries
INSERT INTO unit_growths (unit_id, health, strength, magic, skill, speed, luck, defense, resistance) VALUES
(1, 40, 55, 45, 45, 40, 30, 35, 35),
(2, 55, 60, 20, 50, 50, 25, 40, 20),
(3, 35, 40, 25, 60, 55, 45, 30, 25),
(4, 45, 55, 30, 45, 55, 40, 30, 20),
(5, 35, 35, 45, 45, 40, 20, 15, 45),
(6, 35, 40, 20, 55, 40, 55, 20, 15),
(7, 45, 45, 20, 35, 40, 30, 40, 20);

COMMIT;


-- 'user_unit' table default entries
INSERT INTO user_unit (user_id, unit_id, class_id, level) VALUES
(1, 2, 4, 1), (1, 4, 2, 1), (1, 5, 6, 1),
(2, 1, 14, 1), (2, 2, 4, 9), (2, 7, 8, 1),
(3, 3, 3, 5), (3, 7, 8, 1), (3, 6, 11, 11),
(4, 2, 4, 1), (4, 3, 3, 1);

COMMIT;


-- 'user_unit_stat_gains' table default entries
INSERT INTO user_unit_stat_gains (user_unit_id, health, strength, magic, skill, speed, luck, defense, resistance) VALUES
(1, 0, 0, 0, 0, 0, 0, 0, 0), (2, 0, 0, 0, 0, 0, 0, 0, 0), (3, 0, 0, 0, 0, 0, 0, 0, 0), 
(4, 12, 15, 3, 9, 8, 6, 13, 7), (5, 7, 8, 1, 5, 7, 6, 9, 2), (6, 4, 3, 0, 2, 1, 4, 2, 0),
(7, 0, 0, 0, 0, 0, 0, 0, 0), (8, 0, 0, 0, 0, 0, 0, 0, 0), (9, 0, 0, 0, 0, 0, 0, 0, 0), 
(10, 0, 0, 0, 0, 0, 0, 0, 0), (11, 0, 0, 0, 0, 0, 0, 0, 0);

COMMIT;


-- 'user_unit_skill' table default entries
INSERT INTO user_unit_skill (user_unit_id, skill_id) VALUES 
(4, 3), (4, 6), (4, 9), (4, 10),
(5, 2), (5, 9), (5, 10), (5, 13),
(6, 3), (6, 6), (6, 9), (6, 10), (6, 16);

COMMIT;