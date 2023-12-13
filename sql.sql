
CREATE DATABASE: `bookingcalender`


CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `regdate` date DEFAULT NULL,
  `timeslot` varchar(100) DEFAULT NULL,
  `specialist` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `bookings` (`id`, `name`, `email`, `regdate`, `timeslot`, `specialist`) VALUES
(22, 'test', 'test@test', '2023-12-11', '10:30AM-10:50AM', 'Vladimir Jakovenko'),
(23, 'test', 'test@test', '2023-12-15', '10:30AM-10:50AM', 'Anastasia Mironova'),
(24, 'Iavrov', 'BDShbshd@sjfdsh', '2023-12-14', '17:30PM-17:50PM', 'Vladimir Jakovenko'),
(25, 'fgfg', 'test@test', '2023-12-15', '11:30AM-11:50AM', 'Vladimir Jakovenko');


ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;
