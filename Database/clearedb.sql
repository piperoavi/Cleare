--  CLEARE DATABASE 

CREATE DATABASE IF NOT EXISTS Cleare
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE Cleare;

-- 1. ROLES

CREATE TABLE roles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50)  NOT NULL UNIQUE,   -- 'admin', 'customer', 'moderator', ...
    description VARCHAR(255),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. USERS 

CREATE TABLE users (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id     INT UNSIGNED NOT NULL DEFAULT 2,   -- 2 = customer
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
        FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE
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
    id              INT UNSIGNED  AUTO_INCREMENT PRIMARY KEY,
    code            VARCHAR(50)   NOT NULL UNIQUE,
    type            VARCHAR(10)   NOT NULL DEFAULT 'percent'
                        CHECK (type IN ('percent','fixed')),
    applies_to      VARCHAR(10)   NOT NULL DEFAULT 'all'
                        CHECK (applies_to IN ('all','skincare','hair','body','makeup')),
    discount_value  DECIMAL(10,2) NOT NULL,
    active          TINYINT(1)    NOT NULL DEFAULT 1,
    used            TINYINT(1)    NOT NULL DEFAULT 0,
    expires_at      DATE
) ENGINE=InnoDB;

-- 7. ORDERS

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

-- 8. ORDER_ITEMS

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

-- 9. CART

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

-- 10. SEARCH_HISTORY

