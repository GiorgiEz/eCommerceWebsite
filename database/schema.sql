/* 1. CREATE THE DATABASE */

DROP DATABASE IF EXISTS ecommerce_store;

-- Create database using utf8mb4 to support full Unicode (all special characters)
-- Collation defines how text is compared and sorted (utf8mb4_unicode_ci = Unicode-aware, case-insensitive)
CREATE DATABASE ecommerce_store
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ecommerce_store;
