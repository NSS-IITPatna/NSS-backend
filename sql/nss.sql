-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2019 at 04:03 PM
-- Server version: 5.7.25-0ubuntu0.16.04.2
-- PHP Version: 7.0.33-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nss`
--

-- --------------------------------------------------------

--
-- Table structure for table `blood_request`
--

CREATE TABLE `blood_request` (
  `seq` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `reciever` varchar(50) NOT NULL,
  `id` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `given` int(11) NOT NULL DEFAULT '0',
  `mail_sent` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hours`
--

CREATE TABLE `hours` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `hours` int(11) NOT NULL DEFAULT '0',
  `reason` varchar(150) NOT NULL DEFAULT '-',
  `date` date NOT NULL,
  `entry_id` varchar(120) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `thank`
--

CREATE TABLE `thank` (
  `seq` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `mail_sent` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `think`
--

CREATE TABLE `think` (
  `seq` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `idea` varchar(1000) NOT NULL,
  `mail_sent` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `email` varchar(90) NOT NULL,
  `password` varchar(50) NOT NULL,
  `roll_no` varchar(70) NOT NULL,
  `cell` varchar(50) NOT NULL,
  `access_level` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blood_request`
--
ALTER TABLE `blood_request`
  ADD PRIMARY KEY (`seq`);

--
-- Indexes for table `hours`
--
ALTER TABLE `hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entry_id` (`entry_id`);

--
-- Indexes for table `thank`
--
ALTER TABLE `thank`
  ADD PRIMARY KEY (`seq`);

--
-- Indexes for table `think`
--
ALTER TABLE `think`
  ADD PRIMARY KEY (`seq`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`roll_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blood_request`
--
ALTER TABLE `blood_request`
  MODIFY `seq` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hours`
--
ALTER TABLE `hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `thank`
--
ALTER TABLE `thank`
  MODIFY `seq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `think`
--
ALTER TABLE `think`
  MODIFY `seq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
