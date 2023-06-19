-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28-Maio-2023 às 23:49
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `life_evo`
--
--
-- Extraindo dados da tabela `microorganism_settings`
--

INSERT INTO `microorganism_settings` (`id`, `max_usage`, `break_duration`, `perc_progress`) VALUES
(1, 5, 10, 0),
(2, 3, 10, 50),
(3, 3, 20, 80);

--
-- Extraindo dados da tabela `formula_location`
--

INSERT INTO `formula_location` (`id`, `name`) VALUES
(1, 'Laboratório'),
(2, 'Microorganismo'),
(3, 'Semente');

-- Extraindo dados da tabela `avatars`
--

INSERT INTO `avatars` (`id`, `path`) VALUES
(1, 'avatar1.png');

--
-- Extraindo dados da tabela `profiles`
--

INSERT INTO `profiles` (`id`, `type`) VALUES
(1, 'administrador'),
(2, 'jogador');

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `username`, `pwd_hash`, `avatar_id`, `profiles_id`, `date`) VALUES
(1, 'user1', 'qwertyuioplkjhgfdsazxcvbnm', 1, 2, '2023-05-29 12:52:26'),
(2, 'user2', 'mnbvczasdfghjkllllpoiuytrewq', 1, 2, '2023-05-29 12:52:58');

--
-- Extraindo dados da tabela `planets`
--

INSERT INTO `planets` (`user_id`, `id_settings`, `name`, `progress`) VALUES
(1, 1, 'planeta_1', 0),
(2, 1, 'planeta_2', 0);

--
-- Extraindo dados da tabela `items`
--

INSERT INTO `items` (`id`, `name`, `symbol`, `goal`, `qnt_elements_default`) VALUES
(1, 'Hidrogénio', 'H', 5, 50),
(2, 'Oxigénio', 'O', 210, 0),
(3, 'Água', 'H2O', 20, 500),
(4, 'Azoto', 'N2', 780, 300),
(5, 'Dióxido de Carbono', 'CO2', 10, 400),
(6, 'Metano', 'CH4', 5, 50),
(7, 'Amónio', 'NH3', 5, 50),
(8, 'Nitrogénio', 'N', 10, 980),
(9, 'Ozono', 'O3', 100, 0),
(10, 'Carbono', 'C', 5, 300),
(11, 'Microorganismo', NULL, NULL, 1),
(12, 'Semente', NULL, NULL, 0);

--
-- Extraindo dados da tabela `land`
--

INSERT INTO `land` (`id`) VALUES
(1),
(2),
(3);
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15);

--
-- Extraindo dados da tabela `formulas`
--

INSERT INTO `formulas` (`id`, `formula_location_id`, `name`) VALUES
(1, 1, 'agua'),
(2, 2, 'reproducao'),
(3, 2, 'fotossintese');

--
-- Extraindo dados da tabela `formula_itens`
--

INSERT INTO `formula_itens` (`items_id`, `formula_id`, `qty`, `side`) VALUES
(1, 1, 2, 0),
(2, 1, 1, 0),
(3, 1, 1, 1);
INSERT INTO `formula_itens` (`formula_id`, `items_id`, `qty`, `side`) VALUES ('2', '10', '10', '0'), ('2', '11', '1', '1');
INSERT INTO `formula_itens` (`formula_id`, `items_id`, `qty`, `side`) VALUES ('3', '5', '1', '1'), ('3', '2', '1', '0'), ('3', '10', '1', '0');

--
-- Extraindo dados da tabela `market_offers`
--

INSERT INTO `market_offers` (`id`, `my_item_id`, `my_item_qty`, `other_item_id`, `other_item_qty`, `completed`, `planets_user_id`, `date`) VALUES
(1, 1, 1, 3, 1, 0, 1, '2023-05-29');

--
-- Extraindo dados da tabela `used_formulas_planet`
--

INSERT INTO `used_formulas_planet` (`planets_user_id`, `formula_id`, `date`, `direction`) VALUES
(1, 1, '2023-05-29 12:58:45', 1);

--
-- Extraindo dados da tabela `planets_items_inventory`
--

INSERT INTO `planets_items_inventory` (`item_id`, `qty`, `planets_user_id`) VALUES
(1, 50, 1),
(3, 500, 2);

--
-- Extraindo dados da tabela `planets_land_items`
--

INSERT INTO `planets_land_items` (`item_id`, `user_id`, `land_id`, `qt`) VALUES
(3, 2, 1, 1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
