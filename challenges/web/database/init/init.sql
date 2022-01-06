SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci';

DROP DATABASE IF EXISTS web;
CREATE DATABASE IF NOT EXISTS web CHARACTER SET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

USE web;

CREATE TABLE IF NOT EXISTS `orders` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(30) NOT NULL,
    `email` VARCHAR(30) NOT NULL,
    `phone` VARCHAR(10) NOT NULL,
    `status` VARCHAR(10) NOT NULL DEFAULT 'PICKING',
    `sig_hash` VARCHAR(64) NOT NULL,
    `order_date` DATE NOT NULL,
    `address` TEXT NOT NULL,
    `note` TEXT NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `backend_users` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(30) NOT NULL,
    `password` VARCHAR(72) NOT NULL,
    `description` TEXT NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `items` (
    `id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `title` TEXT NOT NULL,
    `description` TEXT NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `options` (
    `key` VARCHAR(60) PRIMARY KEY,
    `value` VARCHAR(512) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rate_limit` (
    `ip` VARCHAR(64) NOT NULL,
    `last_visit` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    `visit_times` INTEGER NOT NULL DEFAULT 0,
    UNIQUE(ip)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders`
(`name`, `email`, `phone`, `status`, `sig_hash`, `order_date`, `address`)
VALUES
('Steven', 'steven@devcore.local', '0912345678', 'FINISH', '0077d9b7ab55a464a32723e7ca201786010f6fc598bb18fa97ba72975bc4a52e', '2021-11-01' , '115 Address with scvsDP{no.2_bre8k_acc3ss_c0ntrol}'),
('Steven', 'steven@devcore.local', '0912345678', 'ARRIVED', 'b07ac06083bbde4ff4d03a87f76bbad0103f5ffcaef5d1a9aa157881c1fe4187', '2021-11-01', '115 Address with scvsDP{no.2_bre8k_acc3ss_c0ntrol}'),
('Steven', 'steven@devcore.local', '0912345678', 'SENDING', 'f64744362d8065836a0106e5b50addae4844f5e0a1c0bd7cd59738c9d84ce642', '2021-11-01', '115 Address with scvsDP{no.2_bre8k_acc3ss_c0ntrol}');


INSERT INTO `backend_users` (`username`, `password`, `description`) VALUE ('admin', 'u=479_p5jV:Fsq(2', 'The administrator ( scvsDP{no.3_sql_injection_my_passw0rd_is_y0urs} ).');

INSERT INTO `items` (`id`, `title`, `description`) VALUE (1, 'HP Color LaserJet Pro M283fdw 彩色雷射多功能事務機', '<br /><p>🎉🎉🎉</p><p>為了慶祝自家 Research Team 參加 Pwn2Own Austin 2021 比賽，表現大放異彩，奪得亞軍寶座 🏆</p><p>因此 DEVCORE 在此決定免費贈送「HP Color LaserJet Pro M283fdw 彩色雷射多功能事務機」！！</p><p>只要填寫下方表單，我們就會將一台全新的多功能事務機寄送到您家，不需要支付任何費用或運費！</p><p>限時放送只開放報名到  2021/11/27 23:59:59。</p><br /><img src="/image.php?id=aHBfbTI4M2Zkdy5qcGc=" /><br /><br />');

REVOKE ALL ON *.* FROM web_user;
REVOKE ALL ON web.* FROM web_user;
GRANT SELECT ON web.* TO web_user;
GRANT INSERT ON web.options TO web_user;
GRANT INSERT, UPDATE ON web.rate_limit TO web_user;
GRANT INSERT, UPDATE(`status`) ON web.orders TO web_user;
