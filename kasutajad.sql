CREATE TABLE `kasutajad` (
                             `id` int(11) NOT NULL,
                             `kasutaja` varchar(255) NOT NULL,
                             `parool` varchar(255) NOT NULL,
                             `isadmin` int(11) NOT NULL
)


INSERT INTO `kasutajad` (`id`, `kasutaja`, `parool`, `isadmin`) VALUES
    (1, 'Diana', 'vaQ/hE1aox1Vs', 0);
COMMIT;