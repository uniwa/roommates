SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `roommates` ;
CREATE SCHEMA IF NOT EXISTS `roommates` DEFAULT CHARACTER SET utf8 ;
USE `roommates` ;

-- -----------------------------------------------------
-- Table `roommates`.`floor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`floor` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`floor` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`house_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`house_type` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`house_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`heating`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`heating` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`heating` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `roommates`.`house`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`house` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`house` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `address` VARCHAR(50) NOT NULL ,
  `postal_code` VARCHAR(5) NULL DEFAULT NULL ,
  `area` SMALLINT NOT NULL ,
  `bedroom_num` TINYINT NOT NULL ,
  `bathroom_num` TINYINT NULL DEFAULT NULL ,
  `price` SMALLINT NOT NULL ,
  `construction_year` DATE NULL DEFAULT NULL ,
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
  `floor_id` INT NOT NULL ,
  `house_type_id` INT NOT NULL ,
  `heating_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_house_floor` (`floor_id` ASC) ,
  INDEX `fk_house_house_type1` (`house_type_id` ASC) ,
  INDEX `fk_house_heating1` (`heating_id` ASC) ,
  CONSTRAINT `fk_house_floor`
    FOREIGN KEY (`floor_id` )
    REFERENCES `roommates`.`floor` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_house_house_type1`
    FOREIGN KEY (`house_type_id` )
    REFERENCES `roommates`.`house_type` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_house_heating1`
    FOREIGN KEY (`heating_id` )
    REFERENCES `roommates`.`heating` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
