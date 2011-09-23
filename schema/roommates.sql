SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `roommates` ;
CREATE SCHEMA IF NOT EXISTS `roommates` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
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
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `roommates`.`house_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`house_type` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`house_type` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(25) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `roommates`.`heating`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`heating` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`heating` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


-- -----------------------------------------------------
-- Table `roommates`.`house`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `roommates`.`house` ;

CREATE  TABLE IF NOT EXISTS `roommates`.`house` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `address` VARCHAR(50) NOT NULL ,
  `postal_code` VARCHAR(5) NULL ,
  `area` DOUBLE NOT NULL ,
  `bedroom_num` INT NOT NULL ,
  `bathroom_num` INT NULL ,
  `price` DOUBLE NOT NULL ,
  `construction_year` DATE NULL ,
  `solar_heater` TINYINT(1)  NULL ,
  `furnitured` TINYINT(1)  NULL ,
  `aircondition` TINYINT(1)  NULL ,
  `garden` TINYINT(1)  NULL ,
  `parking` TINYINT(1)  NULL ,
  `shared_pay` TINYINT(1)  NULL ,
  `security_doors` TINYINT(1)  NULL ,
  `disability_facilities` TINYINT(1)  NULL ,
  `storeroom` TINYINT(1)  NULL ,
  `availability_date` DATE NOT NULL COMMENT '	' ,
  `rent_period` INT NULL COMMENT '	' ,
  `description` VARCHAR(256) NULL ,
  `floor_id` INT NOT NULL ,
  `floor_id1` INT NOT NULL ,
  `house_type_id` INT NOT NULL ,
  `heating_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_house_floor` (`floor_id1` ASC) ,
  INDEX `fk_house_house_type1` (`house_type_id` ASC) ,
  INDEX `fk_house_heating1` (`heating_id` ASC) ,
  CONSTRAINT `fk_house_floor`
    FOREIGN KEY (`floor_id1` )
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
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `roommates`.`floor`
-- -----------------------------------------------------
START TRANSACTION;
USE `roommates`;
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (1, 'υπόγειο');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (2, 'ημιυπόγειο');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (3, 'υπερυψωμένο');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (4, 'ισόγειο');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (5, 'ημιόροφος');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (6, '1');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (7, '2');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (8, '3');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (9, '4');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (10, '5');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (11, '6');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (12, '7');
INSERT INTO `roommates`.`floor` (`id`, `type`) VALUES (13, '8+');

COMMIT;

-- -----------------------------------------------------
-- Data for table `roommates`.`house_type`
-- -----------------------------------------------------
START TRANSACTION;
USE `roommates`;
INSERT INTO `roommates`.`house_type` (`id`, `name`) VALUES (1, 'studio');
INSERT INTO `roommates`.`house_type` (`id`, `name`) VALUES (2, 'γκαρσονιέρα');
INSERT INTO `roommates`.`house_type` (`id`, `name`) VALUES (3, 'διαμέρισμα');
INSERT INTO `roommates`.`house_type` (`id`, `name`) VALUES (4, 'μονοκατοικία');
INSERT INTO `roommates`.`house_type` (`id`, `name`) VALUES (5, 'μεζονέτα');

COMMIT;

-- -----------------------------------------------------
-- Data for table `roommates`.`heating`
-- -----------------------------------------------------
START TRANSACTION;
USE `roommates`;
INSERT INTO `roommates`.`heating` (`id`, `type`) VALUES (1, 'κεντρική');
INSERT INTO `roommates`.`heating` (`id`, `type`) VALUES (2, 'αυτόνομη');
INSERT INTO `roommates`.`heating` (`id`, `type`) VALUES (3, 'δεν διαθέτει');

COMMIT;
