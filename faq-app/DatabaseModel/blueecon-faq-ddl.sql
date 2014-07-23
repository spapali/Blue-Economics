SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema blueecon_faq
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `blueecon_faq` DEFAULT CHARACTER SET utf8 ;
-- -----------------------------------------------------
-- Schema blueeconomics
-- -----------------------------------------------------
-- This schema was created for a stub table
USE `blueecon_faq` ;

-- -----------------------------------------------------
-- Table `blueecon_faq`.`questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blueecon_faq`.`questions` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `submitter` VARCHAR(45) NOT NULL,
  `question` VARCHAR(255) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `soc_code` VARCHAR(7) NULL,
  PRIMARY KEY (`id`),
  INDEX `jobs_id_fk_idx` (`soc_code` ASC),
  CONSTRAINT `q_jobs_id`
    FOREIGN KEY (`soc_code`)
    REFERENCES `blueeconomics`.`jobs` (`SocCode`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blueecon_faq`.`experts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blueecon_faq`.`experts` (
  `username` VARCHAR(45) NOT NULL,
  `first_name` VARCHAR(45) NOT NULL,
  `last_name` VARCHAR(45) NULL,
  `bio` TINYTEXT NULL,
  `organization` VARCHAR(45) NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`username`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blueecon_faq`.`responses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blueecon_faq`.`responses` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `expert` VARCHAR(45) NOT NULL COMMENT 'references username in experts table',
  `question_id` BIGINT NOT NULL COMMENT 'references id in questions table',
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `response` TINYTEXT NOT NULL,
  `votes` INT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `submitter_idx` (`expert` ASC),
  INDEX `question_id_idx` (`question_id` ASC),
  CONSTRAINT `r_expert_id`
    FOREIGN KEY (`expert`)
    REFERENCES `blueecon_faq`.`experts` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `r_question_id`
    FOREIGN KEY (`question_id`)
    REFERENCES `blueecon_faq`.`questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blueecon_faq`.`expert_question_state`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blueecon_faq`.`expert_question_state` (
  `username` VARCHAR(45) NOT NULL,
  `question_id` BIGINT NOT NULL COMMENT 'references id in questions table',
  `is_read` TINYINT(1) NULL DEFAULT 0,
  `is_responded` TINYINT(1) NULL DEFAULT 0,
  `is_expunged` TINYINT(1) NULL DEFAULT 0,
  `is_muted` TINYINT(1) NULL,
  INDEX `expert_id_idx` (`username` ASC),
  INDEX `question_id_idx` (`question_id` ASC),
  CONSTRAINT `s_expert_id`
    FOREIGN KEY (`username`)
    REFERENCES `blueecon_faq`.`experts` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `s_question_id`
    FOREIGN KEY (`question_id`)
    REFERENCES `blueecon_faq`.`questions` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blueecon_faq`.`expert_group`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blueecon_faq`.`expert_group` (
  `group_name` VARCHAR(45) NOT NULL,
  `soc_code` VARCHAR(7) NULL,
  PRIMARY KEY (`group_name`),
  INDEX `job_id_idx` (`soc_code` ASC),
  CONSTRAINT `g_job_id`
    FOREIGN KEY (`soc_code`)
    REFERENCES `blueeconomics`.`jobs` (`SocCode`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `blueecon_faq`.`expert_group_members`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `blueecon_faq`.`expert_group_members` (
  `expert` VARCHAR(45) NOT NULL COMMENT 'references username in experts',
  `group_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`expert`, `group_name`),
  INDEX (`group_name` ASC),
  CONSTRAINT `m_group`
    FOREIGN KEY (`group_name`)
    REFERENCES `blueecon_faq`.`expert_group` (`group_name`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `m_expert`
    FOREIGN KEY (`expert`)
    REFERENCES `blueecon_faq`.`experts` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
