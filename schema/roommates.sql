SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `roommates` ;
CREATE SCHEMA IF NOT EXISTS `roommates` DEFAULT CHARACTER SET utf8;
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
  `type` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `roommates`.`municipalities`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`municipalities`;

CREATE TABLE IF NOT EXISTS `roommates`.`municipalities` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL,
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
  `solar_heater` TINYINT(1)  NULL DEFAULT NULL ,
  `furnitured` TINYINT(1)  NULL DEFAULT NULL ,
  `aircondition` TINYINT(1)  NULL DEFAULT NULL ,
  `garden` TINYINT(1)  NULL DEFAULT NULL ,
  `parking` TINYINT(1)  NULL DEFAULT NULL ,
  `shared_pay` TINYINT(1)  NULL DEFAULT NULL ,
  `security_doors` TINYINT(1)  NULL DEFAULT NULL ,
  `disability_facilities` TINYINT(1)  NULL DEFAULT NULL ,
  `storeroom` TINYINT(1)  NULL DEFAULT NULL ,
  `availability_date` DATE NOT NULL COMMENT '	' ,
  `rent_period` TINYINT NULL DEFAULT NULL COMMENT '	' ,
  `description` VARCHAR(256) NULL DEFAULT NULL ,
  `created` DATETIME DEFAULT NULL ,
  `modified` DATETIME DEFAULT NULL ,
  `floor_id` INT NOT NULL ,
  `house_type_id` INT NOT NULL ,
  `heating_type_id` INT NOT NULL ,
  `currently_hosting` INT NOT NULL,
  `currently_available` INT NOT NULL,
  `profile_id` INT DEFAULT NULL ,
  `municipality_id` INT DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_house_floor` (`floor_id` ASC) ,
  INDEX `fk_house_house_type1` (`house_type_id` ASC) ,
  INDEX `fk_house_heating1` (`heating_type_id` ASC) ,
  INDEX `fk_house_profile` (`profile_id` ASC) ,
  INDEX `fk_house_municipality` (`municipality_id` ASC) ,
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
  CONSTRAINT `fk_house_profile`
    FOREIGN KEY (`profile_id`)
    REFERENCES `roommates`.`profiles` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_house_minicipality`
    FOREIGN KEY (`municipality_id`)
    REFERENCES `roommates`.`municipalities` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

DROP TABLE IF EXISTS `roommates`.`users` ;
-- -----------------------------------------------------
-- Table `roommates`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `roommates`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` CHAR(50) NOT NULL ,
  `password` CHAR(40) NOT NULL ,
  `profiles_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_users_profiles1` (`profiles_id` ASC) ,
  CONSTRAINT `fk_users_profiles1`
    FOREIGN KEY (`profiles_id` )
    REFERENCES `roommates`.`profiles` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `roommates`.`preferences`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`preferences` ;

CREATE TABLE IF NOT EXISTS `preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `age_min` tinyint(4) DEFAULT NULL,
  `age_max` tinyint(4) DEFAULT NULL,
  `mates_min` tinyint(4) DEFAULT NULL,
  `pref_gender` TINYINT(2) DEFAULT NULL,
  `pref_smoker` TINYINT(2)  NULL ,
  `pref_pet` TINYINT(2)  NULL ,
  `pref_child` TINYINT(2)  NULL ,
  `pref_couple` TINYINT(2)  NULL ,
  PRIMARY KEY (`id`))
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8;

-- -----------------------------------------------------
-- Table `roommates`.`profiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`profiles` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`profiles` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `firstname` VARCHAR(45) NOT NULL ,
  `lastname` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `dob` SMALLINT NOT NULL COMMENT 'date of birth',
  `gender` TINYINT(1) NOT NULL COMMENT '0->male, 1->female',
  `phone` VARCHAR(45) NULL ,
  `smoker` TINYINT(1)  NULL ,
  `pet` TINYINT(1)  NULL ,
  `child` TINYINT(1)  NULL ,
  `couple` TINYINT(1)  NULL ,
  `max_roommates` TINYINT NULL ,
  `visible` TINYINT(1)  NULL ,
  `created` DATETIME DEFAULT NULL ,
  `modified` DATETIME DEFAULT NULL ,
  `preference_id` INT DEFAULT NULL ,
  PRIMARY KEY (`id`),
  INDEX `fk_preference_id` (`preference_id` ASC),
  CONSTRAINT `fk_profile_preference`
    FOREIGN KEY (`preference_id`)
    REFERENCES `roommates`.`preferences`(`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- ----------------------------------------------------
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
