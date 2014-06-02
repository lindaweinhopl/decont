-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2014 at 03:31 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `decont`
--
CREATE DATABASE IF NOT EXISTS `decont` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `decont`;

-- --------------------------------------------------------

--
-- Table structure for table `appdata`
--

CREATE TABLE IF NOT EXISTS `appdata` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `property` text NOT NULL,
  `value` text NOT NULL,
  `date_modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `appdata`
--

INSERT INTO `appdata` (`id`, `property`, `value`, `date_modified`, `modified_by`) VALUES
(1, 'whiteboard', 'On this whiteboard, usefull links and information may be shared with the other users.', '2014-05-21 16:29:58', 1);

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE IF NOT EXISTS `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `submited_by` int(11) NOT NULL,
  `date_submited` datetime NOT NULL,
  `data` longtext NOT NULL,
  `title` varchar(65) NOT NULL,
  `description` varchar(65) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(65) NOT NULL,
  `description` varchar(65) NOT NULL,
  `create_user` tinyint(1) NOT NULL,
  `delete_user` tinyint(1) NOT NULL,
  `edit_user` tinyint(1) NOT NULL,
  `create_report` tinyint(1) NOT NULL,
  `edit_report` tinyint(1) NOT NULL,
  `delete_report` tinyint(1) NOT NULL,
  `create_role` tinyint(1) NOT NULL,
  `edit_role` tinyint(1) NOT NULL,
  `delete_role` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`, `description`, `create_user`, `delete_user`, `edit_user`, `create_report`, `edit_report`, `delete_report`, `create_role`, `edit_role`, `delete_role`) VALUES
(1, 'admin', 'Full privilegies', 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 'guest', 'Read Only', 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `date_created` datetime NOT NULL,
  `role` int(10) unsigned NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `salt`, `date_created`, `role`, `first_name`, `last_name`, `created_by`) VALUES
(1, 'admin@e-uvt.ro', 'a2ed5e9feed1708313362c9762150957830b90a6', 'fb21197446e5adec0d7a3d4bea20863f8218d733', '2014-05-17 21:46:00', 1, 'Admin', '', 1),
(2, 'petrutlucian94@gmail.com', 'b3404cda48cf3ca596c539dfd0e3c36340153a0d', 'b9c7384756cea11800d47ab73c51e98ff4692111', '2014-05-19 00:29:21', 1, 'Lucian', 'Petrut', 1),
(5, 'doe@e-uvt.ro', '0686db426b1945e79c1bdd1a5b583538c7820ce8', '2421ea7c117fae54bbef13e9a982ee2b153cd5e7', '2014-05-19 21:36:33', 2, 'John', 'Doe', 1),
(6, 'test', 'fab6ccf725243aca65aea914bb80b46d20640ef0', 'dcf842c8e7c1386815e5157296619a29cdc72fd7', '2014-05-21 16:14:21', 2, 'test', 'test', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
