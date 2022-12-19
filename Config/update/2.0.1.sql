-- ---------------------------------------------------------------------
-- payment_customer_condition
-- ---------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `payment_customer_condition`
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

CREATE TABLE IF NOT EXISTS `payment_customer_module_condition`
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