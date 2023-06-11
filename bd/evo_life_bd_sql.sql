-- MySQL Script generated by MySQL Workbench
-- Wed May 31 22:06:51 2023
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema life_evo
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema life_evo
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `life_evo` DEFAULT CHARACTER SET utf8 ;
USE `life_evo` ;

-- -----------------------------------------------------
-- Table `life_evo`.`avatars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`avatars` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `path` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)   ,
  UNIQUE INDEX `path_UNIQUE` (`path` ASC)   )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`profiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`profiles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `pwd_hash` VARCHAR(256) NOT NULL,
  `avatar_id` INT NOT NULL DEFAULT 1,
  `profiles_id` INT NOT NULL DEFAULT 2,
  `date` DATETIME NOT NULL DEFAULT now(),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)   ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC)   ,
  INDEX `fk_users_avatars_idx` (`avatar_id` ASC)   ,
  INDEX `fk_users_profiles1_idx` (`profiles_id` ASC)   ,
  CONSTRAINT `fk_users_avatars`
    FOREIGN KEY (`avatar_id`)
    REFERENCES `life_evo`.`avatars` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_profiles1`
    FOREIGN KEY (`profiles_id`)
    REFERENCES `life_evo`.`profiles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `symbol` VARCHAR(45) NULL,
  `goal` INT NOT NULL,
  `qnt_elements_default` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)   )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`microorganism_settings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`microorganism_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `max_usage` INT NOT NULL,
  `break_duration` INT NOT NULL,
  `perc_progress` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`planets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`planets` (
  `user_id` INT NOT NULL,
  `id_settings` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `progress` FLOAT NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`),
  INDEX `fk_planets_users1_idx` (`user_id` ASC)   ,
  UNIQUE INDEX `users_id_UNIQUE` (`user_id` ASC)   ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)   ,
  INDEX `fk_planets_microorganism_settings1_idx` (`id_settings` ASC)   ,
  CONSTRAINT `fk_planets_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `life_evo`.`users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_microorganism_settings1`
    FOREIGN KEY (`id_settings`)
    REFERENCES `life_evo`.`microorganism_settings` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`planets_items_inventory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`planets_items_inventory` (
  `planets_user_id` INT NOT NULL,
  `item_id` INT NOT NULL,
  `qty` INT NOT NULL,
  PRIMARY KEY (`planets_user_id`, `item_id`),
  INDEX `fk_planets_has_items_items1_idx` (`item_id` ASC)   ,
  INDEX `fk_planets_items_inventory_planets1_idx` (`planets_user_id` ASC)   ,
  CONSTRAINT `fk_planets_has_items_items1`
    FOREIGN KEY (`item_id`)
    REFERENCES `life_evo`.`items` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_items_inventory_planets1`
    FOREIGN KEY (`planets_user_id`)
    REFERENCES `life_evo`.`planets` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`formula_location`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`formula_location` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC)   ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC)   )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`market_offers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`market_offers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `my_item_id` INT NOT NULL,
  `my_item_qty` INT NOT NULL,
  `other_item_id` INT NOT NULL,
  `other_item_qty` INT NOT NULL,
  `completed` TINYINT(1) NOT NULL DEFAULT 0,
  `planets_user_id` INT NOT NULL,
  `date` DATE NOT NULL DEFAULT now(),
  PRIMARY KEY (`id`),
  INDEX `fk_planets_has_items_items2_idx` (`my_item_id` ASC)   ,
  INDEX `fk_planets_items_market_items1_idx` (`other_item_id` ASC)   ,
  INDEX `fk_market_offers_planets1_idx` (`planets_user_id` ASC)   ,
  CONSTRAINT `fk_planets_has_items_items2`
    FOREIGN KEY (`my_item_id`)
    REFERENCES `life_evo`.`items` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_items_market_items1`
    FOREIGN KEY (`other_item_id`)
    REFERENCES `life_evo`.`items` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_market_offers_planets1`
    FOREIGN KEY (`planets_user_id`)
    REFERENCES `life_evo`.`planets` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`formulas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`formulas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `formula_location_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  INDEX `fk_formula_itens_formula_location1_idx` (`formula_location_id` ASC)   ,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_formula_itens_formula_location1`
    FOREIGN KEY (`formula_location_id`)
    REFERENCES `life_evo`.`formula_location` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`formula_itens`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`formula_itens` (
  `formula_id` INT NOT NULL,
  `items_id` INT NOT NULL,
  `qty` INT NOT NULL DEFAULT 1,
  `side` TINYINT(1) NOT NULL,
  PRIMARY KEY (`formula_id`, `items_id`),
  INDEX `fk_items_has_formula_itens_formula_itens1_idx` (`formula_id` ASC)   ,
  INDEX `fk_items_has_formula_itens_items1_idx` (`items_id` ASC)   ,
  CONSTRAINT `fk_items_has_formula_itens_items1`
    FOREIGN KEY (`items_id`)
    REFERENCES `life_evo`.`items` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_items_has_formula_itens_formula_itens1`
    FOREIGN KEY (`formula_id`)
    REFERENCES `life_evo`.`formulas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`land`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`land` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`planets_land_items`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`planets_land_items` (
  `item_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `land_id` INT NOT NULL,
  `qt` INT NOT NULL,
  PRIMARY KEY (`item_id`, `user_id`, `land_id`),
  INDEX `fk_planets_items_inventory_has_land_land1_idx` (`land_id` ASC)   ,
  INDEX `fk_planets_items_inventory_has_land_planets_items_inventory_idx` (`item_id` ASC, `user_id` ASC)   ,
  CONSTRAINT `fk_planets_items_inventory_has_land_planets_items_inventory1`
    FOREIGN KEY (`item_id` , `user_id`)
    REFERENCES `life_evo`.`planets_items_inventory` (`item_id` , `planets_user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_items_inventory_has_land_land1`
    FOREIGN KEY (`land_id`)
    REFERENCES `life_evo`.`land` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`microorganism_usage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`microorganism_usage` (
  `break_start` DATETIME NOT NULL,
  `item_usage` INT NOT NULL,
  `planets_land_items_item_id` INT NOT NULL,
  `planets_land_items_user_id` INT NOT NULL,
  `planets_land_items_land_id` INT NOT NULL,
  INDEX `fk_microorganism_usage_planets_land_items1_idx` (`planets_land_items_item_id` ASC, `planets_land_items_user_id` ASC, `planets_land_items_land_id` ASC)   ,
  PRIMARY KEY (`planets_land_items_item_id`, `planets_land_items_user_id`, `planets_land_items_land_id`),
  CONSTRAINT `fk_microorganism_usage_planets_land_items1`
    FOREIGN KEY (`planets_land_items_item_id` , `planets_land_items_user_id` , `planets_land_items_land_id`)
    REFERENCES `life_evo`.`planets_land_items` (`item_id` , `user_id` , `land_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `life_evo`.`used_formulas_planet`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `life_evo`.`used_formulas_planet` (
  `planets_user_id` INT NOT NULL,
  `formula_id` INT NOT NULL,
  `date` DATETIME NOT NULL DEFAULT now(),
  `direction` TINYINT(1) NOT NULL,
  PRIMARY KEY (`planets_user_id`, `formula_id`),
  INDEX `fk_planets_has_formulas_formulas1_idx` (`formula_id` ASC)   ,
  INDEX `fk_planets_has_formulas_planets1_idx` (`planets_user_id` ASC)   ,
  CONSTRAINT `fk_planets_has_formulas_planets1`
    FOREIGN KEY (`planets_user_id`)
    REFERENCES `life_evo`.`planets` (`user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_planets_has_formulas_formulas1`
    FOREIGN KEY (`formula_id`)
    REFERENCES `life_evo`.`formulas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
