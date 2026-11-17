-- PORTFOLIO DEMO ONLY: original customer and transaction records removed; all published users are fictional example.test identities. Never use demo accounts publicly.
CREATE DATABASE IF NOT EXISTS ecosprout CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecosprout;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS workshop_registrations;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS queries;
DROP TABLE IF EXISTS blog_posts;
DROP TABLE IF EXISTS workshops;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS plants;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer','staff','admin') NOT NULL DEFAULT 'customer',
    phone VARCHAR(40),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE plants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(140) NOT NULL,
    category ENUM('indoor','outdoor','ornamental','edible') NOT NULL,
    description TEXT,
    botanical_name VARCHAR(180),
    care_instructions TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock_qty INT NOT NULL DEFAULT 0,
    image_path VARCHAR(600),
    created_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_plants_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    price_range VARCHAR(80),
    image_path VARCHAR(600)
);

CREATE TABLE workshops (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(160) NOT NULL,
    description TEXT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(180) NOT NULL,
    max_participants INT NOT NULL DEFAULT 20,
    created_by INT NULL,
    CONSTRAINT fk_workshops_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_customer FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    plant_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_items_plant FOREIGN KEY (plant_id) REFERENCES plants(id) ON DELETE CASCADE
);

CREATE TABLE workshop_registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    workshop_id INT NOT NULL,
    customer_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_workshop_customer (workshop_id, customer_id),
    CONSTRAINT fk_reg_workshop FOREIGN KEY (workshop_id) REFERENCES workshops(id) ON DELETE CASCADE,
    CONSTRAINT fk_reg_customer FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL,
    subject VARCHAR(180) NOT NULL,
    message TEXT NOT NULL,
    response TEXT NULL,
    status ENUM('open','responded') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_queries_customer FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(80) NOT NULL,
    image_path VARCHAR(600),
    author_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_blog_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO users (id, name, email, password_hash, role, phone) VALUES
(1, 'Demo Administrator', 'admin@example.test', '$2b$12$jcozkshMVhRR/dCKQ1fnHOwxOGahGRbVwUgS79ILIFB2jp7dYIuZ2', 'admin', '0000000000'),
(2, 'Demo Nursery Staff', 'staff@example.test', '$2b$12$H1sZ.bCYrMNk/3HKYcxRRuJBX/VqwImFkHtuXFi.OBinHNi4W4dI6', 'staff', '0000000000'),
(3, 'Demo Customer', 'customer@example.test', '$2b$12$Q.GxL65cUi6eB.sus1g.Ue1i1kOlGs5bSq9x1KERXvPhaW7oWPR3K', 'customer', '0000000000');


