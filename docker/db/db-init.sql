SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `seller` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `seller`;

CREATE TABLE `sellers`
(
    `id`          int(11) NOT NULL,
    `first_name`  varchar(50) NOT NULL,
    `last_name`   varchar(50) NOT NULL,
    `date_joined` datetime    NOT NULL,
    `country`     char(3)     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


CREATE DATABASE IF NOT EXISTS `contact` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `contact`;

CREATE TABLE `contacts`
(
    `id`                              int(11) NOT NULL AUTO_INCREMENT,
    `seller_id`                       int(11) NOT NULL,
    `full_name`                       varchar(100) NOT NULL,
    `region`                          varchar(30)  NOT NULL,
    `contact_date`                    datetime     NOT NULL,
    `contact_type`                    char(3)      NOT NULL,
    `contact_product_type_offered_id` int(11) NOT NULL,
    `contact_product_type_offered`    varchar(50)  NOT NULL,
    `import_uuid`                     char(36) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY(`seller_id`),
    KEY(`import_uuid`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;


CREATE DATABASE IF NOT EXISTS `sale` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sale`;

CREATE TABLE `sales`
(
    `id`                       int(11) NOT NULL AUTO_INCREMENT,
    `seller_id`                int(11) NOT NULL,
    `sale_net_amount`          int(11) NOT NULL,
    `sale_gross_amount`        int(11) NOT NULL,
    `sale_tax_rate_percentage` int(3) NOT NULL,
    `sale_product_total_cost`  int(11) NOT NULL,
    `sale_date`                datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY(`seller_id`),
    KEY(`sale_date`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE TABLE `totals`
(
    `year`         int(4) NOT NULL,
    `net_amount`   int (11) NOT NULL,
    `gross_amount` int (11) NOT NULL,
    `tax_amount`   int (11) NOT NULL,
    `profit`       int (11) NOT NULL,
    PRIMARY KEY (`year`)
) ENGINE = InnoDB
        DEFAULT CHARSET = utf8;

CREATE DATABASE IF NOT EXISTS `import` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `import`;

CREATE TABLE `imports`
(
    `id`                 int(11) NOT NULL AUTO_INCREMENT,
    `content`            BLOB     NOT NULL,
    `imported_at`        datetime NOT NULL,
    `process_started_at` datetime DEFAULT NULL,
    `process_ended_at`   datetime DEFAULT NULL,
    `total_records`      int(11) DEFAULT NULL,
    `valid_records`      int(11) DEFAULT NULL,
    `imported_records`   int(11) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY(`process_started_at`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;

CREATE USER 'seller-ro'@'%' IDENTIFIED BY 'a1a1a1AA!!';

GRANT SELECT, SHOW VIEW ON seller.* TO 'seller-ro'@'%' IDENTIFIED BY 'a1a1a1AA!!';

CREATE USER 'contact-ro'@'%' IDENTIFIED BY 'a2a2a2AA!!';

GRANT SELECT, SHOW VIEW ON contact.* TO 'contact-ro'@'%' IDENTIFIED BY 'a2a2a2AA!!';

CREATE USER 'sale-ro'@'%' IDENTIFIED BY 'a3a3a3AA!!';

GRANT SELECT, SHOW VIEW ON sale.* TO 'sale-ro'@'%' IDENTIFIED BY 'a3a3a3AA!!';

CREATE USER 'import-rw'@'%' IDENTIFIED BY 'a4a4a4AA!!';

GRANT SELECT, SHOW VIEW, INSERT ON import.* TO 'import-rw'@'%' IDENTIFIED BY 'a4a4a4AA!!';

FLUSH PRIVILEGES;

