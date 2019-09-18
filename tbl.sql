-- --------------------------------------------------------
-- Хост:                         192.168.1.10
-- Версия сервера:               5.7.27-0ubuntu0.18.04.1 - (Ubuntu)
-- Операционная система:         Linux
-- HeidiSQL Версия:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп данных таблицы db_smart.mqtt_data: ~7 rows (приблизительно)
DELETE FROM `mqtt_data`;
/*!40000 ALTER TABLE `mqtt_data` DISABLE KEYS */;
INSERT INTO `mqtt_data` (`id`, `time`, `topic`, `value`) VALUES
	(1, '2019-09-18 20:24:59', '/WAVGAT_632B1/Pressure', 734.37),
	(2, '2019-09-18 20:22:48', '/wash_room/Humidity', 90.3),
	(3, '2019-09-18 20:16:59', '/wash_room/Relay', 1),
	(4, '2019-09-18 20:24:59', '/WAVGAT_632B1/Humidity', 73.9),
	(5, '2019-09-18 20:24:59', '/WAVGAT_632B1/Temperature', 14.25),
	(7, '2019-09-18 20:16:30', '/wash_room/Temperature', 22.3);
/*!40000 ALTER TABLE `mqtt_data` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