INSERT INTO plants (id, name, category, description, botanical_name, care_instructions, price, stock_qty, image_path, created_by) VALUES
(1, 'Monstera Deliciosa', 'indoor', 'Large split leaves for bright living rooms.', 'Monstera deliciosa', 'Bright indirect light; water when top soil dries.', 3200.00, 18, 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?auto=format&fit=crop&w=900&q=80', 2),
(2, 'Snake Plant', 'indoor', 'Hardy air-purifying plant for bedrooms and offices.', 'Dracaena trifasciata', 'Low to bright light; water sparingly.', 1800.00, 28, 'https://images.unsplash.com/photo-1593691509543-c55fb32d8de5?auto=format&fit=crop&w=900&q=80', 2),
(3, 'Peace Lily', 'indoor', 'Glossy foliage with elegant white blooms.', 'Spathiphyllum wallisii', 'Keep soil lightly moist; avoid direct sun.', 2200.00, 20, 'https://images.unsplash.com/photo-1598880940080-ff9a29891b85?auto=format&fit=crop&w=900&q=80', 2),
(4, 'Areca Palm', 'indoor', 'Soft tropical palm ideal for corners.', 'Dypsis lutescens', 'Bright filtered light; mist during dry weather.', 4500.00, 12, 'https://images.unsplash.com/photo-1632380211596-b96123618ca8?auto=format&fit=crop&w=900&q=80', 2),
(5, 'Jasmine', 'outdoor', 'Fragrant flowering shrub for sunny gardens.', 'Jasminum sambac', 'Full sun; prune after flowering.', 950.00, 35, 'https://i.herbalreality.com/wp-content/uploads/2025/09/29155554/Jasmine-flowering-Jasminum-grandiflorum-1300x975.jpg', 2),
(6, 'Bougainvillea', 'outdoor', 'Vibrant flowering climber suited to warm climates.', 'Bougainvillea glabra', 'Full sun; water deeply but infrequently.', 1250.00, 24, 'https://bonsaimirai.com/sites/default/files/images/species/species-featured-photo/Zanes-Trees-bougainvillea.jpg', 2),
(7, 'Ixora', 'outdoor', 'Compact shrub with clusters of bright flowers.', 'Ixora coccinea', 'Full sun; feed monthly for blooms.', 850.00, 32, 'https://images.unsplash.com/photo-1591857177580-dc82b9ac4e1e?auto=format&fit=crop&w=900&q=80', 2),
(8, 'Anthurium', 'ornamental', 'Glossy heart-shaped flowers for decorative displays.', 'Anthurium andraeanum', 'Humidity and indirect light; avoid overwatering.', 2750.00, 16, 'https://cdn.mos.cms.futurecdn.net/v2/t:0,l:200,cw:1200,ch:1200,q:80,w:1200/JhYRFDeFNKgUsjkT2fcu6a.jpg', 2),
(9, 'Orchid', 'ornamental', 'Long-lasting blooms for indoor arrangements.', 'Phalaenopsis', 'Filtered light; water roots weekly.', 3800.00, 14, 'https://hips.hearstapps.com/hmg-prod/images/how-to-care-for-orchids-veranda-68b71af77e15f.jpg?crop=0.668xw:1.00xh;0.167xw,0&resize=1200*', 2),
(10, 'Thai Basil', 'edible', 'Aromatic culinary herb for pots and kitchen gardens.', 'Ocimum basilicum var. thyrsiflora', 'Morning sun; pinch tips to encourage growth.', 650.00, 40, 'https://images.unsplash.com/photo-1618375569909-3c8616cf7733?auto=format&fit=crop&w=900&q=80', 2),
(11, 'Cherry Tomato', 'edible', 'Productive edible plant for sunny balconies.', 'Solanum lycopersicum var. cerasiforme', 'Full sun; support vines and water consistently.', 780.00, 30, 'https://images.unsplash.com/photo-1592841200221-a6898f307baa?auto=format&fit=crop&w=900&q=80', 2),
(12, 'Mint', 'edible', 'Fast-growing herb for drinks and cooking.', 'Mentha', 'Partial sun; keep soil moist and trim regularly.', 520.00, 42, 'https://images.unsplash.com/photo-1628556270448-4d4e4148e1b1?auto=format&fit=crop&w=900&q=80', 2);


INSERT INTO services (title, description, price_range, image_path) VALUES
('Home Garden Design', 'Custom plant selection, soil preparation, and layout planning for homes in Kegalle.', 'LKR 15,000 - 60,000', 'https://images.unsplash.com/photo-1558904541-efa843a96f01?auto=format&fit=crop&w=900&q=80'),
('Balcony Plant Setup', 'Compact edible and ornamental garden setups for apartments and small patios.', 'LKR 8,000 - 25,000', 'https://images.unsplash.com/photo-1598902108854-10e335adac99?auto=format&fit=crop&w=900&q=80'),
('Monthly Garden Maintenance', 'Pruning, fertilizing, pest checks, and plant replacement recommendations.', 'From LKR 5,000/month', 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=900&q=80');


INSERT INTO workshops (id, title, description, date, time, location, max_participants, created_by) VALUES
(1, 'Balcony Gardening Basics', 'Learn container choices, light matching, and compact plant care.', '2026-07-12', '09:30:00', 'EcoSprout Nursery, Kegalle', 25, 2),
(2, 'Composting for Home Gardens', 'Hands-on composting methods using kitchen and garden waste.', '2026-08-02', '10:00:00', 'EcoSprout Training Area', 20, 2),
(3, 'Edible Herb Garden Day', 'Create a starter herb planter with basil, mint, and coriander.', '2026-09-06', '14:00:00', 'EcoSprout Nursery, Kegalle', 18, 2);


INSERT INTO blog_posts (title, content, category, image_path, author_id) VALUES
('Five Indoor Plants That Forgive Busy Owners', 'Snake plants, pothos, peace lilies, ZZ plants, and monsteras can handle imperfect routines when matched with the right light and watering rhythm.', 'Plant Care', 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?auto=format&fit=crop&w=900&q=80', 2),
('Preparing Soil Before Monsoon Planting', 'Healthy soil drains well but still holds nutrients. Mix compost into beds, remove compacted patches, and mulch after planting to protect roots.', 'Seasonal Guide', 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=900&q=80', 2),
('DIY Balcony Herb Garden', 'Choose shallow planters with drainage, place herbs where they get morning sun, and harvest tips often so plants grow bushier.', 'DIY Gardening', 'https://images.unsplash.com/photo-1515150144380-bca9f1650ed9?auto=format&fit=crop&w=900&q=80', 2);

