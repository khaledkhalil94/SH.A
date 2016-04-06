/*CREATE DATABASE `sha` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE `sha`;

CREATE TABLE IF NOT EXISTS `students` (
 `sku` int(11) DEFAULT NULL,
 `name` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
 `address` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
 `img` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL
)

ENGINE = innoDB DEFAULT CHARSET=UTF8 COLLATE=utf8_unicode_ci;

INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5501, 'student1', 'wagma Street', 'images.png');
INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5502, 'student2', 'wagma Street', 'images.png');
INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5503, 'student3', 'wagma Street', 'images.png');
INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5504, 'student4', 'wagma Street', 'images.png');
INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5505, 'student5', 'wagma Street', 'images.png');
INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5506, 'student6', 'wagma Street', 'images.png');
INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5507, 'student7', 'wagma Street', 'images.png');
INSERT INTO `students` (`sku`, `name`, `address`, `img`) VALUES(5508, 'student8', 'wagma Street', 'images.png');
