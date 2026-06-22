-- Seed data for Asian Cargo
-- Run AFTER schema.sql

-- Default admin login: username = admin | password = Admin@123
-- IMPORTANT: change this password after first login (Admin Panel > My Profile)
INSERT INTO admins (full_name, username, email, password_hash, role) VALUES
('Site Administrator', 'admin', 'admin@asiancargo.pk', '$2y$10$wZh4.n6jPXLYQwLQeYIv9ObNS0fczj.hWFY44ZtVF1m4amVeGe30u', 'super_admin');

-- Branches
INSERT INTO branches (branch_name, city, address, phone, email, is_head_office, display_order) VALUES
('Asian Cargo - Head Office', 'Lahore', 'Main Boulevard, Gulberg III, Lahore, Pakistan', '+92 42 1234 5678', 'lahore@asiancargo.pk', 1, 1),
('Asian Cargo - Karachi Branch', 'Karachi', 'Shahrah-e-Faisal, Karachi, Pakistan', '+92 21 1234 5678', 'karachi@asiancargo.pk', 0, 2),
('Asian Cargo - Islamabad Branch', 'Islamabad', 'Blue Area, Islamabad, Pakistan', '+92 51 1234 5678', 'islamabad@asiancargo.pk', 0, 3),
('Asian Cargo - Faisalabad Branch', 'Faisalabad', 'D Ground, Faisalabad, Pakistan', '+92 41 1234 5678', 'faisalabad@asiancargo.pk', 0, 4);

-- Site settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'Asian Cargo'),
('site_tagline', 'Your Trusted Partner in Global Logistics'),
('contact_phone', '+92 42 1234 5678'),
('contact_whatsapp', '923001234567'),
('contact_email', 'info@asiancargo.pk'),
('office_hours', 'Mon - Sat: 9:00 AM - 7:00 PM'),
('facebook_url', 'https://facebook.com'),
('instagram_url', 'https://instagram.com'),
('linkedin_url', 'https://linkedin.com'),
('years_experience', '15'),
('shipments_delivered', '50000'),
('countries_served', '120'),
('happy_clients', '8000');

-- Sample shipments so the tracking page has something to demo with
INSERT INTO shipments (tracking_number, sender_name, sender_phone, origin_city, origin_country, receiver_name, receiver_phone, destination_city, destination_country, service_type, package_description, weight_kg, pieces, status, estimated_delivery, created_by) VALUES
('AC2026LHE0001', 'Ahmed Raza', '+92 300 1112233', 'Lahore', 'Pakistan', 'John Smith', '+44 7700 900123', 'London', 'United Kingdom', 'air_freight', 'Textile Samples', 12.5, 2, 'in_transit', '2026-06-25', 1),
('AC2026KHI0002', 'Fatima Traders', '+92 321 4445566', 'Karachi', 'Pakistan', 'Mohammed Al Farsi', '+971 50 1234567', 'Dubai', 'United Arab Emirates', 'ocean_freight', 'Leather Goods - 5 Cartons', 240.0, 5, 'customs_clearance', '2026-07-02', 1),
('AC2026ISB0003', 'Bilal Khan', '+92 333 7778899', 'Islamabad', 'Pakistan', 'Sarah Johnson', '+1 212 5551234', 'New York', 'United States', 'express_courier', 'Documents', 0.5, 1, 'delivered', '2026-06-15', 1);

INSERT INTO tracking_events (shipment_id, status, location, remarks, event_time, created_by) VALUES
(1, 'booked', 'Lahore, Pakistan', 'Shipment booked and confirmed', '2026-06-18 09:00:00', 1),
(1, 'picked_up', 'Lahore, Pakistan', 'Parcel collected from sender', '2026-06-18 14:30:00', 1),
(1, 'in_transit', 'Lahore International Airport', 'Departed on flight to London', '2026-06-19 02:15:00', 1),

(2, 'booked', 'Karachi, Pakistan', 'Shipment booked and confirmed', '2026-06-10 10:00:00', 1),
(2, 'picked_up', 'Karachi, Pakistan', 'Parcel collected from sender', '2026-06-11 11:00:00', 1),
(2, 'in_transit', 'Karachi Port', 'Loaded on vessel to Jebel Ali', '2026-06-13 08:00:00', 1),
(2, 'arrived_hub', 'Jebel Ali Port, UAE', 'Arrived at destination port', '2026-06-17 16:00:00', 1),
(2, 'customs_clearance', 'Dubai, UAE', 'Held for customs documentation review', '2026-06-18 10:00:00', 1),

(3, 'booked', 'Islamabad, Pakistan', 'Shipment booked and confirmed', '2026-06-12 09:00:00', 1),
(3, 'picked_up', 'Islamabad, Pakistan', 'Parcel collected from sender', '2026-06-12 13:00:00', 1),
(3, 'in_transit', 'Islamabad Airport', 'Departed on flight to New York', '2026-06-13 01:00:00', 1),
(3, 'arrived_hub', 'JFK Airport, New York', 'Arrived at destination hub', '2026-06-14 09:00:00', 1),
(3, 'out_for_delivery', 'New York, USA', 'Out for delivery with courier', '2026-06-15 08:00:00', 1),
(3, 'delivered', 'New York, USA', 'Delivered and signed by receiver', '2026-06-15 14:20:00', 1);
