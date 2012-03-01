SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `gallerycms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `gallerycms` ;

-- -----------------------------------------------------
-- Table `gallerycms`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gallerycms`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `email_address` VARCHAR(45) NULL ,
  `password` VARCHAR(45) NULL ,
  `is_active` TINYINT NULL ,
  `role_id` INT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL COMMENT '	' ,
  `last_logged_in` DATETIME NULL COMMENT '		' ,
  `last_ip` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `email_address` (`email_address` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gallerycms`.`image`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gallerycms`.`image` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `category_id` INT NULL ,
  `uuid` VARCHAR(45) NULL ,
  `name` VARCHAR(45) NULL ,
  `order_num` INT NULL ,
  `caption` VARCHAR(45) NULL ,
  `file_type` VARCHAR(45) NULL ,
  `file_name` VARCHAR(45) NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  `created_by` INT NULL ,
  `updated_by` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `category_id` (`category_id` ASC) ,
  INDEX `uuid` (`uuid` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gallerycms`.`category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gallerycms`.`category` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `album_id` INT NULL ,
  `order_num` INT NULL ,
  `created_at` DATETIME NULL ,
  `updated_at` DATETIME NULL ,
  `created_by` INT NULL ,
  `updated_by` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `album_id` (`album_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gallerycms`.`album`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gallerycms`.`album` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  `category_id` INT NULL ,
  `created_by` INT NULL ,
  `updated_by` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `category_id` (`category_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gallerycms`.`config`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gallerycms`.`config` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `album_id` INT NULL ,
  `thumb_width` INT NULL ,
  `thumb_height` INT NULL ,
  `crop_thumbails` TINYINT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `id` (`id` ASC) ,
  INDEX `album_id` (`album_id` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gallerycms`.`role`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gallerycms`.`role` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gallerycms`.`ticket`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gallerycms`.`ticket` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `user_id` INT NULL ,
  `uuid` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
