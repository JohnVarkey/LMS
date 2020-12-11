-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2020 at 09:34 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `book1`
--

CREATE TABLE `book1` (
  `BOOK_ID` int(11) NOT NULL,
  `TITLE` varchar(255) NOT NULL,
  `AUTHOR` varchar(255) NOT NULL,
  `PUBLISHER` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book1`
--

INSERT INTO `book1` (`BOOK_ID`, `TITLE`, `AUTHOR`, `PUBLISHER`) VALUES
(100, 'Vampire Academy', 'Richelle Mead', 'Razorbill'),
(102, 'Vampire Academy - Last Sacrifice', 'Richelle Mead', 'Razorbill'),
(103, 'Harry Potter And The Cursed Child', 'J. K. Rowling', 'DC Books');

-- --------------------------------------------------------

--
-- Table structure for table `book2`
--

CREATE TABLE `book2` (
  `BOOK_ID` int(11) NOT NULL,
  `TITLE` varchar(255) NOT NULL,
  `AUTHOR` varchar(255) NOT NULL,
  `COPIES` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `book2`
--

INSERT INTO `book2` (`BOOK_ID`, `TITLE`, `AUTHOR`, `COPIES`) VALUES
(103, 'Harry Potter And The Cursed Child', 'J. K. Rowling', 5),
(100, 'Vampire Academy', 'Richelle Mead', 10),
(102, 'Vampire Academy - Last Sacrifice', 'Richelle Mead', 10);

-- --------------------------------------------------------

--
-- Table structure for table `borrow`
--

CREATE TABLE `borrow` (
  `BORROW_ID` int(11) NOT NULL,
  `ISSUE_DATE` date NOT NULL DEFAULT current_timestamp(),
  `USER_ID` int(11) DEFAULT NULL,
  `BOOK_ID` int(11) DEFAULT NULL,
  `RETURN_DATE` date DEFAULT NULL,
  `STATUS` varchar(255) DEFAULT 'Assigned'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `borrow`
--

INSERT INTO `borrow` (`BORROW_ID`, `ISSUE_DATE`, `USER_ID`, `BOOK_ID`, `RETURN_DATE`, `STATUS`) VALUES
(1, '2020-12-11', 13, 100, '2020-12-17', 'Returned'),
(3, '2020-12-11', 13, 103, '2020-12-17', 'Assigned'),
(4, '2020-12-11', 13, 103, '2020-12-17', 'Assigned'),
(5, '2020-12-11', 1, 100, '2020-12-12', 'Assigned');

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `BOOK_ID` int(11) NOT NULL,
  `PRICE` int(11) NOT NULL,
  `RELEASE_DATE` date NOT NULL,
  `EDITION` varchar(255) NOT NULL,
  `DESCRIPTION` varchar(4048) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `details`
--

INSERT INTO `details` (`BOOK_ID`, `PRICE`, `RELEASE_DATE`, `EDITION`, `DESCRIPTION`) VALUES
(100, 350, '2007-08-16', 'Vol 1', 'Rose Hathaway is a dhampir, half-Moroi and half-human, who is training to be a guardian at St. Vladimir\'s Academy along with many others like her. There are good and bad vampires in their world: Moroi, who co-exist peacefully among the humans and only take blood from donors, and also possess the ability to control one of the four elements - water, earth, fire or air; and Strigoi, blood-sucking, evil vampires who drink to kill. Rose and other dhampir guardians are trained to protect Moroi and kill Strigoi throughout their education. Along with her best friend, Princess Vasilisa Dragomir (Lissa), a Moroi and the last of her line, with whom she has a nigh unbreakable bond. Rose is able to feel Lissa\'s emotions through her bond and can sometimes enter her body without Lissa knowing when her emotions are too strong. Rose and Lissa ran away from their school, the vampire academy, two years ago and survive through the use of compulsion and by feeding off of each other. They had been moving from places to places, but this time, they got caught by the school guardians and returned to their school.'),
(102, 350, '2010-12-07', 'Vol 6', 'Lead character and dhampir Rosemarie Hathaway, is locked in Moroi jail after being framed in the cold-blooded murder of the Moroi Queen. The punishment for this crime is immediate execution. At the same time, she is faced with the challenge of somehow locating Princess Vasilisa Dragomir\'s lone remaining relative, her secretly existing illegitimate sibling.'),
(103, 350, '2016-07-30', 'Vol 8', 'Harry Potter and the Cursed Child is a 2016 British two-part play written by Jack Thorne based on an original story by J. K. Rowling, John Tiffany, and Thorne.[1] Previews of the play began at the Palace Theatre, London, on 7 June 2016,[2] and it premiered on 30 July 2016. The play opened on Broadway on 22 April 2018 at the Lyric Theatre, with previews starting on 16 March 2018. Its cast is similar to that of the first year in the West End, with returning actors Anthony Boyle, Sam Clemmett, Noma Dumezweni, Poppy Miller, Jamie Parker, Alex Price, and Paul Thornley.');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `BOOK_ID` int(11) NOT NULL,
  `IMAGE_URL` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`BOOK_ID`, `IMAGE_URL`) VALUES
(100, 'Vampire academy.jpg'),
(102, 'Last Sacrifice.jpeg'),
(103, '103HP.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `BOOK_ID` int(11) NOT NULL,
  `USER_ID` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`BOOK_ID`, `USER_ID`, `rating`, `review`) VALUES
(103, 13, 5, 'NIce Book. Great Work by the Author. Would Definitely Recommend'),
(103, 13, 5, 'Read It Again. Man I cant Stop Myself. What a Book'),
(103, 13, 5, 'qwerty'),
(103, 1, 5, 'Very Nice '),
(103, 1, 5, 'Very Nice');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int(11) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `Role`) VALUES
(1, 'Admin'),
(2, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `USER_ID` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `NAME` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `PHONE_NUMBER` int(11) NOT NULL,
  `ROLE` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`USER_ID`, `Email`, `NAME`, `PASSWORD`, `PHONE_NUMBER`, `ROLE`) VALUES
(1, 'librarian@jij.com', 'LIBRARIAN', '$2y$10$zmmUuCmcgQk.JS7EymZBHe9d5SV6tP6jofEcmYJL269qn7r/2bSMS', 1111111111, 1),
(13, 'varkey100john@gmail.com', 'John Koodarathil Varkey', '$2y$10$V8ogzfcSDqM6FzjVV5BpruIPfx329B6Q31LsFr421AjLoxmwoOwla', 2147483647, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book1`
--
ALTER TABLE `book1`
  ADD PRIMARY KEY (`BOOK_ID`);

--
-- Indexes for table `book2`
--
ALTER TABLE `book2`
  ADD PRIMARY KEY (`TITLE`,`AUTHOR`),
  ADD UNIQUE KEY `BOOK_ID` (`BOOK_ID`);

--
-- Indexes for table `borrow`
--
ALTER TABLE `borrow`
  ADD PRIMARY KEY (`BORROW_ID`),
  ADD KEY `USER_ID` (`USER_ID`),
  ADD KEY `BOOK_ID` (`BOOK_ID`);

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`BOOK_ID`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`BOOK_ID`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD KEY `BOOK_ID` (`BOOK_ID`),
  ADD KEY `USER_ID` (`USER_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`USER_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `borrow`
--
ALTER TABLE `borrow`
  MODIFY `BORROW_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `USER_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book2`
--
ALTER TABLE `book2`
  ADD CONSTRAINT `book2_ibfk_1` FOREIGN KEY (`BOOK_ID`) REFERENCES `book1` (`BOOK_ID`);

--
-- Constraints for table `borrow`
--
ALTER TABLE `borrow`
  ADD CONSTRAINT `borrow_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`),
  ADD CONSTRAINT `borrow_ibfk_2` FOREIGN KEY (`BOOK_ID`) REFERENCES `book1` (`BOOK_ID`);

--
-- Constraints for table `details`
--
ALTER TABLE `details`
  ADD CONSTRAINT `details_ibfk_1` FOREIGN KEY (`BOOK_ID`) REFERENCES `book1` (`BOOK_ID`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`BOOK_ID`) REFERENCES `book1` (`BOOK_ID`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`USER_ID`) REFERENCES `user` (`USER_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
