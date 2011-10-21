SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `roommates` ;
CREATE SCHEMA IF NOT EXISTS `roommates` DEFAULT CHARACTER SET utf8 ;
USE `roommates` ;

-- -----------------------------------------------------
-- Table `roommates`.`floors`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`floors` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`floors` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`house_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`house_types` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`house_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`heating_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`heating_types` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`heating_types` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`municipalities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`municipalities` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`municipalities` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`users` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(50) NOT NULL ,
  `password` VARCHAR(40) NOT NULL ,
  `role` VARCHAR(40) NULL DEFAULT NULL ,
  `banned` TINYINT(1) NOT NULL DEFAULT 0 ,
  `terms_accepted` TINYINT(1) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`houses`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`houses` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`houses` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `address` VARCHAR(50) NOT NULL ,
  `postal_code` VARCHAR(5) NULL DEFAULT NULL ,
  `area` SMALLINT NOT NULL ,
  `bedroom_num` TINYINT NOT NULL ,
  `bathroom_num` TINYINT NULL DEFAULT NULL ,
  `price` SMALLINT NOT NULL ,
  `construction_year` INT NULL DEFAULT NULL ,
  `solar_heater` TINYINT(1) NULL DEFAULT NULL ,
  `furnitured` TINYINT(1) NULL DEFAULT NULL ,
  `aircondition` TINYINT(1) NULL DEFAULT NULL ,
  `garden` TINYINT(1) NULL DEFAULT NULL ,
  `parking` TINYINT(1) NULL DEFAULT NULL ,
  `shared_pay` TINYINT(1) NULL DEFAULT NULL ,
  `security_doors` TINYINT(1) NULL DEFAULT NULL ,
  `disability_facilities` TINYINT(1) NULL DEFAULT NULL ,
  `storeroom` TINYINT(1) NULL DEFAULT NULL ,
  `availability_date` DATE NOT NULL COMMENT '	' ,
  `rent_period` TINYINT NULL DEFAULT NULL COMMENT '	' ,
  `description` VARCHAR(256) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  `floor_id` INT NOT NULL ,
  `house_type_id` INT NOT NULL ,
  `heating_type_id` INT NOT NULL ,
  `currently_hosting` INT NOT NULL ,
  `total_places` INT NOT NULL ,
  `user_id` INT NULL DEFAULT NULL ,
  `municipality_id` INT NULL DEFAULT NULL ,
  `default_image_id` INT NULL DEFAULT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `fk_house_floor` (`floor_id` ASC) ,
  INDEX `fk_house_house_type1` (`house_type_id` ASC) ,
  INDEX `fk_house_heating1` (`heating_type_id` ASC) ,
  INDEX `fk_house_user` (`user_id` ASC) ,
  INDEX `fk_house_municipality` (`municipality_id` ASC) ,
  INDEX `fk_default_image` (`default_image_id` ASC),
  CONSTRAINT `fk_house_floor`
    FOREIGN KEY (`floor_id` )
    REFERENCES `roommates`.`floors` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_house_house_type1`
    FOREIGN KEY (`house_type_id` )
    REFERENCES `roommates`.`house_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_house_heating1`
    FOREIGN KEY (`heating_type_id` )
    REFERENCES `roommates`.`heating_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_house_user`
    FOREIGN KEY (`user_id` )
    REFERENCES `roommates`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_house_minicipality`
    FOREIGN KEY (`municipality_id` )
    REFERENCES `roommates`.`municipalities` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_default_image`
    FOREIGN KEY (`default_image_id`)
    REFERENCES `roommates`.`images` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`preferences`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`preferences` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`preferences` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `age_min` TINYINT(4) NULL DEFAULT NULL ,
  `age_max` TINYINT(4) NULL DEFAULT NULL ,
  `pref_gender` TINYINT(2) NULL DEFAULT NULL ,
  `pref_smoker` TINYINT(2) NULL DEFAULT NULL ,
  `pref_pet` TINYINT(2) NULL DEFAULT NULL ,
  `pref_child` TINYINT(2) NULL DEFAULT NULL ,
  `pref_couple` TINYINT(2) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`profiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`profiles` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`profiles` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `firstname` VARCHAR(45) NOT NULL ,
  `lastname` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `dob` SMALLINT NOT NULL COMMENT 'date of birth' ,
  `gender` TINYINT(1) NOT NULL COMMENT '0->male, 1->female' ,
  `phone` VARCHAR(45) NULL DEFAULT NULL ,
  `smoker` TINYINT(1) NULL DEFAULT NULL ,
  `pet` TINYINT(1) NULL DEFAULT NULL ,
  `child` TINYINT(1) NULL DEFAULT NULL ,
  `couple` TINYINT(1) NULL DEFAULT NULL ,
  `we_are` INT(11) NULL DEFAULT NULL ,
  `max_roommates` TINYINT NULL DEFAULT NULL ,
  `visible` TINYINT(1) NULL DEFAULT NULL ,
  `token` VARCHAR(40) NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  `preference_id` INT NULL DEFAULT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_preference_id` (`preference_id` ASC) ,
  INDEX `fk_user_id` (`user_id` ASC) ,
  CONSTRAINT `fk_profile_preference`
    FOREIGN KEY (`preference_id` )
    REFERENCES `roommates`.`preferences` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `roommates`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`images`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`images` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`images` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `location` VARCHAR(100) NOT NULL ,
  `house_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_image_house` (`house_id` ASC) ,
  CONSTRAINT `fk_image_house`
    FOREIGN KEY (`house_id` )
    REFERENCES `roommates`.`houses` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
