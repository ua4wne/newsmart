-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.7.25 - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп данных таблицы db_smart.protocol: ~4 rows (приблизительно)
DELETE FROM `protocol`;
/*!40000 ALTER TABLE `protocol` DISABLE KEYS */;
INSERT INTO `protocol` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'none', 'Без протокола', '2019-01-28 19:58:58', '2019-01-28 19:58:58'),
	(2, 'http', 'HTTP(S)', '2019-01-28 20:02:20', '2019-01-28 20:02:20'),
	(3, 'mqtt', 'MQTT', '2019-01-28 20:02:40', '2019-01-28 20:02:40'),
	(4, 'mix', 'HTTP(S)+MQTT', '2019-01-28 20:03:05', '2019-01-28 20:03:05');
/*!40000 ALTER TABLE `protocol` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
