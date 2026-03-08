-- deepakPotfolio  //// this is a databases name-

-- Admin login table 
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add index to email column for faster lookups
CREATE INDEX idx_admins_email ON admins(email);

-- Insert default admin user
-- Default credentials: Email: admin@deepakkumar.com, Password: password
INSERT INTO admins (email, password, status) VALUES (
    'admin@deepakkumar.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Hash for 'password'
    'active'
) ON DUPLICATE KEY UPDATE 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    status = 'active';


    -- profile image table 

    CREATE TABLE profile_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP
);

-- portfolio projects table

CREATE TABLE portfolio_projects (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,

    category ENUM(
        'development',
        'application',
        'photoshop',
        'video',
        'design'
    ) NOT NULL,

    project_type ENUM('image', 'video') DEFAULT 'image',

    thumbnail VARCHAR(255) NOT NULL,

    video_url VARCHAR(500) DEFAULT NULL,
    video_file VARCHAR(255) DEFAULT NULL,
    video_poster VARCHAR(255) DEFAULT NULL,

    tags VARCHAR(255) DEFAULT NULL,

    likes INT(11) DEFAULT 0,
    is_featured TINYINT(1) DEFAULT 0,

    status ENUM('active', 'inactive') DEFAULT 'active',

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL 
        DEFAULT CURRENT_TIMESTAMP 
        ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id)
) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_general_ci;


ALTER TABLE portfolio_projects
ADD COLUMN video_file VARCHAR(255) NULL AFTER video_url;


-- Table for Experience
CREATE TABLE experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_title VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    duration VARCHAR(100) NOT NULL,
    rating DECIMAL(3,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Education
CREATE TABLE educations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    degree_course VARCHAR(255) NOT NULL,
    institution VARCHAR(255) NOT NULL,
    duration VARCHAR(100) NOT NULL,
    grade_score VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Skills
CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    skill_name VARCHAR(255) NOT NULL,
    rating DECIMAL(2,1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Achievements
CREATE TABLE achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    organization VARCHAR(255) NOT NULL,
    date_achieved VARCHAR(100) NOT NULL,
    rating_score VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Pricing Packages

CREATE TABLE pricing_packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_name VARCHAR(50) NOT NULL,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    price VARCHAR(50),
    description TEXT,
    features TEXT,
    is_recommended BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Table for Blogs


-- Table for Blogs (already exists in schema, but here's the complete structure)
CREATE TABLE IF NOT EXISTS blogs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255) DEFAULT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    category VARCHAR(100) NOT NULL,
    author VARCHAR(255) NOT NULL DEFAULT 'Deepak Kumar',
    thumbnail VARCHAR(255) DEFAULT NULL,
    video_url VARCHAR(255) DEFAULT NULL,
    banner_image VARCHAR(255) DEFAULT NULL,
    short_description TEXT,
    full_description LONGTEXT NOT NULL, -- This will store the HTML content from CKEditor
    read_time VARCHAR(50) DEFAULT '2 min read',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- contact messages table

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone_number VARCHAR(20),
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Add status column to contact_messages table

ALTER TABLE contact_messages
ADD COLUMN status ENUM(
  'new',
  'in-progress',
  'completed',
  'hold',
  'rejected'
) DEFAULT 'new' AFTER message;

-- Table for Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Awesome Clients


CREATE TABLE awesome_clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(255) NOT NULL,
    client_name VARCHAR(255) NOT NULL,
    logo_path VARCHAR(255),
    testimonial_text TEXT,
    rating DECIMAL(3,2),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);