-- Tabla para visitas
CREATE TABLE IF NOT EXISTS `visits` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `page` VARCHAR(50) NOT NULL,
    `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `ip_address` VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla para reproducciones de audio
CREATE TABLE IF NOT EXISTS `audio_plays` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `audio_name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `ip_address` VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla para clicks en anuncios
CREATE TABLE IF NOT EXISTS `ad_clicks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `ad_name` VARCHAR(255) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `timestamp` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `ip_address` VARCHAR(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;