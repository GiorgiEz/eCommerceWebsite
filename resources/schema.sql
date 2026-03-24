/* 1. CREATE DATABASE */

DROP DATABASE IF EXISTS ecommerce_store;

-- Create Database using utf8mb4 to support full Unicode (all special characters)
-- Collation defines how text is compared and sorted (utf8mb4_unicode_ci = Unicode-aware, case-insensitive)
CREATE DATABASE ecommerce_store
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ecommerce_store;

/* 2. CREATE TABLES */

-- 1. categories
CREATE TABLE categories (
    category_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    category_name VARCHAR(20) NOT NULL,

    CONSTRAINT pk_category PRIMARY KEY (category_id),
    CONSTRAINT uq_category_name UNIQUE (category_name)
) ENGINE=InnoDB;

-- 2. products
CREATE TABLE products (
    product_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    product_category_id INT UNSIGNED NOT NULL,
    product_external_id VARCHAR(100) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_description TEXT NOT NULL,
    product_brand VARCHAR(255) NOT NULL,
    product_in_stock TINYINT(1) NOT NULL,

    CONSTRAINT pk_product PRIMARY KEY (product_id),
    CONSTRAINT uq_product_external_id UNIQUE (product_external_id),

    CONSTRAINT fk_products_category FOREIGN KEY (product_category_id)
        REFERENCES categories(category_id)
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 3. currencies
CREATE TABLE currencies (
    currency_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    currency_label VARCHAR(10) NOT NULL,
    currency_symbol VARCHAR(5) NOT NULL,

    CONSTRAINT pk_currency PRIMARY KEY (currency_id),
    CONSTRAINT uq_currency_label UNIQUE (currency_label)
) ENGINE=InnoDB;

-- 4. prices
CREATE TABLE prices (
    price_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    price_product_id INT UNSIGNED NOT NULL,
    price_currency_id INT UNSIGNED NOT NULL,
    price_amount DECIMAL(10,2) NOT NULL,

    CONSTRAINT pk_price PRIMARY KEY (price_id),
    CONSTRAINT uq_price_product_currency UNIQUE (price_product_id, price_currency_id),

    CONSTRAINT fk_prices_product FOREIGN KEY (price_product_id)
        REFERENCES products(product_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_prices_currency FOREIGN KEY (price_currency_id)
        REFERENCES currencies(currency_id)
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 5. images
CREATE TABLE images (
    image_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    image_product_id INT UNSIGNED NOT NULL,
    image_url VARCHAR(255) NOT NULL,

    CONSTRAINT pk_image PRIMARY KEY (image_id),
    CONSTRAINT uq_image_product_url UNIQUE (image_product_id, image_url),

    CONSTRAINT fk_images_product FOREIGN KEY (image_product_id)
        REFERENCES products(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. attributes
CREATE TABLE attributes (
    attribute_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    attribute_external_id VARCHAR(100) NOT NULL,
    attribute_name VARCHAR(100) NOT NULL,
    attribute_type VARCHAR(20) NOT NULL,

    CONSTRAINT pk_attribute PRIMARY KEY (attribute_id),
    CONSTRAINT uq_attribute_external UNIQUE (attribute_external_id)
) ENGINE=InnoDB;

-- 7. attribute_items
CREATE TABLE attribute_items (
    attribute_item_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    attribute_item_attribute_id INT UNSIGNED NOT NULL,
    attribute_item_external_id VARCHAR(100) NOT NULL,
    attribute_item_display_value VARCHAR(100) NOT NULL,
    attribute_item_value VARCHAR(100) NOT NULL,

    CONSTRAINT pk_item PRIMARY KEY (attribute_item_id),
    CONSTRAINT uq_item_attribute_external
        UNIQUE (attribute_item_attribute_id, attribute_item_external_id),

    CONSTRAINT fk_items_attribute FOREIGN KEY (attribute_item_attribute_id)
        REFERENCES attributes(attribute_id)
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 8. product_attribute_items
CREATE TABLE product_attribute_items (
    product_attribute_item_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    product_attribute_item_attribute_item_id INT UNSIGNED NOT NULL,
    product_attribute_item_product_id INT UNSIGNED NOT NULL,

    CONSTRAINT pk_product_attribute_item PRIMARY KEY (product_attribute_item_id),
    CONSTRAINT uq_product_attribute_item UNIQUE
        (product_attribute_item_attribute_item_id, product_attribute_item_product_id),

    CONSTRAINT fk_product_attribute_items_attribute_item FOREIGN KEY
        (product_attribute_item_attribute_item_id)
        REFERENCES attribute_items(attribute_item_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_product_attribute_items_product FOREIGN KEY
        (product_attribute_item_product_id)
        REFERENCES products(product_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. orders
CREATE TABLE orders (
    order_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    order_total_amount DECIMAL(10,2) NOT NULL,

    CONSTRAINT pk_order PRIMARY KEY (order_id)
) ENGINE=InnoDB;

-- 10. order_items
CREATE TABLE order_items (
    order_item_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    order_item_order_id INT UNSIGNED NOT NULL,
    order_item_product_id INT UNSIGNED NOT NULL,
    order_item_quantity INT UNSIGNED NOT NULL,
    order_item_price DECIMAL(10,2) NOT NULL,

    CONSTRAINT pk_order_item PRIMARY KEY (order_item_id),

    CONSTRAINT fk_order_items_order FOREIGN KEY (order_item_order_id)
    REFERENCES orders(order_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_order_items_product FOREIGN KEY (order_item_product_id)
        REFERENCES products(product_id)
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 11. order_item_attributes
CREATE TABLE order_item_attributes (
    order_item_attribute_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
    order_item_attribute_order_item_id INT UNSIGNED NOT NULL,
    order_item_attribute_attribute_item_id INT UNSIGNED NOT NULL,

    CONSTRAINT pk_order_item_attribute PRIMARY KEY (order_item_attribute_id),
    CONSTRAINT uq_order_item_attribute
        UNIQUE (order_item_attribute_order_item_id, order_item_attribute_attribute_item_id),

    CONSTRAINT fk_order_item_attributes_order_item FOREIGN KEY
        (order_item_attribute_order_item_id)
        REFERENCES order_items(order_item_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_order_item_attributes_attribute_item FOREIGN KEY
        (order_item_attribute_attribute_item_id)
        REFERENCES attribute_items(attribute_item_id)
        ON DELETE RESTRICT
) ENGINE=InnoDB;