CREATE TABLE search_history (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED DEFAULT NULL,
    query      VARCHAR(200) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_search_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

--  INSERT DATA

-- ROLES

INSERT INTO roles (id, name, description) VALUES
(1, 'admin',     'Administrues i plotë i platformës'),
(2, 'customer',  'Klient i rregullt'),
(3, 'moderator', 'Moderues i produkteve dhe komenteve');

-- USERS

INSERT INTO users (id, role_id, name, email, password, phone, address, city, gender, birth_date, points) VALUES
(1, 1, 'Arta Hoxha',      'arta@cleare.al',    '$2y$10$adminHashHere1',    '+355691111111', 'Rruga e Elbasanit 5',   'Tiranë',    'female', '1990-03-15', 0),
(2, 2, 'Blerina Koci',    'blerina@gmail.com', '$2y$10$userHashHere1',     '+355692222222', 'Rruga Myslym Shyri 12', 'Tiranë',    'female', '1995-07-22', 150),
(3, 2, 'Erjon Leka',      'erjon@gmail.com',   '$2y$10$userHashHere2',     '+355693333333', 'Bulevardi Zogu I 8',    'Durrës',    'male',   '1988-11-30', 80),
(4, 2, 'Mirela Dervishi', 'mirela@gmail.com',  '$2y$10$userHashHere3',     '+355694444444', 'Rruga Ismail Qemali 3', 'Vlorë',     'female', '2000-01-05', 200),
(5, 2, 'Alban Shehu',     'alban@gmail.com',   '$2y$10$userHashHere4',     '+355695555555', 'Rruga 28 Nentori 22',   'Shkodër',   'male',   '1993-05-18', 50),
(6, 3, 'Sara Gjoka',      'sara@cleare.al',    '$2y$10$modHashHere1',      '+355696666666', 'Rruga Kavajes 44',      'Tiranë',    'female', '1997-09-10', 0),
(7, 2, 'Klajdi Muça',     'klajdi@gmail.com',  '$2y$10$userHashHere5',     '+355697777777', 'Lagja Skenderbeg 7',    'Korçë',     'male',   '1991-12-25', 320),
(8, 2, 'Dea Prifti',      'dea@gmail.com',     '$2y$10$userHashHere6',     '+355698888888', 'Rruga Ali Demi 15',     'Tiranë',    'female', '2001-04-02', 10);

-- CATEGORIES

INSERT INTO categories (id, name, type, slug, description, image) VALUES
-- Skincare
(1,  'Hidratues',         'skincare', 'hidratues',          'Produkte hidratuese për fytyrë dhe trup.',       'cat-hidratues.jpg'),
(2,  'Serume',            'skincare', 'serume',             'Serume të koncentruara me përbërës aktivë.',    'cat-serume.jpg'),
(3,  'Maska Fytyre',      'skincare', 'maska-fytyre',       'Maska argjili, teli dhe hidratuese.',           'cat-maska.jpg'),
(4,  'Krem Dielli',       'skincare', 'krem-dielli',        'Mbrojtje nga rrezet UV – SPF 30 deri 50+.',    'cat-spf.jpg'),
-- Makeup
(5,  'Foundation',        'makeup',   'foundation',         'Foundation me mbulim të lehtë deri të plotë.',  'cat-foundation.jpg'),
(6,  'Buzëkuq',           'makeup',   'buze-kuq',           'Ngjyra të ndezura dhe nude për buzë.',          'cat-lip.jpg'),
(7,  'Sytë',              'makeup',   'syte',               'Linere, hijezues dhe maskara.',                 'cat-eye.jpg'),
-- Hair
(8,  'Shampo',            'hair',     'shampo',             'Shampo për çdo lloj floku.',                    'cat-shampo.jpg'),
(9,  'Kondicionerë',      'hair',     'kondicionere',       'Kondicionerë dhe maska floku.',                 'cat-kond.jpg'),
(10, 'Serum Floku',       'hair',     'serum-floku',        'Serume mbrojtëse dhe rigjeneruese për flokë.',  'cat-serflok.jpg'),
-- Body
(11, 'Locion Trupi',      'body',     'locion-trupi',       'Losione hidratuese për trup.',                  'cat-locion.jpg'),
(12, 'Scrub Trupi',       'body',     'scrub-trupi',        'Eksfoliantë me sheqer, kripë dhe kafei.',       'cat-scrub.jpg');

-- PRODUCTS

INSERT INTO products (id, category_id, name, slug, description, price, stock, image, type, size) VALUES

-- SKINCARE – Hidratues (categ 1)
(1,  1, 'Krem Hidratues me Acid Hialuronik', 'krem-hidratues-ah',
    'Krem i lehtë që hidratohet thellë dhe mbush lëkurën me lagështi për 24 orë.',
    12.90, 80, 'prod-krem-ah.jpg', 'skincare', '50ml'),

(2,  1, 'Gel Hidratues Aqua Boost',          'gel-hidratues-aqua',
    'Gel i freskët pa yndyrë, ideal për lëkurën e kombinuar dhe të yndyrshme.',
    10.50, 60, 'prod-gel-aqua.jpg', 'skincare', '75ml'),

(3,  1, 'Krem Nate me Retinol',              'krem-nate-retinol',
    'Formula e pasur me retinol 0.3% rigjeneroit lëkurën gjatë natës.',
    18.00, 45, 'prod-retinol.jpg', 'skincare', '50ml'),

-- SKINCARE – Serume (categ 2)
(4,  2, 'Serum Vitamina C 15%',              'serum-vitamina-c',
    'Ndriçon lëkurën dhe redukton njollat e errëta. Pa parfum.',
    22.00, 55, 'prod-vitc.jpg', 'skincare', '30ml'),

(5,  2, 'Serum Niacinamide 10%',             'serum-niacinamide',
    'Minimizon poret, balanczon yndyrën dhe qetëson lëkurën e irrituar.',
    16.50, 70, 'prod-niac.jpg', 'skincare', '30ml'),

(6,  2, 'Serum Peptide Anti-Age',            'serum-peptide-anti-age',
    'Serume me peptide triple action kundër rrudhave dhe rënies së lëkurës.',
    28.00, 30, 'prod-peptide.jpg', 'skincare', '25ml'),

-- SKINCARE – Maska (categ 3)
(7,  3, 'Maskë Argjili Kaolin',              'maske-argjili-kaolin',
    'Pastron poret e zgjeruara dhe thith tepricën e yndyrës.',
    9.90, 90, 'prod-argjil.jpg', 'skincare', '75ml'),

(8,  3, 'Maskë Hidratuese me Aloe Vera',     'maske-aloe',
    'Maskë qetësuese dhe freskuese ideale pas ekspozimit ndaj diellit.',
    8.50, 100, 'prod-aloe-mask.jpg', 'skincare', '100ml'),

-- SKINCARE – Krem Dielli (categ 4)
(9,  4, 'Krem Dielli SPF 50+ Mineral',       'spf50-mineral',
    'Mbrojtje minerale me zinc oxide. Pa lë gjurmë të bardha.',
    14.00, 65, 'prod-spf50.jpg', 'skincare', '50ml'),

(10, 4, 'Fluid Dielli SPF 30 Tinted',        'spf30-tinted',
    'Fluid me ngjyrë të lehtë dhe mbrojtje SPF 30, ideal si bazë grimi.',
    13.50, 50, 'prod-spf30-tint.jpg', 'skincare', '40ml'),

-- MAKEUP – Foundation (categ 5)
(11, 5, 'Foundation Dewy Finish',            'foundation-dewy',
    'Mbulim mesatar me finish të lagështë dhe të shëndetshëm.',
    19.90, 40, 'prod-fnd-dewy.jpg', 'makeup', '30ml'),

(12, 5, 'Foundation Matte Full Coverage',    'foundation-matte',
    'Mbulim i plotë me finish mat që zgjat deri 16 orë.',
    21.00, 35, 'prod-fnd-matte.jpg', 'makeup', '30ml'),

(13, 5, 'BB Cream SPF 20',                   'bb-cream-spf20',
    'Krem BB 5-në-1: hidratim, mbrojtje, unifikim, iluminim dhe kujdes.',
    15.00, 55, 'prod-bb.jpg', 'makeup', '40ml'),

-- MAKEUP – Buzëkuq (categ 6)
(14, 6, 'Lipstick Satin Rouge',              'lipstick-satin-rouge',
    'Buzëkuq me teksturë satin, i qëndrueshëm dhe i pasur me moisturizues.',
    11.00, 75, 'prod-lip-rouge.jpg', 'makeup', '3.5g'),

(15, 6, 'Lip Gloss Nude Glow',               'lip-gloss-nude',
    'Gloss i shndritshëm me ngjyrë nude universale dhe vaj arganoje.',
    8.00, 90, 'prod-gloss-nude.jpg', 'makeup', '5ml'),

(16, 6, 'Lip Liner Longwear',                'lip-liner-longwear',
    'Konturues buze me pigment të lartë. Qëndron gjatë tërë ditës.',
    7.50, 60, 'prod-lipliner.jpg', 'makeup', '1.2g'),

-- MAKEUP – Sytë (categ 7)
(17, 7, 'Maskara Volume & Curl',             'maskara-volume-curl',
    'Maskara me furçë kurburuese. Jep vëllim dhe lakon qimet.',
    13.00, 50, 'prod-mascara.jpg', 'makeup', '10ml'),

(18, 7, 'Eyeliner Liquid Black',             'eyeliner-liquid',
    'Linere i lëngshëm me majë precize. Rezistent ndaj ujit.',
    9.00, 70, 'prod-liner.jpg', 'makeup', '1ml'),

(19, 7, 'Paleta Hijezues 12 Ngjyra',        'paleta-hijezues-12',
    'Paletë me 12 hije nude dhe smoky. Pigmentim i lartë.',
    24.00, 30, 'prod-paleta.jpg', 'makeup', '12g'),

-- HAIR – Shampo (categ 8)
(20, 8, 'Shampo Hidratuese me Keratin',      'shampo-keratin',
    'Pasurohet me keratin dhe acid amino. Zbut dhe ndriçon flokët.',
    11.90, 85, 'prod-shampo-ker.jpg', 'hair', '400ml'),

(21, 8, 'Shampo Kundër Rënies së Flokëve',  'shampo-anti-renies',
    'Forcon folëzat dhe redukton rënien nga doza e parë.',
    13.50, 60, 'prod-shampo-loss.jpg', 'hair', '250ml'),

-- HAIR – Kondicionerë (categ 9)
(22, 9, 'Maskë Floku me Vajra Arganoje',     'maske-floku-argan',
    'Rigjenerues intensiv 5 minuta. I lë flokët të butë dhe me shkëlqim.',
    14.00, 50, 'prod-mask-argan.jpg', 'hair', '300ml'),

(23, 9, 'Kondicioneri Leave-In me Biotin',   'leavein-biotin',
    'Kondicioneri pa shpëlarje. Mbron nga nxehtësia dhe nyjëzimi.',
    10.50, 65, 'prod-leavein.jpg', 'hair', '200ml'),

-- HAIR – Serum Floku (categ 10)
(24, 10,'Serum Anti-Frizz me Argan',         'serum-anti-frizz',
    'Kontrollon rrëmujën, jep shkëlqim dhe mbron nga temperatura 230°C.',
    16.00, 40, 'prod-ser-frizz.jpg', 'hair', '100ml'),

-- BODY – Locion (cat 11)
(25, 11,'Locion Trupi me Gjalp Shea',        'locion-shea',
    'Hidratim i thellë dhe i qëndrueshëm. Pa parfum sintetik.',
    12.00, 95, 'prod-locion-shea.jpg', 'body', '400ml'),

(26, 11,'Vaj Trupi Organik Kokosi',          'vaj-trupi-kokos',
    'Vaj i pastër organik 100% kokosi. Multifunksional: trup, flokë, buzë.',
    15.00, 70, 'prod-vaj-kok.jpg', 'body', '200ml'),

-- BODY – Scrub (cat 12)
(27, 12,'Scrub Sheqeri me Lavandë',          'scrub-sheqer-lavande',
    'Eksfoliant i butë me sheqer organik dhe vaj lavande.',
    10.00, 80, 'prod-scrub-lav.jpg', 'body', '250g'),

(28, 12,'Scrub Kafeje Kundër Celulitit',     'scrub-kafeje-celulit',
    'Kafeja aktivizon qarkullimin e gjakut dhe redukton dukjen e celulitit.',
    11.50, 75, 'prod-scrub-kaf.jpg', 'body', '200g');

-- COUPONS

INSERT INTO coupons (id, code, type, applies_to, discount_value, active, used, expires_at) VALUES
(1, 'WELCOME10',  'percent', 'all',      10.00, 1, 0, '2025-12-31'),
(2, 'SKIN20',     'percent', 'skincare', 20.00, 1, 0, '2025-09-30'),
(3, 'FLAT5',      'fixed',   'all',       5.00, 1, 1, '2025-06-30'),
(4, 'HAIR15',     'percent', 'hair',     15.00, 1, 0, '2025-11-30'),
(5, 'BODY10OFF',  'fixed',   'body',     10.00, 0, 1, '2025-03-01'),
(6, 'SUMMER26',   'percent', 'all',      25.00, 1, 0, '2025-08-31');

-- ORDERS

INSERT INTO orders (id, user_id, coupon_id, subtotal, discount, total, status, payment_method, payment_status,
                    shipping_name, shipping_email, shipping_address, shipping_city, shipping_phone) VALUES
(1, 2, 1, 45.40,  4.54, 40.86, 'delivered',  'stripe', 'paid',
    'Blerina Koci',    'blerina@gmail.com', 'Rruga Myslym Shyri 12', 'Tiranë',  '+355692222222'),

(2, 3, NULL, 28.00, 0.00, 28.00, 'shipped',  'paypal', 'paid',
    'Erjon Leka',      'erjon@gmail.com',   'Bulevardi Zogu I 8',    'Durrës',  '+355693333333'),

(3, 4, 2, 38.50, 7.70, 30.80, 'processing', 'stripe', 'paid',
    'Mirela Dervishi', 'mirela@gmail.com',  'Rruga Ismail Qemali 3', 'Vlorë',   '+355694444444'),

(4, 5, NULL, 22.00, 0.00, 22.00, 'pending',  'cash',   'unpaid',
    'Alban Shehu',     'alban@gmail.com',   'Rruga 28 Nentori 22',   'Shkodër', '+355695555555'),

(5, 7, 6, 63.90, 15.98, 47.92, 'delivered', 'stripe', 'paid',
    'Klajdi Muça',     'klajdi@gmail.com',  'Lagja Skenderbeg 7',    'Korçë',   '+355697777777'),

(6, 8, NULL, 19.90, 0.00, 19.90, 'cancelled', 'paypal', 'refunded',
    'Dea Prifti',      'dea@gmail.com',     'Rruga Ali Demi 15',     'Tiranë',  '+355698888888');

-- ORDER_ITEMS

INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES
-- Order 1 (Blerina)
(1, 4,  'Serum Vitamina C 15%',          22.00, 1),
(1, 7,  'Maskë Argjili Kaolin',           9.90, 1),
(1, 15, 'Lip Gloss Nude Glow',            8.00, 1),
(1, 16, 'Lip Liner Longwear',             7.50, 1),

-- Order 2 (Erjon)
(2, 6,  'Serum Peptide Anti-Age',        28.00, 1),

-- Order 3 (Mirela)
(3, 4,  'Serum Vitamina C 15%',          22.00, 1),
(3, 8,  'Maskë Hidratuese me Aloe Vera',  8.50, 1),
(3, 1,  'Krem Hidratues me Acid Hialuronik', 12.90, 1),

-- Order 4 (Alban)
(4, 4,  'Serum Vitamina C 15%',          22.00, 1),

-- Order 5 (Klajdi)
(5, 19, 'Paleta Hijezues 12 Ngjyra',     24.00, 1),
(5, 11, 'Foundation Dewy Finish',        19.90, 1),
(5, 17, 'Maskara Volume & Curl',         13.00, 1),
(5, 18, 'Eyeliner Liquid Black',          9.00, 1),

-- Order 6 (Dea – cancelled)
(6, 11, 'Foundation Dewy Finish',        19.90, 1);

-- CART

INSERT INTO cart (user_id, product_id, quantity) VALUES
(2, 9,  1),   -- Blerina: Krem Dielli SPF50
(2, 25, 2),   -- Blerina: Locion Shea x2
(3, 20, 1),   -- Erjon: Shampo Keratin
(4, 6,  1),   -- Mirela: Serum Peptide
(7, 27, 1),   -- Klajdi: Scrub Lavandë
(8, 14, 1),   -- Dea: Lipstick Satin Rouge
(8, 17, 1);   -- Dea: Maskara

-- POINTS_LOG

INSERT INTO points_log (user_id, points, reason) VALUES
(2,  50,  'Blerje - Order #1'),
(2,  100, 'Bonus regjistrim'),
(3,  80,  'Blerje - Order #2'),
(4,  100, 'Blerje - Order #3'),
(4,  100, 'Bonus promocional'),
(5,  50,  'Blerje - Order #4'),
(7,  200, 'Blerje - Order #5'),
(7,  120, 'Bonus VIP'),
(8,  10,  'Bonus regjistrim'),
(8, -10,  'Kthim porosie - Order #6');

-- SEARCH_HISTORY

INSERT INTO search_history (user_id, query) VALUES
(2,   'serum vitamina c'),
(2,   'krem dielle spf50'),
(3,   'peptide anti age'),
(3,   'shampo keratin'),
(4,   'maskë fytyre'),
(4,   'niacinamide serum'),
(5,   'spf 30'),
(7,   'paleta hijezues'),
(7,   'foundation matte'),
(7,   'maskara longwear'),
(8,   'lipstick nude'),
(8,   'lip gloss'),
(NULL,'krem hidratues'),   
(NULL,'foundation');     

-- 1. CATEGORIES
INSERT INTO categories (name, type, slug, description) VALUES
('Kujdes Fytyre',  'skincare', 'kujdes-fytyre',  'Produkte skincare për kujdesin e fytyrës'),
('Kujdes Trupi',   'skincare', 'kujdes-trupi',   'Produkte skincare për kujdesin e trupit'),
('Mbrojtje Diell', 'skincare', 'mbrojtje-diell', 'Krem diellor dhe mbrojtje UV'),
('Fytyrë',         'makeup',   'fytyra',         'Makeup për fytyrë'),
('Sy & Buzë',      'makeup',   'sy-buze',        'Makeup për sy dhe buzë'),
('Mjete',          'makeup',   'mjete',          'Furça, sfungjerë dhe mjete aplikimi');
 
-- 2. USERS

INSERT INTO users (name, email, password, role, phone, city, points) VALUES
('Admin Cleare', 'admin@cleare.al',   '$2y$12$placeholder', 'admin',    '+355 69 100 0001', 'Tiranë',  0),
('Arta Hoxha',   'arta@gmail.com',    '$2y$12$placeholder', 'customer', '+355 69 200 0001', 'Tiranë',  150),
('Blerina Kola', 'blerina@gmail.com', '$2y$12$placeholder', 'customer', '+355 69 200 0002', 'Durrës',  80),
('Mimoza Cara',  'mimoza@gmail.com',  '$2y$12$placeholder', 'customer', '+355 69 200 0003', 'Shkodër', 0);
 
-- 3. POINTS_LOG

INSERT INTO points_log (user_id, points, reason) VALUES
(2,  100, 'Porosi e parë'),
(2,   50, 'Bonus regjistrim'),
(3,   80, 'Porosi e parë');
 
-- 4. PRODUCTS

INSERT INTO products (category_id, name, slug, description, price, stock, type, size) VALUES
(1, 'Hydra Boost Moisturizer',        'hydra-boost-moisturizer',        'Hidratues i lehtë me acid hialuronik dhe aloe vera.',  24.99, 50, 'skincare', '50ml'),
(1, 'Vitamin C Brightening Serum',    'vitamin-c-brightening-serum',    'Serum me 15% Vitamina C. Ndriçon lëkurën.',           39.90, 35, 'skincare', '30ml'),
(1, 'Gentle Foam Cleanser',           'gentle-foam-cleanser',           'Pastrues me shkumë të butë pa sulfate.',               18.50, 60, 'skincare', '150ml'),
(3, 'SPF 50+ Daily Shield',           'spf-50-daily-shield',            'Krem diellor SPF 50+ me teksturë ujore.',             29.00, 45, 'skincare', '50ml'),
(1, 'Retinol Night Renewal Serum',    'retinol-night-renewal-serum',    'Serum nate me 0.3% Retinol.',                         44.90, 30, 'skincare', '30ml'),
(2, 'Barrier Repair Body Cream',      'barrier-repair-body-cream',      'Krem trupi me ceramide.',                             32.00, 40, 'skincare', '200ml'),
(4, 'Velvet Matte Foundation',        'velvet-matte-foundation',        'Fondatinë me mbulim të plotë dhe finish matte.',      27.50, 55, 'makeup',   '30ml'),
(5, 'Plump & Shine Lip Gloss',        'plump-shine-lip-gloss',          'Gloss buzësh me efekt plumping. 8 nuanca.',           14.90, 80, 'makeup',   '5ml'),
(5, 'Precision Liquid Eyeliner',      'precision-liquid-eyeliner',      'Eyeliner rezistent ndaj ujit. Qëndron 16 orë.',       16.00, 70, 'makeup',   '1.5ml');
 
-- 5. COUPONS

INSERT INTO coupons (code, type, applies_to, discount_value, active, expires_at) VALUES
('CLEARE10', 'percent', 'all',      10.00, 1, '2025-12-31'),
('NEWUSER',  'percent', 'all',      15.00, 1, '2025-12-31'),
('SKIN20',   'percent', 'skincare', 20.00, 1, '2025-06-30');
 
-- 6. ORDERS

INSERT INTO orders (user_id, coupon_id, subtotal, discount, total, status, payment_method, payment_status, shipping_name, shipping_email, shipping_address, shipping_city, shipping_phone) VALUES
(2, 1,    64.89, 6.49, 58.40, 'delivered',  'cash',   'paid', 'Arta Hoxha',   'arta@gmail.com',    'Rruga e Kavajës 12',    'Tiranë', '+355 69 200 0001'),
(3, NULL, 29.00, 0.00, 29.00, 'processing', 'stripe', 'paid', 'Blerina Kola', 'blerina@gmail.com', 'Bulevardi Epidamn 5',   'Durrës', '+355 69 200 0002');
 
-- 7. ORDER_ITEMS

INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES
(1, 1, 'Hydra Boost Moisturizer',     24.99, 1),
(1, 2, 'Vitamin C Brightening Serum', 39.90, 1),
(2, 4, 'SPF 50+ Daily Shield',        29.00, 1);
 
-- 8. COUPON_USAGE

INSERT INTO coupon_usage (user_id, coupon_id) VALUES
(2, 1);
 
-- 9. CART

INSERT INTO cart (user_id, product_id, quantity) VALUES
(4, 7, 1),
(4, 8, 2);
 
-- 10. SEARCH_HISTORY

INSERT INTO search_history (user_id, query) VALUES
(2,    'serum vitamin c'),
(3,    'krem diellor'),
(NULL, 'moisturizer'),
(4,    'lip gloss');