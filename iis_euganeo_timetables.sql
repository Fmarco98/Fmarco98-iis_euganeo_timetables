-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 15, 2025 alle 19:36
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iis_euganeo_timetables`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `aula`
--

CREATE TABLE `aula` (
  `id_aula` int(11) NOT NULL,
  `nome` varchar(30) DEFAULT NULL,
  `piano` int(1) NOT NULL,
  `n_aula` int(2) NOT NULL,
  `fk_plesso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `aula`
--

INSERT INTO `aula` (`id_aula`, `nome`, `piano`, `n_aula`, `fk_plesso`) VALUES
(5, '', 0, 1, 1),
(6, '', 0, 2, 1),
(7, 'LTR1', 1, 17, 1),
(8, '', 0, 1, 2),
(9, '', 0, 1, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `fascia_oraria`
--

CREATE TABLE `fascia_oraria` (
  `id_fascia_oraria` int(11) NOT NULL,
  `ora_inizio` time NOT NULL,
  `ora_fine` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fascia_oraria`
--

INSERT INTO `fascia_oraria` (`id_fascia_oraria`, `ora_inizio`, `ora_fine`) VALUES
(9, '07:50:00', '08:45:00'),
(1, '07:55:00', '08:55:00'),
(10, '08:45:00', '09:35:00'),
(5, '08:55:00', '09:55:00'),
(11, '09:35:00', '10:25:00'),
(6, '09:55:00', '10:55:00'),
(15, '10:10:00', '11:10:00'),
(12, '10:40:00', '11:30:00'),
(7, '11:10:00', '12:10:00'),
(13, '11:30:00', '12:20:00'),
(8, '12:10:00', '13:10:00'),
(14, '12:20:00', '13:15:00');

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `fascia_oraria_giorno`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `fascia_oraria_giorno` (
`giorno` int(11)
,`fascia_oraria` int(11)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `giorno`
--

CREATE TABLE `giorno` (
  `id_giorno` int(11) NOT NULL,
  `nome` varchar(10) NOT NULL,
  `fk_tipo_orario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `giorno`
--

INSERT INTO `giorno` (`id_giorno`, `nome`, `fk_tipo_orario`) VALUES
(1, 'Lunedì', 1),
(2, 'Martedì', 2),
(3, 'Mercoledì', 1),
(4, 'Giovedì', 2),
(5, 'Venerdì', 2),
(6, 'Sabato', 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `plesso`
--

CREATE TABLE `plesso` (
  `id_plesso` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `plesso`
--

INSERT INTO `plesso` (`id_plesso`, `nome`) VALUES
(3, 'Duca'),
(2, 'Fermi'),
(1, 'IIS Euganeo');

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazione`
--

CREATE TABLE `prenotazione` (
  `id_prenotazione` int(11) NOT NULL,
  `descrizione` varchar(500) DEFAULT NULL,
  `data` date NOT NULL,
  `fk_utente` int(11) NOT NULL,
  `fk_fascia_oraria` int(11) NOT NULL,
  `fk_aula` int(11) NOT NULL,
  `data_richiesta` datetime NOT NULL DEFAULT current_timestamp(),
  `data_approvazione` datetime DEFAULT NULL,
  `data_eliminazione` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prenotazione`
--

INSERT INTO `prenotazione` (`id_prenotazione`, `descrizione`, `data`, `fk_utente`, `fk_fascia_oraria`, `fk_aula`, `data_richiesta`, `data_approvazione`, `data_eliminazione`) VALUES
(5, 'no', '2025-04-10', 2, 1, 7, '2025-04-06 18:34:52', NULL, '2025-04-06 18:42:24'),
(12, 'ciao', '2025-04-07', 2, 5, 7, '2025-04-06 18:34:52', '2025-04-06 18:40:52', NULL),
(14, '', '2025-04-07', 2, 1, 6, '2025-04-06 18:49:08', '2025-04-06 18:49:08', NULL),
(15, '', '2025-04-09', 2, 1, 6, '2025-04-06 18:49:56', '2025-04-06 18:49:56', '2025-04-06 18:49:59'),
(16, '', '2025-04-07', 2, 1, 7, '2025-04-06 18:50:08', NULL, NULL),
(17, '', '2025-04-09', 2, 1, 7, '2025-04-06 18:57:02', NULL, '2025-04-06 18:57:26'),
(18, '', '2025-04-14', 2, 1, 5, '2025-04-14 19:08:53', '2025-04-14 19:08:53', '2025-04-14 19:09:10'),
(19, '', '2025-04-28', 2, 1, 7, '2025-04-14 23:11:27', NULL, '2025-04-14 23:11:33'),
(20, '', '2025-04-16', 2, 5, 7, '2025-04-15 18:58:53', '2025-04-15 18:58:53', '2025-04-15 18:59:46'),
(21, '', '2025-04-21', 2, 1, 7, '2025-04-15 19:04:21', '2025-04-15 19:10:28', NULL),
(22, '', '2025-04-21', 2, 5, 7, '2025-04-15 19:06:52', '2025-04-15 19:06:52', '2025-04-15 19:10:33'),
(23, '', '2025-04-21', 2, 5, 7, '2025-04-15 19:17:28', '2025-04-15 19:17:28', '2025-04-15 19:17:39'),
(24, '', '2025-04-21', 2, 5, 7, '2025-04-15 19:17:50', '2025-04-15 19:17:50', NULL),
(25, '', '2025-04-23', 2, 1, 7, '2025-04-15 19:20:25', NULL, NULL),
(26, '', '2025-04-23', 5, 5, 7, '2025-04-15 19:24:49', '2025-04-15 19:24:49', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `richiesta_conferma`
--

CREATE TABLE `richiesta_conferma` (
  `id_richiesta_conferma` int(11) NOT NULL,
  `fk_aula` int(11) NOT NULL,
  `fk_fascia_oraria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `richiesta_conferma`
--

INSERT INTO `richiesta_conferma` (`id_richiesta_conferma`, `fk_aula`, `fk_fascia_oraria`) VALUES
(1, 7, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_orario`
--

CREATE TABLE `tipo_orario` (
  `id_tipo_orario` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tipo_orario`
--

INSERT INTO `tipo_orario` (`id_tipo_orario`, `nome`) VALUES
(2, 'Ore da 50\''),
(1, 'Ore da 60\''),
(3, 'Sabato');

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo_orario_fascia_oraria`
--

CREATE TABLE `tipo_orario_fascia_oraria` (
  `id_tipo_orario_fascia_oraria` int(11) NOT NULL,
  `fk_tipo_orario` int(11) NOT NULL,
  `fk_fascia_oraria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tipo_orario_fascia_oraria`
--

INSERT INTO `tipo_orario_fascia_oraria` (`id_tipo_orario_fascia_oraria`, `fk_tipo_orario`, `fk_fascia_oraria`) VALUES
(1, 1, 1),
(2, 1, 5),
(3, 1, 6),
(4, 1, 7),
(5, 1, 8),
(6, 2, 9),
(7, 2, 10),
(8, 2, 11),
(9, 2, 12),
(10, 2, 13),
(11, 2, 14),
(12, 3, 1),
(13, 3, 5),
(15, 3, 7),
(14, 3, 15);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id_utente` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `cognome` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `ruolo` varchar(1) NOT NULL,
  `pwd_c` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id_utente`, `nome`, `cognome`, `email`, `password`, `ruolo`, `pwd_c`) VALUES
(1, 'admin', 'admin', 'admin@iiseuganeo.timetables', '21232f297a57a5a743894a0e4a801fc3', 'A', 'admin'),
(2, 'nome_docente', 'cognome_docente', 'docente@iiseuganeo.timetables', 'ac99fecf6fcb8c25d18788d14a5384ee', 'D', 'docente'),
(3, 'test', 'test', 'test@test', '81dc9bdb52d04dc20036dbd8313ed055', 'D', '1234'),
(4, 'test1', 'test1', 'test1@test', '81dc9bdb52d04dc20036dbd8313ed055', 'D', '1234'),
(5, 'test2', 'test2', 'test2@test', '81dc9bdb52d04dc20036dbd8313ed055', 'D', '1234');

-- --------------------------------------------------------

--
-- Struttura per vista `fascia_oraria_giorno`
--
DROP TABLE IF EXISTS `fascia_oraria_giorno`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fascia_oraria_giorno`  AS SELECT `g`.`id_giorno` AS `giorno`, `thfh`.`fk_fascia_oraria` AS `fascia_oraria` FROM ((`giorno` `g` join `tipo_orario` `th` on(`g`.`fk_tipo_orario` = `th`.`id_tipo_orario`)) join `tipo_orario_fascia_oraria` `thfh` on(`th`.`id_tipo_orario` = `thfh`.`fk_tipo_orario`)) ORDER BY `g`.`id_giorno` ASC ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `aula`
--
ALTER TABLE `aula`
  ADD PRIMARY KEY (`id_aula`),
  ADD UNIQUE KEY `piano` (`piano`,`n_aula`,`fk_plesso`),
  ADD KEY `fk_plesso` (`fk_plesso`);

--
-- Indici per le tabelle `fascia_oraria`
--
ALTER TABLE `fascia_oraria`
  ADD PRIMARY KEY (`id_fascia_oraria`),
  ADD UNIQUE KEY `ora_inizio` (`ora_inizio`,`ora_fine`);

--
-- Indici per le tabelle `giorno`
--
ALTER TABLE `giorno`
  ADD PRIMARY KEY (`id_giorno`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `fk_tipo_orario` (`fk_tipo_orario`);

--
-- Indici per le tabelle `plesso`
--
ALTER TABLE `plesso`
  ADD PRIMARY KEY (`id_plesso`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `prenotazione`
--
ALTER TABLE `prenotazione`
  ADD PRIMARY KEY (`id_prenotazione`),
  ADD KEY `fk_utente` (`fk_utente`),
  ADD KEY `fk_aula` (`fk_aula`),
  ADD KEY `fk_fascia_oraria` (`fk_fascia_oraria`);

--
-- Indici per le tabelle `richiesta_conferma`
--
ALTER TABLE `richiesta_conferma`
  ADD PRIMARY KEY (`id_richiesta_conferma`),
  ADD UNIQUE KEY `fk_aula` (`fk_aula`,`fk_fascia_oraria`),
  ADD KEY `fk_fascia_oraria` (`fk_fascia_oraria`);

--
-- Indici per le tabelle `tipo_orario`
--
ALTER TABLE `tipo_orario`
  ADD PRIMARY KEY (`id_tipo_orario`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `tipo_orario_fascia_oraria`
--
ALTER TABLE `tipo_orario_fascia_oraria`
  ADD PRIMARY KEY (`id_tipo_orario_fascia_oraria`),
  ADD UNIQUE KEY `fk_tipo_orario` (`fk_tipo_orario`,`fk_fascia_oraria`),
  ADD KEY `fk_fascia_oraria` (`fk_fascia_oraria`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `aula`
--
ALTER TABLE `aula`
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `fascia_oraria`
--
ALTER TABLE `fascia_oraria`
  MODIFY `id_fascia_oraria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `giorno`
--
ALTER TABLE `giorno`
  MODIFY `id_giorno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `plesso`
--
ALTER TABLE `plesso`
  MODIFY `id_plesso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `prenotazione`
--
ALTER TABLE `prenotazione`
  MODIFY `id_prenotazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT per la tabella `richiesta_conferma`
--
ALTER TABLE `richiesta_conferma`
  MODIFY `id_richiesta_conferma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `tipo_orario`
--
ALTER TABLE `tipo_orario`
  MODIFY `id_tipo_orario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `tipo_orario_fascia_oraria`
--
ALTER TABLE `tipo_orario_fascia_oraria`
  MODIFY `id_tipo_orario_fascia_oraria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `aula`
--
ALTER TABLE `aula`
  ADD CONSTRAINT `aula_ibfk_1` FOREIGN KEY (`fk_plesso`) REFERENCES `plesso` (`id_plesso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `giorno`
--
ALTER TABLE `giorno`
  ADD CONSTRAINT `giorno_ibfk_1` FOREIGN KEY (`fk_tipo_orario`) REFERENCES `tipo_orario` (`id_tipo_orario`);

--
-- Limiti per la tabella `prenotazione`
--
ALTER TABLE `prenotazione`
  ADD CONSTRAINT `prenotazione_ibfk_1` FOREIGN KEY (`fk_utente`) REFERENCES `utente` (`id_utente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazione_ibfk_2` FOREIGN KEY (`fk_aula`) REFERENCES `aula` (`id_aula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prenotazione_ibfk_3` FOREIGN KEY (`fk_fascia_oraria`) REFERENCES `fascia_oraria` (`id_fascia_oraria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `richiesta_conferma`
--
ALTER TABLE `richiesta_conferma`
  ADD CONSTRAINT `richiesta_conferma_ibfk_1` FOREIGN KEY (`fk_aula`) REFERENCES `aula` (`id_aula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `richiesta_conferma_ibfk_2` FOREIGN KEY (`fk_fascia_oraria`) REFERENCES `fascia_oraria` (`id_fascia_oraria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `tipo_orario_fascia_oraria`
--
ALTER TABLE `tipo_orario_fascia_oraria`
  ADD CONSTRAINT `tipo_orario_fascia_oraria_ibfk_1` FOREIGN KEY (`fk_tipo_orario`) REFERENCES `tipo_orario` (`id_tipo_orario`),
  ADD CONSTRAINT `tipo_orario_fascia_oraria_ibfk_2` FOREIGN KEY (`fk_fascia_oraria`) REFERENCES `fascia_oraria` (`id_fascia_oraria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
