-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 29, 2025 alle 16:08
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
  `nome` varchar(30),
  `piano` int(1) NOT NULL,
  `n_aula` int(2) NOT NULL,
  `fk_plesso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `aula`
--

INSERT INTO `aula` (`id_aula`, `nome`, `piano`, `n_aula`, `fk_plesso`) VALUES
(1, 'A-test1', 0, 1, 1),
(2, 'A-test2', 1, 1, 2);

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
-- Struttura della tabella `fascia_oraria_giorno`
--

CREATE TABLE `fascia_oraria_giorno` (
  `id_fascia_oraria_giorno` int(11) NOT NULL,
  `fk_fascia_oraria` int(11) NOT NULL,
  `fk_giorno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `fascia_oraria_giorno`
--

INSERT INTO `fascia_oraria_giorno` (`id_fascia_oraria_giorno`, `fk_fascia_oraria`, `fk_giorno`) VALUES
(1, 1, 1),
(18, 1, 3),
(35, 1, 6),
(8, 5, 1),
(19, 5, 3),
(36, 5, 6),
(9, 6, 1),
(20, 6, 3),
(10, 7, 1),
(21, 7, 3),
(38, 7, 6),
(11, 8, 1),
(22, 8, 3),
(12, 9, 2),
(23, 9, 4),
(29, 9, 5),
(13, 10, 2),
(24, 10, 4),
(30, 10, 5),
(14, 11, 2),
(25, 11, 4),
(31, 11, 5),
(15, 12, 2),
(26, 12, 4),
(32, 12, 5),
(16, 13, 2),
(27, 13, 4),
(33, 13, 5),
(17, 14, 2),
(28, 14, 4),
(34, 14, 5),
(37, 15, 6);

-- --------------------------------------------------------

--
-- Struttura della tabella `giorno`
--

CREATE TABLE `giorno` (
  `id_giorno` int(11) NOT NULL,
  `nome` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `giorno`
--

INSERT INTO `giorno` (`id_giorno`, `nome`) VALUES
(4, 'Giovedì'),
(1, 'Lunedì'),
(2, 'Martedì'),
(3, 'Mercoledì'),
(6, 'Sabato'),
(5, 'Venerdì');

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
(1, 'IIS Euganeo'),
(2, 'Fermi'),
(3, 'Duca');

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazione`
--

CREATE TABLE `prenotazione` (
  `id_prenotazione` int(11) NOT NULL,
  `descrizione` varchar(500) DEFAULT NULL,
  `conferma` tinyint(1) NOT NULL,
  `data` date NOT NULL,
  `fk_utente` int(11) NOT NULL,
  `fk_fascia_oraria` int(11) NOT NULL,
  `fk_aula` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prenotazione`
--

INSERT INTO `prenotazione` (`id_prenotazione`, `descrizione`, `conferma`, `data`, `fk_utente`, `fk_fascia_oraria`, `fk_aula`) VALUES
(3, NULL, 0, '2025-03-13', 3, 5, 1),
(4, NULL, 1, '2025-03-11', 2, 1, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `richiesta_conferma`
--

CREATE TABLE `richiesta_conferma` (
  `id_richiesta_conferma` int(11) NOT NULL,
  `fk_aula` int(11) NOT NULL,
  `fk_fascia_oraria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indici per le tabelle `fascia_oraria_giorno`
--
ALTER TABLE `fascia_oraria_giorno`
  ADD PRIMARY KEY (`id_fascia_oraria_giorno`),
  ADD UNIQUE KEY `fk_fascia_oraria` (`fk_fascia_oraria`,`fk_giorno`),
  ADD KEY `fk_giorno` (`fk_giorno`);

--
-- Indici per le tabelle `giorno`
--
ALTER TABLE `giorno`
  ADD PRIMARY KEY (`id_giorno`),
  ADD UNIQUE KEY `nome` (`nome`);

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
  ADD UNIQUE KEY `data` (`data`,`fk_fascia_oraria`,`fk_aula`),
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
  MODIFY `id_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `fascia_oraria`
--
ALTER TABLE `fascia_oraria`
  MODIFY `id_fascia_oraria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT per la tabella `fascia_oraria_giorno`
--
ALTER TABLE `fascia_oraria_giorno`
  MODIFY `id_fascia_oraria_giorno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  MODIFY `id_prenotazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `richiesta_conferma`
--
ALTER TABLE `richiesta_conferma`
  MODIFY `id_richiesta_conferma` int(11) NOT NULL AUTO_INCREMENT;

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
-- Limiti per la tabella `fascia_oraria_giorno`
--
ALTER TABLE `fascia_oraria_giorno`
  ADD CONSTRAINT `fascia_oraria_giorno_ibfk_1` FOREIGN KEY (`fk_giorno`) REFERENCES `giorno` (`id_giorno`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fascia_oraria_giorno_ibfk_2` FOREIGN KEY (`fk_fascia_oraria`) REFERENCES `fascia_oraria` (`id_fascia_oraria`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
