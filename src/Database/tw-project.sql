-- MySQL Script generated by MySQL Workbench
-- Fri Apr 26 14:11:39 2024
-- Model: New Model Version: 1.0
-- MySQL Workbench Forward Engineering

-- Drop existing schema if exists
DROP SCHEMA IF EXISTS `tw-project`;

-- Create new schema
CREATE SCHEMA IF NOT EXISTS `tw-project` DEFAULT CHARACTER SET utf8mb4;
USE `tw-project`;

-- Table `users`
CREATE TABLE `users`
(
    `id`                 INT AUTO_INCREMENT PRIMARY KEY,
    `username`           VARCHAR(50)  NOT NULL UNIQUE,
    `password`           VARCHAR(255) NOT NULL,
    `email`              VARCHAR(100) NOT NULL UNIQUE,
    `created_at`         TIMESTAMP              DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         TIMESTAMP              DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `role`               ENUM ('admin', 'user') DEFAULT 'user',
    `confirmed`          BOOLEAN                DEFAULT FALSE,
    `confirmation_token` VARCHAR(32),
    `reset_token` VARCHAR(32)
);

-- Table `AnimalType`
CREATE TABLE IF NOT EXISTS `AnimalType`
(
    `id`   INT          NOT NULL AUTO_INCREMENT,
    `Type` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- Table `Reports`
CREATE TABLE IF NOT EXISTS `Reports` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(60) NOT NULL,
  `Animal_name` VARCHAR(60) NOT NULL,
  `Location` VARCHAR(45) NOT NULL,
  `Description` TEXT NOT NULL,
  `Phone_Number` VARCHAR(15) NOT NULL,
  `AnimalType_id` INT NOT NULL,
  `tags` VARCHAR(500) NOT NULL,
    `user_id` INT NOT NULL,
    `status` VARCHAR(45) NOT NULL DEFAULT 'active',
    `timestamp`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Reports_AnimalType1_idx` (`AnimalType_id` ASC),
  CONSTRAINT `fk_Reports_AnimalType1`
    FOREIGN KEY (`AnimalType_id`)
    REFERENCES `AnimalType` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
    CONSTRAINT `fk_Reports_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- Table `Images`
CREATE TABLE IF NOT EXISTS `Images`
(
    `file_name`  VARCHAR(100) NOT NULL,
    `Extension`  VARCHAR(6)   NOT NULL,
    `Reports_id` INT          NOT NULL,
    PRIMARY KEY (`file_name`),
    INDEX `fk_Images_Reports1_idx` (`Reports_id` ASC),
    CONSTRAINT `fk_Images_Reports1`
        FOREIGN KEY (`Reports_id`)
            REFERENCES `Reports` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE
) ENGINE = InnoDB;

-- Table `Tags`
CREATE TABLE IF NOT EXISTS `Tags`
(
    `id`   INT         NOT NULL AUTO_INCREMENT,
    `Text` VARCHAR(45) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;

-- Table `Reports_has_Tags`
CREATE TABLE IF NOT EXISTS `Reports_has_Tags` (
  `Reports_id` INT NOT NULL,
  `Tags_id` INT NOT NULL,
  PRIMARY KEY (`Reports_id`, `Tags_id`),
  INDEX `fk_Reports_has_Tags_Tags1_idx` (`Tags_id` ASC),
  INDEX `fk_Reports_has_Tags_Reports1_idx` (`Reports_id` ASC),
  CONSTRAINT `fk_Reports_has_Tags_Reports1`
    FOREIGN KEY (`Reports_id`)
    REFERENCES `Reports` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Reports_has_Tags_Tags1`
    FOREIGN KEY (`Tags_id`)
    REFERENCES `Tags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- Table `Coordinates`
CREATE TABLE IF NOT EXISTS `Coordinates` (
  `latitude` VARCHAR(100) NOT NULL,
  `longitude` VARCHAR(100) NOT NULL,
  `Reports_id` INT NOT NULL,
  INDEX `fk_Coordinates_Reports1_idx` (`Reports_id` ASC),
  PRIMARY KEY (`Reports_id`),
  CONSTRAINT `fk_Coordinates_Reports1`
    FOREIGN KEY (`Reports_id`)
    REFERENCES `Reports` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB;

-- Table `Contact`
CREATE TABLE IF NOT EXISTS `Contact`
(
    `id`      INT           NOT NULL AUTO_INCREMENT,
    `name`    VARCHAR(60)   NOT NULL,
    `email`   VARCHAR(60)   NOT NULL,
    `message` VARCHAR(1000) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;


insert into animaltype (type)
values ('dog'),
       ('cat'),
       ('hamster'),
       ('bird'),
       ('parrot'),
       ('rabbit'),
       ('guinea pig'),
       ('other');

insert into tags (Text)
values ('dangerous'),
       ('mad'),
       ('injured'),
       ('friendly'),
       ('scared'),
       ('sick'),
       ('left shelter'),
       ('violent'),
       ('trained'),
       ('aggressive towards children'),
       ('other');

insert into users (username, password, email, role, confirmed)
values
('admin', '$2y$10$KAkN1oKaUMVmx2CH7J/s7ekZtaMrnXUoqN4XK6bSlzf9/iYK1SDEu', 'flaviburca@gmail.com', 'admin', true);


