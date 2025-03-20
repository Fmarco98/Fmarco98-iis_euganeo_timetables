-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 20, 2025 alle 18:56
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
  `nome` varchar(30) NOT NULL,
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
(1, '07:55:00', '08:55:00'),
(2, '07:50:00', '08:45:00');

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
(2, 1, 3),
(3, 2, 2),
(4, 2, 4),
(5, 2, 5);

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
(1, 'Luniedì'),
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
  `nome` varchar(30) NOT NULL,
  `n_piani` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `plesso`
--

INSERT INTO `plesso` (`id_plesso`, `nome`, `n_piani`) VALUES
(1, 'IIS Euganeo', 3),
(2, 'Fermi', 1),
(3, 'Duca', 1);

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
  ADD PRIMARY KEY (`id_fascia_oraria`);

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
  MODIFY `id_fascia_oraria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `fascia_oraria_giorno`
--
ALTER TABLE `fascia_oraria_giorno`
  MODIFY `id_fascia_oraria_giorno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id_prenotazione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `aula_ibfk_1` FOREIGN KEY (`fk_plesso`) REFERENCES `plesso` (`id_plesso`);

--
-- Limiti per la tabella `fascia_oraria_giorno`
--
ALTER TABLE `fascia_oraria_giorno`
  ADD CONSTRAINT `fascia_oraria_giorno_ibfk_1` FOREIGN KEY (`fk_giorno`) REFERENCES `giorno` (`id_giorno`),
  ADD CONSTRAINT `fascia_oraria_giorno_ibfk_2` FOREIGN KEY (`fk_fascia_oraria`) REFERENCES `fascia_oraria` (`id_fascia_oraria`);

--
-- Limiti per la tabella `prenotazione`
--
ALTER TABLE `prenotazione`
  ADD CONSTRAINT `prenotazione_ibfk_1` FOREIGN KEY (`fk_utente`) REFERENCES `utente` (`id_utente`),
  ADD CONSTRAINT `prenotazione_ibfk_2` FOREIGN KEY (`fk_aula`) REFERENCES `aula` (`id_aula`),
  ADD CONSTRAINT `prenotazione_ibfk_3` FOREIGN KEY (`fk_fascia_oraria`) REFERENCES `fascia_oraria` (`id_fascia_oraria`);

--
-- Limiti per la tabella `richiesta_conferma`
--
ALTER TABLE `richiesta_conferma`
  ADD CONSTRAINT `richiesta_conferma_ibfk_1` FOREIGN KEY (`fk_aula`) REFERENCES `aula` (`id_aula`),
  ADD CONSTRAINT `richiesta_conferma_ibfk_2` FOREIGN KEY (`fk_fascia_oraria`) REFERENCES `fascia_oraria` (`id_fascia_oraria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
