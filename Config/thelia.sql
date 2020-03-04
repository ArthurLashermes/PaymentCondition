
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- payment_delivery_condition
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `payment_delivery_condition`;

CREATE TABLE `payment_delivery_condition`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `payment_module_id` INTEGER NOT NULL,
    `delivery_module_id` INTEGER NOT NULL,
    `is_valid` TINYINT,
    PRIMARY KEY (`id`),
    INDEX `fi_payment_delivery_condition_payment_module_id` (`payment_module_id`),
    INDEX `fi_payment_delivery_condition_delivery_module_id` (`delivery_module_id`),
    CONSTRAINT `fk_payment_delivery_condition_payment_module_id`
        FOREIGN KEY (`payment_module_id`)
        REFERENCES `module` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_payment_delivery_condition_delivery_module_id`
        FOREIGN KEY (`delivery_module_id`)
        REFERENCES `module` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- payment_customer_family_condition
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `payment_customer_family_condition`;

CREATE TABLE `payment_customer_family_condition`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `payment_module_id` INTEGER NOT NULL,
    `customer_family_id` INTEGER NOT NULL,
    `is_valid` TINYINT,
    PRIMARY KEY (`id`),
    INDEX `fi_payment_customer_family_condition_payment_module_id` (`payment_module_id`),
    CONSTRAINT `fk_payment_customer_family_condition_payment_module_id`
        FOREIGN KEY (`payment_module_id`)
        REFERENCES `module` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- payment_area_condition
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `payment_area_condition`;

CREATE TABLE `payment_area_condition`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `payment_module_id` INTEGER NOT NULL,
    `area_id` INTEGER NOT NULL,
    `is_valid` TINYINT,
    PRIMARY KEY (`id`),
    INDEX `fi_payment_area_condition_payment_module_id` (`payment_module_id`),
    INDEX `fi_payment_area_condition_area_id` (`area_id`),
    CONSTRAINT `fk_payment_area_condition_payment_module_id`
        FOREIGN KEY (`payment_module_id`)
        REFERENCES `module` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_payment_area_condition_area_id`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- payment_customer_condition
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `payment_customer_condition`;

CREATE TABLE `payment_customer_condition`
(
    `customer_id` INTEGER NOT NULL,
    `module_restriction_active` TINYINT,
    PRIMARY KEY (`customer_id`),
    CONSTRAINT `fk_payment_customer_condition_customer_id`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- payment_customer_module_condition
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `payment_customer_module_condition`;

CREATE TABLE `payment_customer_module_condition`
(
    `payment_module_id` INTEGER NOT NULL,
    `customer_id` INTEGER NOT NULL,
    `is_valid` TINYINT,
    PRIMARY KEY (`payment_module_id`,`customer_id`),
    INDEX `fi_payment_customer_module_condition_customer_id` (`customer_id`),
    CONSTRAINT `fk_payment_customer_module_condition_customer_id`
        FOREIGN KEY (`customer_id`)
        REFERENCES `customer` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE,
    CONSTRAINT `fk_payment_customer_module_condition_payment_module_id`
        FOREIGN KEY (`payment_module_id`)
        REFERENCES `module` (`id`)
        ON UPDATE RESTRICT
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
