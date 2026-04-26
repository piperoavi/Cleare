CREATE DATABASE IF NOT EXISTS Cleare
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE Cleare;

-- 1. ROLES
CREATE TABLE roles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50)  NOT NULL UNIQUE,
    description VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. USERS
CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id     INT UNSIGNED NOT NULL DEFAULT 2,
    name        VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    phone       VARCHAR(30),
    address     VARCHAR(255),
    city        VARCHAR(100),
    gender      VARCHAR(10)  CHECK (gender IN ('male','female','other')),
    birth_date  DATE,
    points      INT UNSIGNED NOT NULL DEFAULT 0,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_user_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. POINTS_LOG
CREATE TABLE points_log (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    points     INT          NOT NULL,
    reason     VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_points_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. CATEGORIES
CREATE TABLE categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    type        VARCHAR(10)  NOT NULL
                    CHECK (type IN ('skincare','makeup','hair','body')),
    slug        VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image       VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. PRODUCTS
CREATE TABLE products (
    id          INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED  NOT NULL,
    name        VARCHAR(200)  NOT NULL,
    slug        VARCHAR(200)  NOT NULL UNIQUE,
    description TEXT,
    price       DECIMAL(10,2) NOT NULL,
    stock       INT UNSIGNED  NOT NULL DEFAULT 0,
    image       VARCHAR(255),
    type        VARCHAR(10)   CHECK (type IN ('skincare','makeup','hair','body')),
    size        VARCHAR(20),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_product_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 6. COUPONS
CREATE TABLE coupons (
    id             INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    code           VARCHAR(50)   NOT NULL UNIQUE,
    type           VARCHAR(10)   NOT NULL DEFAULT 'percent'
                       CHECK (type IN ('percent','fixed')),
    applies_to     VARCHAR(10)   NOT NULL DEFAULT 'all'
                       CHECK (applies_to IN ('all','skincare','makeup','hair','body')),
    discount_value DECIMAL(10,2) NOT NULL,
    active         TINYINT(1)    NOT NULL DEFAULT 1,
    expires_at     DATE
) ENGINE=InnoDB;

-- 7. COUPON_USAGE
CREATE TABLE coupon_usage (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NOT NULL,
    coupon_id  INT UNSIGNED NOT NULL,
    used_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_user_coupon (user_id, coupon_id),

    CONSTRAINT fk_usage_user
        FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    CONSTRAINT fk_usage_coupon
        FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. ORDERS
CREATE TABLE orders (
    id               INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    user_id          INT UNSIGNED  DEFAULT NULL,
    coupon_id        INT UNSIGNED  DEFAULT NULL,
    subtotal         DECIMAL(10,2) NOT NULL,
    discount         DECIMAL(10,2) NOT NULL DEFAULT 0,
    total            DECIMAL(10,2) NOT NULL,
    status           VARCHAR(15)   NOT NULL DEFAULT 'pending'
                         CHECK (status IN ('pending','processing','shipped','delivered','cancelled')),
    payment_method   VARCHAR(20)   DEFAULT NULL
                         CHECK (payment_method IN ('stripe','paypal','cash')),
    payment_status   VARCHAR(15)   NOT NULL DEFAULT 'unpaid'
                         CHECK (payment_status IN ('unpaid','paid','refunded')),
    shipping_name    VARCHAR(150)  NOT NULL,
    shipping_email   VARCHAR(150)  NOT NULL,
    shipping_address VARCHAR(255)  NOT NULL,
    shipping_city    VARCHAR(100)  NOT NULL,
    shipping_phone   VARCHAR(30)   NOT NULL,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_user
        FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE SET NULL,
    CONSTRAINT fk_order_coupon
        FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 9. ORDER_ITEMS
CREATE TABLE order_items (
    id           INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    order_id     INT UNSIGNED  NOT NULL,
    product_id   INT UNSIGNED  DEFAULT NULL,
    product_name VARCHAR(200)  NOT NULL,
    price        DECIMAL(10,2) NOT NULL,
    quantity     INT UNSIGNED  NOT NULL,
    subtotal     DECIMAL(10,2) GENERATED ALWAYS AS (price * quantity) STORED,

    CONSTRAINT fk_item_order
        FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    CONSTRAINT fk_item_product
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 10. CART
CREATE TABLE cart (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED DEFAULT NULL,
    product_id INT UNSIGNED NOT NULL,
    quantity   INT UNSIGNED NOT NULL DEFAULT 1,
    added_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY unique_cart_item (user_id, product_id),

    CONSTRAINT fk_cart_user
        FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    CONSTRAINT fk_cart_product
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 11. SEARCH_HISTORY
CREATE TABLE search_history (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED DEFAULT NULL,
    query      VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_search_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;


-- INSERT DATA IN ALL TABLES

-- ROLES
INSERT INTO roles (id, name, description) VALUES
(1, 'admin',     'Full platform administrator'),
(2, 'customer',  'Regular customer'),
(3, 'moderator', 'Product and content moderator');

-- USERS
INSERT INTO users (id, role_id, name, email, password, phone, address, city, gender, birth_date, points) VALUES
(1, 1, 'Admin Cleare',    'admin@cleare.al',   '$2y$12$placeholder', '+355 69 100 0001', '5 Elbasani Street',       'Tirana',  'female', '1990-03-15',   0),
(2, 2, 'Arta Hoxha',      'arta@gmail.com',    '$2y$12$placeholder', '+355 69 200 0001', '12 Kavaja Street',        'Tirana',  'female', '1995-07-22', 150),
(3, 2, 'Blerina Kola',    'blerina@gmail.com', '$2y$12$placeholder', '+355 69 200 0002', '5 Epidamn Boulevard',     'Durres',  'female', '1988-11-30',  80),
(4, 2, 'Mirela Dervishi', 'mirela@gmail.com',  '$2y$12$placeholder', '+355 69 200 0003', '3 Ismail Qemali Street',  'Vlore',   'female', '2000-01-05', 200),
(5, 2, 'Alban Shehu',     'alban@gmail.com',   '$2y$12$placeholder', '+355 69 200 0004', '22 November 28 Street',   'Shkoder', 'male',   '1993-05-18',  50),
(6, 3, 'Klajdi Muca',     'klajdi@gmail.com',  '$2y$12$placeholder', '+355 69 200 0005', '7 Skenderbeg Quarter',    'Korce',   'male',   '1985-09-12',   0),
(7, 2, 'Dea Prifti',      'dea@gmail.com',     '$2y$12$placeholder', '+355 69 200 0006', '15 Ali Demi Street',      'Tirana',  'female', '1998-03-21', 100);

-- CATEGORIES
INSERT INTO categories (id, name, type, slug, description) VALUES
(1,  'Face Care',             'skincare', 'face-care',          'Skincare products for daily face routine'),
(2,  'Body Care',             'body',     'body-care',          'Skincare products for body hydration'),
(3,  'Sun Protection',        'skincare', 'sun-protection',     'Sunscreen and UV protection'),
(4,  'Face Makeup',           'makeup',   'face-makeup',        'Foundation, blush, bronzer and more'),
(5,  'Eyes & Lips',           'makeup',   'eyes-lips',          'Mascara, eyeliner, lipstick and lip gloss'),
(6,  'Tools',                 'makeup',   'tools',              'Brushes, sponges and application tools'),
(7,  'Shampoo & Conditioner', 'hair',     'shampoo-conditioner','Shampoo and conditioner for all hair types'),
(8,  'Hair Treatments',       'hair',     'hair-treatments',    'Masks, serums and intensive hair treatments'),
(9,  'Hair Styling',          'hair',     'hair-styling',       'Styling sprays, oils and creams'),
(10, 'Body Lotion & Cream',   'body',     'body-lotion-cream',  'Body lotions and moisturising creams'),
(11, 'Body Wash',             'body',     'body-wash',          'Shower gels and body soaps'),
(12, 'Scrubs & Exfoliants',   'body',     'scrubs-exfoliants',  'Body scrubs and exfoliating treatments');

-- PRODUCTS
INSERT INTO products (id, category_id, name, slug, description, price, stock, image, type, size) VALUES
-- SKINCARE: Face Care 
(1,  1, 'Hydra Boost Moisturizer',     'hydra-boost-moisturizer',     'Lightweight moisturiser with hyaluronic acid and aloe vera.',      24.99, 50, 'prod-hydra-boost.jpg',    'skincare', '50ml'),
(2,  1, 'Vitamin C Brightening Serum', 'vitamin-c-brightening-serum', '15% Vitamin C serum for brighter, more even skin tone.',          39.90, 35, 'prod-vit-c-serum.jpg',    'skincare', '30ml'),
(3,  1, 'Gentle Foam Cleanser',        'gentle-foam-cleanser',        'Sulphate-free foam cleanser with balanced pH.',                    18.50, 60, 'prod-foam-cleanser.jpg',  'skincare', '150ml'),
(4,  1, 'Retinol Night Renewal Serum', 'retinol-night-renewal-serum', '0.3% Retinol night serum for cellular renewal and anti-ageing.',   44.90, 30, 'prod-retinol-serum.jpg',  'skincare', '30ml'),
(5,  1, 'Niacinamide 10% Serum',       'niacinamide-10-serum',        'Minimises pores and dark spots. Ideal for oily skin.',             22.00, 45, 'prod-niacinamide.jpg',    'skincare', '30ml'),
(6,  1, 'Kaolin Clay Mask',            'kaolin-clay-mask',            'Deep cleansing clay mask that minimises pores.',                   19.90, 40, 'prod-clay-mask.jpg',      'skincare', '75ml'),
-- BODY: Body Care
(7,  2, 'Barrier Repair Body Cream',   'barrier-repair-body-cream',   'Rich body cream with ceramides and shea butter.',                  32.00, 40, 'prod-body-cream.jpg',     'body',     '200ml'),
(8,  2, 'Shea Body Lotion',            'shea-body-lotion',            'Lightweight lotion with shea butter and vitamin E.',               18.00, 65, 'prod-shea-lotion.jpg',    'body',     '250ml'),
                                                                                                              
-- SKINCARE: Sun Protection
(9,  3, 'SPF 50+ Daily Shield',        'spf-50-daily-shield',         'SPF 50+ sunscreen with a water-light texture. No white cast.',     29.00, 45, 'prod-spf50.jpg',          'skincare', '50ml'),
(10, 3, 'SPF 30 Tinted Moisturizer',   'spf-30-tinted-moisturizer',   'Tinted moisturiser with SPF 30. Two-in-one daily protection.',     26.50, 38, 'prod-spf30-tinted.jpg',   'skincare', '50ml'),
-- MAKEUP: Face Makeup 
(11, 4, 'Velvet Matte Foundation',     'velvet-matte-foundation',     'Full coverage foundation with a matte finish. 20 shades.',         27.50, 55, 'prod-foundation.jpg',     'makeup',   '30ml'),
(12, 4, 'Glow Blush Powder',           'glow-blush-powder',           'Soft blush with a subtle shimmer. Available in 4 shades.',         16.00, 50, 'prod-blush.jpg',          'makeup',   '8g'),
(13, 4, 'Sculpt & Define Bronzer',     'sculpt-define-bronzer',       'Matte bronzer for natural-looking contouring.',                    19.50, 42, 'prod-bronzer.jpg',        'makeup',   '10g'),
-- MAKEUP: Eyes & Lips 
(14, 5, 'Plump & Shine Lip Gloss',     'plump-shine-lip-gloss',       'Plumping lip gloss for fuller-looking lips. 8 shades.',            14.90, 80, 'prod-lip-gloss.jpg',      'makeup',   '5ml'),
(15, 5, 'Satin Longwear Lipstick',     'satin-longwear-lipstick',     'Satin finish lipstick that lasts up to 8 hours.',                  17.00, 60, 'prod-lipstick.jpg',       'makeup',   '3.5g'),
(16, 5, 'Volume & Curl Mascara',       'volume-curl-mascara',         'Volumising and curling mascara. Clump-free formula.',              18.50, 70, 'prod-mascara.jpg',        'makeup',   '10ml'),
(17, 5, 'Precision Liquid Eyeliner',   'precision-liquid-eyeliner',   'Waterproof liquid eyeliner. Lasts up to 16 hours.',                16.00, 65, 'prod-eyeliner.jpg',       'makeup',   '1.5ml'),
-- MAKEUP: Tools 
(18, 6, 'Pro Blending Sponge',         'pro-blending-sponge',         'Latex-free blending sponge for a seamless finish.',                 9.90, 90, 'prod-sponge.jpg',         'makeup',   NULL),
(19, 6, 'Foundation Brush Set',        'foundation-brush-set',        'Set of 5 professional face brushes.',                             24.00, 35, 'prod-brush-set.jpg',      'makeup',   NULL),
-- HAIR: Shampoo & Conditioner 
(20, 7, 'Keratin Repair Shampoo',      'keratin-repair-shampoo',      'Keratin-infused shampoo for damaged and frizzy hair.',             14.50, 55, 'prod-keratin-shampoo.jpg','hair',     '250ml'),
(21, 7, 'Hydrating Conditioner',       'hydrating-conditioner',       'Moisturising conditioner with argan oil and protein.',             13.00, 50, 'prod-conditioner.jpg',    'hair',     '250ml'),
-- HAIR: Hair Treatments 
(22, 8, 'Deep Repair Hair Mask',       'deep-repair-hair-mask',       'Intensive mask with keratin and argan oil for deep repair.',       19.90, 40, 'prod-hair-mask.jpg',      'hair',     '200ml'),
(23, 8, 'Argan Oil Hair Serum',        'argan-oil-hair-serum',        '100% pure argan oil serum. Protects and adds shine.',              16.50, 45, 'prod-argan-serum.jpg',    'hair',     '100ml'),
-- HAIR: Hair Styling
(24, 9, 'Flexible Hold Hair Spray',    'flexible-hold-hair-spray',    'Flexible hold spray with natural finish and light shine.',         11.00, 60, 'prod-hairspray.jpg',      'hair',     '300ml'),
-- BODY: Body Lotion & Cream 
(25, 10,'Coconut Body Butter',         'coconut-body-butter',         'Rich coconut and shea body butter. 24-hour hydration.',            22.00, 50, 'prod-body-butter.jpg',    'body',     '200ml'),
(26, 10,'Vanilla Glow Body Oil',       'vanilla-glow-body-oil',       'Vanilla and jojoba body oil for radiant, glowing skin.',           18.90, 45, 'prod-body-oil.jpg',       'body',     '100ml'),
-- BODY: Body Wash
(27, 11,'Moisturising Shower Gel',     'moisturising-shower-gel',     'Shower gel with aloe vera and vitamin E for soft skin.',           12.00, 70, 'prod-shower-gel.jpg',     'body',     '300ml'),
(28, 11,'Exfoliating Body Wash',       'exfoliating-body-wash',       'Body wash with natural micro-granules for gentle exfoliation.',    13.50, 65, 'prod-body-wash.jpg',      'body',     '300ml'),
-- BODY: Scrubs & Exfoliants 
(29, 12,'Coffee Anti-Cellulite Scrub', 'coffee-anti-cellulite-scrub', 'Coffee scrub that boosts circulation and reduces cellulite.',      15.00, 60, 'prod-coffee-scrub.jpg',   'body',     '200g'),
(30, 12,'Sugar Lavender Scrub',        'sugar-lavender-scrub',        'Gentle scrub with organic sugar and lavender essential oil.',       12.50, 75, 'prod-sugar-scrub.jpg',    'body',     '250g');

-- COUPONS
INSERT INTO coupons (id, code, type, applies_to, discount_value, active, expires_at) VALUES
(1, 'CLEARE10', 'percent', 'all',      10.00, 1, '2026-12-31'),
(2, 'NEWUSER',  'percent', 'all',      15.00, 1, '2026-12-31'),
(3, 'SKIN20',   'percent', 'skincare', 20.00, 1, '2026-12-31'),
(4, 'HAIR15',   'percent', 'hair',     15.00, 1, '2026-12-31'),
(5, 'BODY10',   'fixed',   'body',     10.00, 1, '2026-12-31'),
(6, 'SUMMER26', 'percent', 'all',      25.00, 1, '2026-08-31');

-- ORDERS
INSERT INTO orders (id, user_id, coupon_id, subtotal, discount, total, status, payment_method, payment_status,
                    shipping_name, shipping_email, shipping_address, shipping_city, shipping_phone) VALUES
(1, 2, 1,    64.89,  6.49, 58.40, 'delivered',  'cash',   'paid',
    'Arta Hoxha',     'arta@gmail.com',    '12 Kavaja Street',        'Tirana',  '+355 69 200 0001'),
(2, 3, NULL, 29.00,  0.00, 29.00, 'processing', 'stripe', 'paid',
    'Blerina Kola',   'blerina@gmail.com', '5 Epidamn Boulevard',     'Durres',  '+355 69 200 0002'),
(3, 4, 3,    44.90,  8.98, 35.92, 'delivered',  'stripe', 'paid',
    'Mirela Dervishi','mirela@gmail.com',  '3 Ismail Qemali Street',  'Vlore',   '+355 69 200 0003'),
(4, 5, NULL, 22.00,  0.00, 22.00, 'pending',    'cash',   'unpaid',
    'Alban Shehu',    'alban@gmail.com',   '22 November 28 Street',   'Shkoder', '+355 69 200 0004'),
(5, 7, 6,    60.90, 15.23, 45.67, 'shipped',    'paypal', 'paid',
    'Dea Prifti',     'dea@gmail.com',     '15 Ali Demi Street',      'Tirana',  '+355 69 200 0006');

-- ORDER_ITEMS
INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES
-- Order by Arta
(1, 1,  'Hydra Boost Moisturizer',     24.99, 1),
(1, 2,  'Vitamin C Brightening Serum', 39.90, 1),
-- Order by Blerina
(2, 9,  'SPF 50+ Daily Shield',        29.00, 1),
-- Order by Mirela
(3, 4,  'Retinol Night Renewal Serum', 44.90, 1),
-- Order by Alban
(4, 5,  'Niacinamide 10% Serum',       22.00, 1),
-- Order by Dea
(5, 11, 'Velvet Matte Foundation',     27.50, 1),
(5, 14, 'Plump & Shine Lip Gloss',     14.90, 1),
(5, 16, 'Volume & Curl Mascara',       18.50, 1);

-- COUPON_USAGE
INSERT INTO coupon_usage (user_id, coupon_id) VALUES
(2, 1),
(4, 3);

-- CART
INSERT INTO cart (user_id, product_id, quantity) VALUES
(2,  9, 1),
(3, 22, 1),
(4,  6, 2),
(5, 29, 1),
(7, 14, 1),
(7, 16, 2);

-- POINTS_LOG
INSERT INTO points_log (user_id, points, reason) VALUES
(2,  100, 'Purchase - Order #1'),
(2,   50, 'Registration bonus'),
(3,   80, 'Purchase - Order #2'),
(4,  100, 'Purchase - Order #3'),
(4,  100, 'Promotional bonus'),
(5,   50, 'Purchase - Order #4'),
(7,  100, 'Purchase - Order #5'),
(7,   50, 'Registration bonus'),
(7,  -50, 'Points refund - Order cancelled');


-- SEARCH_HISTORY
INSERT INTO search_history (user_id, query) VALUES
(2,    'vitamin c serum'),
(2,    'sunscreen spf 50'),
(3,    'keratin shampoo'),
(4,    'retinol serum'),
(4,    'face mask'),
(5,    'spf 30'),
(7,    'lip gloss nude'),
(7,    'matte foundation'),
(NULL, 'moisturizer'),
(NULL, 'face cleanser');
