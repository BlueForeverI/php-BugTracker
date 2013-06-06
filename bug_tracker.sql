-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2012 at 08:19 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bug_tracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `bugs`
--

CREATE TABLE IF NOT EXISTS `bugs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DateFound` text NOT NULL,
  `TesterUsername` varchar(15) DEFAULT NULL,
  `Description` text NOT NULL,
  `Priority` enum('Critical','High','Normal','Low') NOT NULL,
  `ProjectName` varchar(32) NOT NULL,
  `State` enum('New','In Progress','Fixed','Closed','Deleted') NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `bugs`
--

INSERT INTO `bugs` (`ID`, `DateFound`, `TesterUsername`, `Description`, `Priority`, `ProjectName`, `State`) VALUES
(3, '06/Jan/2012 20:06:41', 'mitko', 'Cannot export to .avi and to .wmv if the file is larger than 100MB', 'High', 'MovieMaker', 'In Progress'),
(4, '06/Jan/2012 20:15:29', 'Pesho', 'Cannot select cyrillic font', 'Critical', 'DocumentEditor', 'New'),
(8, '06/Jan/2012 20:27:07', 'kiro', 'The torrent description is not displayed good in Firefox versions prior to 8.0', 'Low', 'ZetTorrents', 'New'),
(9, '06/Jan/2012 20:28:04', 'kiro', 'The search engine is ignoring whitespaces', 'Normal', 'ZetTorrents', 'In Progress'),
(11, '07/Jan/2012 13:39:37', 'Pesho', 'Should work with whole numbers for years', 'High', 'DeathCalculator', 'New'),
(12, '07/Jan/2012 13:49:29', 'kiro', 'Crashes when larger value for year is specified', 'Normal', 'DeathCalculator', 'New'),
(13, '07/Jan/2012 19:17:45', 'tisho', 'Does not support subtitles with UTF-16 encoding', 'Low', 'MovieMaker', 'New'),
(14, '07/Jan/2012 19:57:50', 'mitko', 'PDF loads too slow in read-only mode', 'Low', 'DocumentEditor', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(32) NOT NULL,
  `Description` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`ID`, `ProjectName`, `Description`) VALUES
(1, 'DocumentEditor', 'A document editing software. Supports formats such as .doc, .pdf and .xls '),
(2, 'MovieMaker', 'A program for creating and editing video. Can export to .avi and .wmv'),
(3, 'DeathCalculator', 'A web application that calculates the date of your death by given personal information.'),
(4, 'ZetTorrents', 'A torrent tracker with user submitted content. Contains movies, music, software, documents and XXX');

-- --------------------------------------------------------

--
-- Table structure for table `testers`
--

CREATE TABLE IF NOT EXISTS `testers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(15) NOT NULL,
  `PasswordMd5` varchar(512) NOT NULL,
  `FirstName` varchar(15) DEFAULT NULL,
  `LastName` varchar(15) DEFAULT NULL,
  `Email` varchar(32) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `LastVisit` text NOT NULL,
  `LastAction` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `testers`
--

INSERT INTO `testers` (`ID`, `Username`, `PasswordMd5`, `FirstName`, `LastName`, `Email`, `Telephone`, `LastVisit`, `LastAction`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', NULL, NULL, 'support@bugtracker.net', NULL, '0000-00-00', ''),
(4, 'Pesho', 'd9a5f3725f900432926e465a273b7cb8', 'Petar', 'Georgiev', 'pesho_picha@abv.bg', '0273199', '07/Jan/2012 13:47:08', 'added a bug to project DeathCa'),
(5, 'mitko', 'd880e4a4b8a80eb33c1c40604930b79c', 'Dimitar', 'Petrov', 'mitko@bugtracker.net', '04815599', '07/Jan/2012 20:16:32', 'added a bug to project Documen'),
(6, 'kiro', '323d017d19e2e860a732df8b67b26aa9', 'Kiril', 'Jordanov', 'kiro@bugtracker.net', '025007011', '07/Jan/2012 13:47:52', 'added a bug to project DeathCa'),
(7, 'tisho', '64eb2d692424169c1aadb9dfd21b3707', 'Tihomir', 'Nikolov', 'tisho@bugtracker.net', '068631821', '07/Jan/2012 19:17:50', 'added a bug to project MovieMa');

-- --------------------------------------------------------

--
-- Table structure for table `testers_projects`
--

CREATE TABLE IF NOT EXISTS `testers_projects` (
  `ProjectName` varchar(32) NOT NULL,
  `TesterUsername` varchar(15) NOT NULL,
  PRIMARY KEY (`ProjectName`,`TesterUsername`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `testers_projects`
--

INSERT INTO `testers_projects` (`ProjectName`, `TesterUsername`) VALUES
('DeathCalculator', 'kiro'),
('DeathCalculator', 'Pesho'),
('DocumentEditor', 'mitko'),
('DocumentEditor', 'Pesho'),
('MovieMaker', 'mitko'),
('MovieMaker', 'tisho'),
('ZetTorrents', 'kiro');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
