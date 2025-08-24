-- Voucher Management System Database Schema
-- Created: August 24, 2025
-- Description: Complete schema for voucher management system

-- Drop existing tables if they exist (for clean setup)
DROP TABLE IF EXISTS voucher_usage;
DROP TABLE IF EXISTS vouchers;
DROP TABLE IF EXISTS voucher_types;

-- Create voucher_types table for categorizing vouchers
CREATE TABLE voucher_types (
    id SERIAL PRIMARY KEY,
    type_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create main vouchers table
CREATE TABLE vouchers (
    id SERIAL PRIMARY KEY,
    voucher_code VARCHAR(50) NOT NULL UNIQUE,
    voucher_type VARCHAR(100) NOT NULL,
    voucher_name VARCHAR(255) NOT NULL,
    description TEXT,
    
    -- Stock and usage tracking
    initial_stock INTEGER NOT NULL CHECK (initial_stock > 0),
    current_stock INTEGER NOT NULL CHECK (current_stock >= 0),
    used_count INTEGER DEFAULT 0 CHECK (used_count >= 0),
    
    -- Financial constraints
    minimum_spend DECIMAL(10,2) DEFAULT 0.00 CHECK (minimum_spend >= 0),
    discount_type VARCHAR(20) CHECK (discount_type IN ('percentage', 'fixed_amount', 'free_shipping')),
    discount_value DECIMAL(10,2) CHECK (discount_value >= 0),
    maximum_discount DECIMAL(10,2) CHECK (maximum_discount >= 0),
    
    -- Date constraints
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    end_date TIMESTAMP NOT NULL,
    
    -- File storage
    picture_file_name VARCHAR(255),
    picture_file_path VARCHAR(500),
    picture_file_size INTEGER,
    picture_file_type VARCHAR(50),
    
    -- Terms and conditions
    terms_conditions TEXT,
    
    -- Status and metadata
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active', 'inactive', 'expired', 'exhausted')),
    created_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Constraints
    CONSTRAINT check_end_date_future CHECK (end_date > start_date),
    CONSTRAINT check_stock_consistency CHECK (current_stock <= initial_stock),
    CONSTRAINT check_usage_consistency CHECK (used_count = initial_stock - current_stock)
);

-- Create voucher_usage table to track usage history
CREATE TABLE voucher_usage (
    id SERIAL PRIMARY KEY,
    voucher_id INTEGER NOT NULL REFERENCES vouchers(id) ON DELETE CASCADE,
    user_id VARCHAR(100),
    user_email VARCHAR(255),
    order_id VARCHAR(100),
    usage_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    discount_applied DECIMAL(10,2),
    order_total DECIMAL(10,2),
    savings_amount DECIMAL(10,2),
    status VARCHAR(20) DEFAULT 'used' CHECK (status IN ('used', 'refunded', 'cancelled')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX idx_vouchers_code ON vouchers(voucher_code);
CREATE INDEX idx_vouchers_type ON vouchers(voucher_type);
CREATE INDEX idx_vouchers_status ON vouchers(status);
CREATE INDEX idx_vouchers_end_date ON vouchers(end_date);
CREATE INDEX idx_voucher_usage_voucher_id ON voucher_usage(voucher_id);
CREATE INDEX idx_voucher_usage_user_id ON voucher_usage(user_id);
CREATE INDEX idx_voucher_usage_date ON voucher_usage(usage_date);

-- Insert default voucher types
INSERT INTO voucher_types (type_name, description) VALUES
('Percentage Discount', 'Vouchers that provide a percentage discount on total order'),
('Fixed Amount Discount', 'Vouchers that provide a fixed amount discount'),
('Free Shipping', 'Vouchers that provide free shipping on orders'),
('Buy One Get One', 'Special promotional vouchers for BOGO offers'),
('Category Specific', 'Vouchers applicable to specific product categories'),
('New Customer', 'Welcome vouchers for new customers'),
('Loyalty Reward', 'Vouchers for loyal customers'),
('Seasonal Promotion', 'Time-limited seasonal promotional vouchers');

-- Create a function to automatically update updated_at timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create triggers to automatically update updated_at
CREATE TRIGGER update_vouchers_updated_at 
    BEFORE UPDATE ON vouchers 
    FOR EACH ROW 
    EXECUTE FUNCTION update_updated_at_column();

CREATE TRIGGER update_voucher_types_updated_at 
    BEFORE UPDATE ON voucher_types 
    FOR EACH ROW 
    EXECUTE FUNCTION update_updated_at_column();

-- Create a function to automatically update voucher status based on conditions
CREATE OR REPLACE FUNCTION update_voucher_status()
RETURNS TRIGGER AS $$
BEGIN
    -- Check if voucher is expired
    IF NEW.end_date <= CURRENT_TIMESTAMP THEN
        NEW.status = 'expired';
    -- Check if voucher is exhausted
    ELSIF NEW.current_stock <= 0 THEN
        NEW.status = 'exhausted';
    -- Otherwise keep it active if it was active
    ELSIF OLD.status = 'active' AND NEW.end_date > CURRENT_TIMESTAMP AND NEW.current_stock > 0 THEN
        NEW.status = 'active';
    END IF;
    
    RETURN NEW;
END;
$$ language 'plpgsql';

-- Create trigger to automatically update voucher status
CREATE TRIGGER update_voucher_status_trigger
    BEFORE UPDATE ON vouchers
    FOR EACH ROW
    EXECUTE FUNCTION update_voucher_status();

-- Create a view for active vouchers
CREATE VIEW active_vouchers AS
SELECT 
    v.*,
    vt.description as type_description,
    CASE 
        WHEN v.end_date <= CURRENT_TIMESTAMP THEN 'Expired'
        WHEN v.current_stock <= 0 THEN 'Out of Stock'
        ELSE 'Available'
    END as availability_status,
    ROUND((v.used_count::DECIMAL / v.initial_stock * 100), 2) as usage_percentage
FROM vouchers v
LEFT JOIN voucher_types vt ON v.voucher_type = vt.type_name
WHERE v.status = 'active' 
    AND v.end_date > CURRENT_TIMESTAMP 
    AND v.current_stock > 0;

-- Create a view for voucher usage statistics
CREATE VIEW voucher_usage_stats AS
SELECT 
    v.id,
    v.voucher_code,
    v.voucher_name,
    v.initial_stock,
    v.current_stock,
    v.used_count,
    COUNT(vu.id) as total_uses,
    COALESCE(SUM(vu.discount_applied), 0) as total_discount_given,
    COALESCE(SUM(vu.savings_amount), 0) as total_savings_provided,
    ROUND((v.used_count::DECIMAL / v.initial_stock * 100), 2) as usage_percentage
FROM vouchers v
LEFT JOIN voucher_usage vu ON v.id = vu.voucher_id AND vu.status = 'used'
GROUP BY v.id, v.voucher_code, v.voucher_name, v.initial_stock, v.current_stock, v.used_count;

-- Sample data insertion (optional - remove if not needed)
INSERT INTO vouchers (
    voucher_code, voucher_type, voucher_name, description,
    initial_stock, current_stock, minimum_spend,
    discount_type, discount_value, maximum_discount,
    end_date, terms_conditions, created_by
) VALUES 
(
    'WELCOME2025', 'New Customer', 'Welcome Discount', 
    'Special discount for new customers',
    100, 100, 500.00,
    'percentage', 15.00, 200.00,
    '2025-12-31 23:59:59',
    'Valid for first-time customers only. Cannot be combined with other offers.',
    'admin'
),
(
    'FREESHIP50', 'Free Shipping', 'Free Shipping Voucher',
    'Free shipping on orders above minimum spend',
    50, 50, 1000.00,
    'free_shipping', 0.00, 0.00,
    '2025-09-30 23:59:59',
    'Valid for orders above ₱1000. Applicable to Metro Manila only.',
    'admin'
),
(
    'SAVE100', 'Fixed Amount Discount', '₱100 Off Voucher',
    'Get ₱100 off on your purchase',
    200, 200, 800.00,
    'fixed_amount', 100.00, 100.00,
    '2025-10-31 23:59:59',
    'Minimum spend of ₱800 required. Valid for all products.',
    'admin'
);

-- Grant permissions (adjust as needed for your user)
-- GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO your_app_user;
-- GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO your_app_user;

-- Comments for documentation
COMMENT ON TABLE vouchers IS 'Main table storing all voucher information including codes, discounts, and constraints';
COMMENT ON TABLE voucher_types IS 'Reference table for categorizing different types of vouchers';
COMMENT ON TABLE voucher_usage IS 'Audit table tracking all voucher usage instances';
COMMENT ON COLUMN vouchers.voucher_code IS 'Unique code that customers use to redeem the voucher';
COMMENT ON COLUMN vouchers.minimum_spend IS 'Minimum order amount required to use this voucher';
COMMENT ON COLUMN vouchers.picture_file_path IS 'File system path or URL to the voucher image/document';
COMMENT ON COLUMN vouchers.current_stock IS 'Current available quantity of this voucher';
COMMENT ON COLUMN vouchers.initial_stock IS 'Original quantity when voucher was created';
