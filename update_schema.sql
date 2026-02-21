-- Add role and is_active columns to users table
ALTER TABLE users ADD COLUMN role ENUM('admin', 'user') DEFAULT 'user';
ALTER TABLE users ADD COLUMN is_active TINYINT(1) DEFAULT 1;

-- Set first user as admin (optional, for existing data)
UPDATE users SET role = 'admin', is_active = 1 LIMIT 1;
