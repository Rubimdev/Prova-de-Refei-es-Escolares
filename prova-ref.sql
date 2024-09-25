-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 08-Jul-2024 às 21:18
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `prova-ref`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ementas`
--

CREATE TABLE `ementas` (
  `id` int(11) NOT NULL,
  `mes` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ementas`
--

INSERT INTO `ementas` (`id`, `mes`, `file_path`) VALUES
(66, '06-2024', 'uploads/Ementa_EBI_EB2.3_e_Sec_03_a_28_jun_2024 _1_.pdf');

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_utilizador` int(11) DEFAULT NULL,
  `nome_utilizador` varchar(50) DEFAULT NULL,
  `num_utilizador` varchar(10) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `escola` varchar(255) DEFAULT NULL,
  `hora` text DEFAULT NULL,
  `status` enum('pendente','confirmada','negada') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `reservas`
--

INSERT INTO `reservas` (`id`, `id_utilizador`, `nome_utilizador`, `num_utilizador`, `data`, `escola`, `hora`, `status`) VALUES
(142, NULL, 'maria', '123', '17/09/2024', 'EB/ES Padre Alberto Neto', '12:30h às 13:00h', 'negada'),
(145, NULL, 'sandra', '6778', '20/09/2024', 'EB/ES Padre Alberto Neto', '12:00h às 12:30h', 'pendente'),
(146, NULL, 'sandra', '878999', '17/09/2024', 'EB/ES Padre Alberto Neto', '12:00h às 12:30h', 'pendente'),
(147, NULL, '77779', '788', '19/09/2024', 'EB/ES Padre Alberto Neto', '12:00h às 12:30h', 'pendente');

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

CREATE TABLE `utilizadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `num` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `tipo` enum('admin','utilizador') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `utilizadores`
--

INSERT INTO `utilizadores` (`id`, `nome`, `num`, `email`, `senha`, `tipo`) VALUES
(0, '', '', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(38, 'goncalo', '27660', 'provaderefeicoes1@gmail', '202cb962ac59075b964b07152d234b70', 'utilizador'),
(39, 'goncalo', '2600', 'provaderefeicoes1@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'utilizador');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `ementas`
--
ALTER TABLE `ementas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilizador` (`id_utilizador`),
  ADD KEY `nome_utilizador` (`nome_utilizador`),
  ADD KEY `num_utilizador` (`num_utilizador`);

--
-- Índices para tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_nome` (`nome`),
  ADD KEY `num` (`num`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `ementas`
--
ALTER TABLE `ementas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT de tabela `utilizadores`
--
ALTER TABLE `utilizadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_utilizador`) REFERENCES `utilizadores` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
