<?php
// Start session and check if user is authenticated
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    // Redirect to login page if not authenticated
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Center - Portfolio Managemendfght</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- CKEditor 5 classic build from CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

</head>

<body style="background-color: #ffffff;">
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-cog"></i> Admin Panel</h3>
        </div>
        <div class="sidebar-menu">
            <div class="menu-item active" data-section="dashboard">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </div>
            <div class="menu-item" data-section="services">
                <i class="fas fa-concierge-bell"></i>
                <span>Services</span>
            </div>
            <div class="menu-item" data-section="hero">
                <i class="fas fa-user"></i>
                <span>Hero & Profile</span>
            </div>
            <div class="menu-item" data-section="portfolio">
                <i class="fas fa-folder"></i>
                <span>Portfolio</span>
            </div>
            <div class="menu-item" data-section="resume">
                <i class="fas fa-file-alt"></i>
                <span>Resume & Skills</span>
            </div>
            <div class="menu-item" data-section="pricing">
                <i class="fas fa-tag"></i>
                <span>Pricing</span>
            </div>
            <div class="menu-item" data-section="blog">
                <i class="fas fa-blog"></i>
                <span>Blog</span>
            </div>

            <div class="menu-item" data-section="testimonials">
                <i class="fas fa-blog"></i>
                <span>Awesome Clients</span>
            </div>

            <div class="menu-item" data-section="leads">
                <i class="fas fa-users"></i>
                <span>Lead Management</span>
            </div>
            <div class="menu-item" data-section="settings">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div class="header-title">Admin Control Center</div>
            <button class="btn btn-outline" id="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </div>

        <!-- Dashboard Section -->
        <div class="content-section active" id="dashboard">
            <h2 class="section-title">Dashboard Overview</h2>
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-folder text-primary"></i>
                        <div>
                            <div class="card-title">Projects</div>
                            <div class="card-value">24</div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-blog text-success"></i>
                        <div>
                            <div class="card-title">Blogs</div>
                            <div class="card-value">12</div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users text-warning"></i>
                        <div>
                            <div class="card-title">Leads</div>
                            <div class="card-value">8</div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-comments text-info"></i>
                        <div>
                            <div class="card-title">Testimonials</div>
                            <div class="card-value">15</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h3>Recent Activity</h3>
                <p>No recent activity to display.</p>
            </div>
        </div>

        <!-- Services Section -->
        <div class="content-section" id="services">
            <h2 class="section-title">What I Do - Services</h2>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3>Video Editing</h3>
                    <p>Professional video editing services including cutting, trimming, transitions, color correction,
                        and audio enhancement to create compelling visual narratives.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3>Motion Graphics & Animation</h3>
                    <p>Creative motion graphics and animation services for intros, outros, lower thirds, and animated
                        elements to enhance your video content.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <h3>Reels and Shorts Editing</h3>
                    <p>Engaging short-form content creation and editing optimized for social media platforms like
                        Instagram Reels, TikTok, and YouTube Shorts.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <h3>YouTube Video Editing</h3>
                    <p>Complete YouTube video editing services including intros, outros, end screens, annotations, and
                        optimization for maximum engagement.</p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3>Promotional & Social Media Videos</h3>
                    <p>Strategic promotional video creation tailored for social media campaigns, product launches, and
                        brand awareness initiatives.</p>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-primary" id="update-services-btn">Update Services</button>
            </div>
        </div>

        <!-- Hero & Profile Section -->
        <div class="content-section" id="hero">
            <h2 class="section-title">Profile Image & Logo Management</h2>
            <form id="hero-form">

                <div class="form-group">
                    <label for="logo-image">Logo Image</label>
                    <input type="file" id="logo-image" name="logo_image" class="form-control" accept="image/*">
                    <div class="mt-2">
                        <img id="logo-preview" src="AdminApi/img/logo-06.png" alt="Logo Preview"
                            style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                </div>

                <div class="form-group">
                    <label for="profile-image">Profile Image</label>
                    <input type="file" id="profile-image" name="profile_image" class="form-control" accept="image/*">
                    <div class="mt-2">
                        <img id="profile-preview" src="AdminApi/img/banner-01.png" alt="Profile Preview"
                            style="width: 700px; height: 960px; object-fit: cover;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>

        <!-- Portfolio Section -->
        <div class="content-section" id="portfolio">
            <h2 class="section-title">Portfolio Management</h2>
            <div class="form-group">
                <label for="portfolio-category">Category</label>
                <select id="portfolio-category" class="form-control">
                    <option value="all">All Categories</option>
                    <option value="photoshop">Photoshop</option>
                    <option value="video" selected>Video Editing</option>
                    <option value="motion-graphics">Motion Graphics</option>
                    <option value="animation">Animation</option>
                    <option value="strategy">Strategy</option>
                </select>
            </div>

            <div class="portfolio-filters">

                <div class="form-group">
                    <label>Status</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" value="active" checked> Active
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" value="inactive"> Inactive
                        </label>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Video URL</th>
                            <th>Tags</th>
                            <th>Likes</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="portfolio-projects-table">
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <button class="btn btn-success" id="add-project-btn"><i class="fas fa-plus"></i> Add New
                    Project</button>
            </div>

            <!-- Add Project Modal -->
            <div id="add-project-modal" class="modal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Add New Project</h3>
                        <span class="close" id="close-project-modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form id="project-form">
                            <div class="form-group">
                                <label for="project-title">Project Title *</label>
                                <input type="text" id="project-title" class="form-control" required
                                    placeholder="Enter project title">
                            </div>

                            <div class="form-group">
                                <label for="project-description">Description</label>
                                <textarea id="project-description" class="form-control" rows="3"
                                    placeholder="Enter project description"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="project-category">Category *</label>
                                <select id="project-category" class="form-control" required>
                                    <option value="">Select Category</option>
                                    <option value="photoshop">Photoshop</option>
                                    <option value="video">Video Editing</option>
                                    <option value="motion-graphics">Motion Graphics</option>
                                    <option value="animation">Animation</option>
                                    <option value="strategy">Strategy</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="project-type">Project Type *</label>
                                <select id="project-type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="image">Image</option>
                                    <option value="video">Video</option>
                                </select>
                            </div>

                            <div class="form-group" id="video-url-group" style="display: none;">
                                <label for="video-url">Video URL</label>
                                <input type="url" id="video-url" class="form-control"
                                    placeholder="Enter video URL (MP4, MOV, etc.)">
                                <small class="form-text">For video projects, enter the URL to the video file</small>
                            </div>

                            <div class="form-group" id="video-description-group" style="display: none;">
                                <label for="video-description">Video Description</label>
                                <input type="text" id="video-description" class="form-control"
                                    placeholder="Enter video description">
                                <small class="form-text">Brief description of the video content</small>
                            </div>
                            <div class="form-group" id="video-upload-group">
                                <label for="video-file">Upload Video</label>
                                <input type="file" id="video-file" class="form-control"
                                    accept="video/mp4,video/mov,video/avi,video/webm">
                                <small class="form-text">Allowed formats: MP4, MOV, AVI, WebM (Max size: 500MB) </small>
                            </div>

                            <div class="form-group">
                                <label for="project-image">Project Thumbnail *</label>
                                <input type="file" id="project-image" class="form-control" accept="image/*" required>
                                <div class="mt-2">
                                    <img id="project-preview" src="https://via.placeholder.com/300x200"
                                        alt="Project Preview"
                                        style="width: 100%; max-width: 300px; height: 200px; object-fit: cover; display: none;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="project-tags">Tags (comma separated)</label>
                                <input type="text" id="project-tags" class="form-control"
                                    placeholder="e.g., After Effects, Premiere Pro, Motion Design">
                            </div>

                            <div class="form-group">
                                <label for="project-status">Status</label>
                                <select id="project-status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="project-featured"> Featured Project
                                </label>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" id="cancel-project">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Project</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <!-- Resume & Skills Section -->
        <div class="content-section" id="resume">
            <h2 class="section-title">Resume & Skills Management</h2>

            <h3>Experience Timeline</h3>
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Company</th>
                        <th>Duration</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Senior Video Editor</td>
                        <td>Creative Studios</td>
                        <td>2020 - Present</td>
                        <td>4.8/5</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Motion Designer</td>
                        <td>Design Agency</td>
                        <td>2018 - 2020</td>
                        <td>4.5/5</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-3">
                <button class="btn btn-success" id="add-experience-btn"><i class="fas fa-plus"></i> Add
                    Experience</button>
                <!-- <button class="btn btn-primary" id="add-education-btn"><i class="fas fa-plus"></i> Add Education</button> -->
                <!-- <button class="btn btn-warning" id="add-achievement-btn"><i class="fas fa-plus"></i> Add Achievement</button> -->
            </div>

            <h3 class="mt-4">Education</h3>
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>Degree/Course</th>
                        <th>Institution</th>
                        <th>Duration</th>
                        <th>Grade/Score</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td>Video Editing</td>
                        <td>4.8/5.0</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Motion Graphics</td>
                        <td>4.5/5.0</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>Graphic Design</td>
                        <td>4.3/5.0</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr> -->

                </tbody>
            </table>
            <div class="mt-3">
                <button class="btn btn-info" id="add-education-btn"><i class="fas fa-plus"></i> Add Education</button>
            </div>

            <h3 class="mt-4">Skills</h3>
            <table class="table-responsive">
                <thead>
                    <tr>


                        <th>Skill Name</th>
                        <th>Proficiency</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td>Bachelor of Science in Computer Science</td>
                        <td>University of Technology</td>
                        <td>2015 - 2019</td>
                        <td>3.8/4.0</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr> -->
                    <!-- <tr>
                        <td>Master of Science in Software Engineering</td>
                        <td>State University</td>
                        <td>2019 - 2021</td>
                        <td>4.0/4.0</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr> -->
                </tbody>
            </table>

            <div class="mt-3">
                <button class="btn btn-primary" id="add-skills-btn"><i class="fas fa-plus"></i> Add Professional
                    Skills</button>
            </div>


            <h3 class="mt-4">Achievements</h3>
            <table class="table-responsive">
                <thead>
                    <tr>
                        <th>Achievement Title</th>
                        <th>Organization</th>
                        <th>Date</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td>Best Video Editor Award</td>
                        <td>Creative Studios</td>
                        <td>2023</td>
                        <td>5/5</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr> -->
                    <!-- <tr>
                        <td>Top Performer</td>
                        <td>Design Agency</td>
                        <td>2022</td>
                        <td>4.8/5</td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </td>
                    </tr> -->
                </tbody>
            </table>

            <div class="mt-3">
                <button class="btn btn-warning" id="add-achievement-btn"><i class="fas fa-plus"></i> Add
                    Achievement</button>
            </div>
        </div>

        <!-- Pricing Section -->
        <div class="content-section" id="pricing">
            <h2 class="section-title">Pricing Management</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <h3>Static Package</h3>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" placeholder="Package Title">
                        </div>
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" class="form-control" placeholder="Package Subtitle">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" value="₹99">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" rows="3" placeholder="Package Description"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Features</label>
                            <textarea class="form-control" rows="4">- Basic Design
                            - 2 Revisions
                            - 1 Page
                           - Mobile Friendly
                        </textarea>
                        </div>
                        <button class="btn btn-primary" onclick="savePackage('Static Package')">Save Package</button>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <h3>Standard Package</h3>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" placeholder="Package Title">
                        </div>
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" class="form-control" placeholder="Package Subtitle">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" value="₹199">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" rows="3" placeholder="Package Description"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Features</label>
                            <textarea class="form-control" rows="4">- Design Figma
                            - 3 Revisions
                             - 3 Pages
                            - Responsive Design
                            - Contact Form
                        </textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" id="recommended-standard" class="form-check-input"
                                onchange="toggleRecommended(this)">
                            <label for="recommended-standard" class="form-check-label">Recommended Package</label>
                        </div>
                        <button class="btn btn-primary" onclick="savePackage('Standard Package')">Save Package</button>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <h3>Premium Package</h3>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" placeholder="Package Title">
                        </div>
                        <div class="form-group">
                            <label>Subtitle</label>
                            <input type="text" class="form-control" placeholder="Package Subtitle">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" value="₹299">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" rows="3" placeholder="Package Description"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Features</label>
                            <textarea class="form-control" rows="4">- Design Figma
                          - 5 Revisions
                          - 5 Pages
                          - Responsive Design
                         - Contact Form
                         - SEO Optimized
                        </textarea>
                        </div>
                        <button class="btn btn-primary" onclick="savePackage('Premium Package')">Save Package</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Section -->
        <div class="content-section" id="blog">
            <h2 class="section-title">Blog Management</h2>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Manage Blog Posts</h3>
                <button class="btn btn-success" id="add-blog-btn"><i class="fas fa-plus"></i> Add New Blog Post</button>
            </div>

            <div class="card">
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Read Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="blog-posts-table">
                        <tr>
                            <td>How to Create Stunning Video Content</td>
                            <td>Tutorials</td>
                            <td>Deepak Kumar</td>
                            <td>2024-01-15</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>5 min</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-blog-btn">Edit</button>
                                <button class="btn btn-sm btn-danger delete-blog-btn">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Top 10 Motion Design Trends</td>
                            <td>Design</td>
                            <td>Deepak Kumar</td>
                            <td>2024-01-10</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>8 min</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-blog-btn">Edit</button>
                                <button class="btn btn-sm btn-danger delete-blog-btn">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Add/Edit Blog Modal -->
            <div id="blog-modal" class="modal" style="display: none;">
                <div class="modal-content" style="width: 90%; max-width: 1200px;">
                    <div class="modal-header">
                        <h3 id="blog-modal-title">Add New Blog Post</h3>
                        <span class="close" id="close-blog-modal">&times;</span>
                    </div>
                    <div class="modal-body">
                        <form id="blog-form">
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <label for="blog-title">Title *</label>
                                    <input type="text" id="blog-title" class="form-control"
                                        placeholder="Enter blog title" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="blog-category">Category *</label>
                                    <select id="blog-category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="tutorials">Tutorials</option>
                                        <option value="design">Design</option>
                                        <option value="video">Video Editing</option>
                                        <option value="motion">Motion Graphics</option>
                                        <option value="news">Industry News</option>
                                        <option value="tips">Tips & Tricks</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="blog-subtitle">Subtitle</label>
                                    <input type="text" id="blog-subtitle" class="form-control"
                                        placeholder="Enter blog subtitle">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="blog-slug">Slug</label>
                                    <input type="text" id="blog-slug" class="form-control"
                                        placeholder="Auto-generated from title">
                                    <small class="form-text text-muted">Leave empty to auto-generate from title</small>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="blog-author">Author *</label>
                                    <input type="text" id="blog-author" class="form-control"
                                        placeholder="Enter author name" value="Deepak Kumar" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="blog-readtime">Read Time (minutes) *</label>
                                    <input type="number" id="blog-readtime" class="form-control" placeholder="Minutes"
                                        min="1" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="blog-status">Status *</label>
                                    <select id="blog-status" class="form-control" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="blog-thumbnail">Thumbnail Image</label>
                                <input type="file" id="blog-thumbnail" class="form-control" accept="image/*">
                                <div class="mt-2">
                                    <img id="thumbnail-preview" src="" alt="Thumbnail Preview"
                                        style="max-width: 200px; max-height: 150px; display: none;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="blog-video">Video Upload (Optional)</label>
                                <input type="file" id="blog-video" class="form-control" accept="video/*">
                                <small class="form-text text-muted">Upload a video to embed in the blog post</small>
                            </div>

                            <div class="form-group">
                                <label for="blog-banner">Banner Image</label>
                                <input type="file" id="blog-banner" class="form-control" accept="image/*">
                                <div class="mt-2">
                                    <img id="banner-preview" src="" alt="Banner Preview"
                                        style="max-width: 100%; max-height: 200px; display: none;">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="blog-shortdesc">Short Description</label>
                                <textarea id="blog-shortdesc" class="form-control" rows="3"
                                    placeholder="Enter a short description for the blog post"></textarea>
                                <div class="character-counter"
                                    style="text-align: right; font-size: 0.8rem; color: #6c757d; margin-top: 5px;">0/200
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="blog-content">Full Description (Rich Text Editor) *</label>
                                <textarea id="blog-content" class="form-control" rows="10"
                                    placeholder="Write your blog content here..."></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary" id="cancel-blog">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Blog Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Testimonials Section -->
        <div class="content-section" id="testimonials">
            <h2 class="section-title">Popular Clients Awesome Clients</h2>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mt-4">Testimonials</h3>
                <button type="button" class="btn btn-success" id="add-category-btn">
                    Add Category
                </button>
            </div>
            
            <table class="table-responsive table table-bordered">
    <thead>
        <tr>
            <th>Category</th>
            <th>Logo</th>
            <th>Client Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Technology</td>
            <td>
                <img src="https://res.cloudinary.com/vistaprint/images/f_auto,q_auto/v1706191816/ideas-and-advice-prod/blogadmin/Screenshot-2024-01-25-at-15.09.28/Screenshot-2024-01-25-at-15.09.28.png?_i=AA" alt="TechCorp Logo" width="50">
            </td>
            <td>John Smith</td>
            <td>
                <button class="btn btn-sm btn-primary">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
            </td>
        </tr>

        <tr>
            <td>Marketing</td>
            <td>
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRv5iBfV61rJWeIcnx6KN9tqOR9wMyERLZpdw&s" alt="Marketing Logo" width="50">
            </td>
            <td>Sarah Johnson</td>
            <td>
                <button class="btn btn-sm btn-primary">Edit</button>
                <button class="btn btn-sm btn-danger">Delete</button>
            </td>
        </tr>
    </tbody>
</table>


            <div class="mt-3">
                <button class="btn btn-success" id="add-awesome-client-btn"><i class="fas fa-plus"></i> Add Awesome Clients</button>
            </div>
        </div>

        <!-- Lead Management Section -->
        <div class="content-section" id="leads">
            <h2 class="section-title">Lead Management</h2>

            <div class="form-group">
                <label for="lead-status">Filter by Status</label>
                <select id="lead-status" class="form-control">
                    <option value="all">All Leads</option>
                    <option value="new">New</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="hold">Hold</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="leads-table-body">
                        <!-- Leads will be loaded here dynamically -->
                        <tr>
                            <td colspan="6" class="text-center">Loading leads...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- View Lead Details Modal -->
            <div id="view-lead-modal" class="modal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Lead Details</h3>
                        <span class="close" id="close-lead-modal">&times;</span>
                    </div>
                    <div class="modal-body" id="lead-details-content">
                        <!-- Details will be loaded here -->
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button class="btn btn-success" id="export-leads-csv">Export to CSV</button>
            </div>
        </div>

        <script>
            // Lead Management Script
            (function () {
                console.log('Lead Management script (index.php) starting...');

                const tableBody = document.getElementById('leads-table-body');
                const statusFilter = document.getElementById('lead-status');
                const viewModal = document.getElementById('view-lead-modal');
                const modalContent = document.getElementById('lead-details-content');

                async function loadLeads(status = 'all') {
                    console.log('loadLeads called from index.php with status:', status);
                    if (!tableBody) return;

                    try {
                        const response = await fetch(`./AdminApi/auth/fetchContactUs.php?status=${status}`);
                        const data = await response.json();
                        console.log('loadLeads data:', data);

                        if (data.success) {
                            tableBody.innerHTML = '';
                            if (data.data.length === 0) {
                                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">No leads found.</td></tr>';
                                return;
                            }

                            data.data.forEach(lead => {
                                const row = document.createElement('tr');
                                const date = new Date(lead.created_at).toLocaleDateString();

                                let statusBadgeClass = 'badge-info';
                                if (lead.status === 'in-progress') statusBadgeClass = 'badge-warning';
                                if (lead.status === 'completed') statusBadgeClass = 'badge-success';
                                if (lead.status === 'hold') statusBadgeClass = 'badge-danger';

                                if (lead.status === 'rejected') statusBadgeClass = 'badge-secondary';

                                let actionsHtml = `
                                    <button class="btn btn-sm btn-primary view-btn" data-id="${lead.id}">View</button>
                                    <select class="form-control form-control-sm d-inline-block status-select" style="width: auto;" data-id="${lead.id}">
                                        <option value="new" ${lead.status === 'new' ? 'selected' : ''}>New</option>
                                        <option value="in-progress" ${lead.status === 'in-progress' ? 'selected' : ''}>In Progress</option>
                                        <option value="completed" ${lead.status === 'completed' ? 'selected' : ''}>Completed</option>
                                        <option value="hold" ${lead.status === 'hold' ? 'selected' : ''}>Hold</option>
                                        <option value="rejected" ${lead.status === 'rejected' ? 'selected' : ''}>Rejected</option>
                                    </select>
                                    <button class="btn btn-sm btn-danger delete-btn" data-id="${lead.id}">Delete</button>
                                `;

                                row.innerHTML = `
                                    <td>${lead.name}</td>
                                    <td>${lead.email}</td>
                                    <td>${lead.subject}</td>
                                    <td><span class="badge ${statusBadgeClass}">${lead.status.charAt(0).toUpperCase() + lead.status.slice(1)}</span></td>
                                    <td>${date}</td>
                                    <td>${actionsHtml}</td>
                                `;
                                tableBody.appendChild(row);

                                // View Details
                                row.querySelector('.view-btn').addEventListener('click', () => {
                                    if (viewModal && modalContent) {
                                        modalContent.innerHTML = `
                                            <div class="lead-info">
                                                <p><strong>Name:</strong> ${lead.name}</p>
                                                <p><strong>Email:</strong> <a href="mailto:${lead.email}">${lead.email}</a></p>
                                                <p><strong>Phone:</strong> ${lead.phone_number || 'N/A'}</p>
                                                <p><strong>Subject:</strong> ${lead.subject}</p>
                                                <p><strong>Status:</strong> ${lead.status}</p>
                                                <p><strong>Date:</strong> ${new Date(lead.created_at).toLocaleString()}</p>
                                                <hr>
                                                <p><strong>Message:</strong></p>
                                                <div class="message-body" style="background: #f9f9f9; padding: 15px; border-radius: 5px; white-space: pre-wrap;">${lead.message}</div>
                                            </div>
                                        `;
                                        viewModal.style.display = 'flex';
                                    }
                                });

                                // Update Status via Dropdown
                                const statusSelect = row.querySelector('.status-select');
                                if (statusSelect) {
                                    statusSelect.addEventListener('change', async () => {
                                        const newStatus = statusSelect.value;
                                        const id = lead.id;
                                        try {
                                            const res = await fetch('./AdminApi/auth/updateContactUsStatus.php', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/json' },
                                                body: JSON.stringify({ id, status: newStatus })
                                            });
                                            const resData = await res.json();
                                            if (resData.success) {
                                                if (typeof showNotification === 'function') showNotification(resData.message, 'success');
                                                else alert(resData.message);
                                                loadLeads(statusFilter ? statusFilter.value : 'all');
                                            }
                                        } catch (e) {
                                            console.error(e);
                                            if (typeof showNotification === 'function') showNotification('Failed to update status', 'error');
                                        }
                                    });
                                }

                                // Delete
                                row.querySelector('.delete-btn').addEventListener('click', async () => {
                                    if (confirm(`Delete lead from ${lead.name}?`)) {
                                        try {
                                            const res = await fetch('./AdminApi/auth/deleteContactUs.php', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/json' },
                                                body: JSON.stringify({ id: lead.id })
                                            });
                                            const resData = await res.json();
                                            if (resData.success) {
                                                if (typeof showNotification === 'function') showNotification(resData.message, 'success');
                                                else alert(resData.message);
                                                loadLeads(statusFilter ? statusFilter.value : 'all');
                                            }
                                        } catch (e) { console.error(e); }
                                    }
                                });
                            });
                        }
                    } catch (error) {
                        console.error('Error fetching leads:', error);
                    }
                }

                // Modal Close logic
                const closeBtn = document.getElementById('close-lead-modal');
                if (closeBtn) closeBtn.onclick = () => viewModal.style.display = 'none';
                window.onclick = (e) => { if (e.target == viewModal) viewModal.style.display = 'none'; };

                // Filter logic
                if (statusFilter) {
                    statusFilter.addEventListener('change', () => loadLeads(statusFilter.value));
                }

                // Section Activation logic
                const leadsSection = document.getElementById('leads');
                if (leadsSection) {
                    const observer = new MutationObserver(m => {
                        m.forEach(mut => {
                            if (mut.type === 'attributes' && leadsSection.classList.contains('active')) {
                                loadLeads(statusFilter ? statusFilter.value : 'all');
                            }
                        });
                    });
                    observer.observe(leadsSection, { attributes: true });

                    // Initial load triggers
                    document.addEventListener('DOMContentLoaded', () => {
                        if (leadsSection.classList.contains('active')) loadLeads(statusFilter ? statusFilter.value : 'all');
                    });
                    window.addEventListener('load', () => {
                        if (leadsSection.classList.contains('active')) loadLeads(statusFilter ? statusFilter.value : 'all');
                    });
                    if (leadsSection.classList.contains('active')) {
                        loadLeads(statusFilter ? statusFilter.value : 'all');
                    }
                }

                // Export to CSV logic
                async function exportLeadsToCSV() {
                    const status = statusFilter ? statusFilter.value : 'all';
                    try {
                        const response = await fetch(`./AdminApi/auth/fetchContactUs.php?status=${status}`);
                        const data = await response.json();

                        if (data.success && data.data.length > 0) {
                            const leads = data.data;
                            const headers = ['Name', 'Email', 'Phone', 'Subject', 'Status', 'Date', 'Message'];

                            let csvContent = headers.join(',') + '\n';

                            leads.forEach(lead => {
                                const row = [
                                    `"${lead.name.replace(/"/g, '""')}"`,
                                    `"${lead.email.replace(/"/g, '""')}"`,
                                    `"${(lead.phone_number || 'N/A').replace(/"/g, '""')}"`,
                                    `"${lead.subject.replace(/"/g, '""')}"`,
                                    `"${lead.status.replace(/"/g, '""')}"`,
                                    `"${new Date(lead.created_at).toLocaleString().replace(/"/g, '""')}"`,
                                    `"${lead.message.replace(/"/g, '""')}"`
                                ];
                                csvContent += row.join(',') + '\n';
                            });

                            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                            const url = URL.createObjectURL(blob);
                            const link = document.createElement('a');
                            link.setAttribute('href', url);
                            link.setAttribute('download', `leads_export_${status}_${new Date().toISOString().split('T')[0]}.csv`);
                            link.style.visibility = 'hidden';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            if (typeof showNotification === 'function') showNotification('CSV exported successfully', 'success');
                        } else {
                            if (typeof showNotification === 'function') showNotification('No data to export', 'error');
                            else alert('No data to export');
                        }
                    } catch (error) {
                        console.error('Error exporting CSV:', error);
                        if (typeof showNotification === 'function') showNotification('Failed to export CSV', 'error');
                    }
                }

                const exportBtn = document.getElementById('export-leads-csv');
                if (exportBtn) {
                    exportBtn.addEventListener('click', exportLeadsToCSV);
                }
            })();
        </script>

        <!-- Settings Section -->
        <div class="content-section" id="settings">
            <h2 class="section-title">Settings</h2>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Admin Management</h3>
                <button class="btn btn-success" id="add-admin-btn"><i class="fas fa-plus"></i> Add Admin</button>
            </div>

            <div class="card">
                <table class="table-responsive">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="admin-list">
                        <tr>
                            <td>admin@deepakkumar.com</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-action="toggle-status">Update</button>
                                <button class="btn btn-sm btn-danger" data-action="delete-admin">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Admin Modal  /// complte -->

    <div id="add-admin-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Admin</h3>
                <span class="close" id="close-admin-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="admin-form">
                    <div class="form-group">
                        <label for="admin-email">Email</label>
                        <input type="email" id="admin-email" class="form-control" placeholder="Enter admin email"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="admin-password">Password</label>
                        <input type="password" id="admin-password" class="form-control" placeholder="Enter password"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="admin-status">Status</label>
                        <select id="admin-status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancel-admin">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- complete this section  -->


    <!-- Add Experience Modal -->


    <div id="add-experience-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Experience</h3>
                <span class="close" id="close-experience-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="experience-form">
                    <div class="form-group">
                        <label for="job-title">Job Experience *</label>
                        <input type="text" id="job-title" class="form-control" required
                            placeholder="Enter job title/position">
                    </div>

                    <div class="form-group">
                        <label for="company-name">Company Name *</label>
                        <input type="text" id="company-name" class="form-control" required
                            placeholder="Enter company name">
                    </div>

                    <div class="form-group">
                        <label for="job-duration">Duration *</label>
                        <input type="text" id="job-duration" class="form-control" required
                            placeholder="e.g., 2004 - 2008">
                    </div>

                    <div class="form-group">
                        <label for="job-rating">Rating *</label>
                        <input type="text" id="job-rating" class="form-control" required placeholder="e.g., 4.70/5">
                    </div>

                    <div class="form-group">
                        <label for="job-description">Description</label>
                        <textarea id="job-description" class="form-control" rows="4"
                            placeholder="Enter job description"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancel-experience">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Experience</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Education Modal -->
    <div id="add-education-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Education</h3>
                <span class="close" id="close-education-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="education-form">
                    <div class="form-group">
                        <label for="education-degree">Degree/Course *</label>
                        <input type="text" id="education-degree" class="form-control" required
                            placeholder="Enter degree or course name">
                    </div>

                    <div class="form-group">
                        <label for="education-institution">Institution *</label>
                        <input type="text" id="education-institution" class="form-control" required
                            placeholder="Enter institution name">
                    </div>

                    <div class="form-group">
                        <label for="education-duration">Duration *</label>
                        <input type="text" id="education-duration" class="form-control" required
                            placeholder="e.g., 2004 - 2008">
                    </div>

                    <div class="form-group">
                        <label for="education-grade">Grade/Score</label>
                        <input type="text" id="education-grade" class="form-control" placeholder="e.g., 4.70/5 or 85%">
                    </div>

                    <div class="form-group">
                        <label for="education-description">Description</label>
                        <textarea id="education-description" class="form-control" rows="4"
                            placeholder="Enter education details"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancel-education">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Education</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Professional Skills Modal -->
    <div id="add-skills-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Professional Skill</h3>
                <span class="close" id="close-skills-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="skills-form">
                    <div class="form-group">
                        <label for="skill-name">Skill Name *</label>
                        <input type="text" id="skill-name" class="form-control" required placeholder="Enter skill name">
                    </div>

                    <div class="form-group">
                        <label for="skill-rating">Rating (0-5) *</label>
                        <input type="range" id="skill-rating" min="0" max="5" step="0.1" class="form-control" required>
                        <span id="skill-rating-value">0.0/5.0</span>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancel-skills">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Skill</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Achievement Modal -->
    <div id="add-achievement-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Achievement</h3>
                <span class="close" id="close-achievement-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="achievement-form">
                    <div class="form-group">
                        <label for="achievement-title">Achievement Title *</label>
                        <input type="text" id="achievement-title" class="form-control" required
                            placeholder="Enter achievement title">
                    </div>

                    <div class="form-group">
                        <label for="achievement-organization">Organization *</label>
                        <input type="text" id="achievement-organization" class="form-control" required
                            placeholder="Enter organization name">
                    </div>

                    <div class="form-group">
                        <label for="achievement-date">Date *</label>
                        <input type="text" id="achievement-date" class="form-control" required
                            placeholder="e.g., January 2024">
                    </div>

                    <div class="form-group">
                        <label for="achievement-rating">Rating/Score</label>
                        <input type="text" id="achievement-rating" class="form-control" placeholder="e.g., 4.70/5">
                    </div>

                    <div class="form-group">
                        <label for="achievement-description">Description</label>
                        <textarea id="achievement-description" class="form-control" rows="4"
                            placeholder="Enter achievement details"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancel-achievement">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Achievement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var form = document.getElementById('hero-form');
            var profileInput = document.getElementById('profile-image');
            var logoInput = document.getElementById('logo-image');
            var profilePreview = document.getElementById('profile-preview');
            var logoPreview = document.getElementById('logo-preview');
            var endpoint = 'AdminApi/auth/profileImage&logo.php';
            function loadActive() {
                fetch(endpoint).then(function (r) { return r.json(); }).then(function (j) {
                    if (j && j.success && j.data) {
                        if (j.data.profile && j.data.profile.url) profilePreview.src = j.data.profile.url;
                        if (j.data.logo && j.data.logo.url) logoPreview.src = j.data.logo.url;
                    }
                }).catch(function () { });
            }
            function updatePreview(input, img) {
                if (input.files && input.files[0]) {
                    var url = URL.createObjectURL(input.files[0]);
                    img.src = url;
                }
            }
            profileInput.addEventListener('change', function () { updatePreview(profileInput, profilePreview); });
            logoInput.addEventListener('change', function () { updatePreview(logoInput, logoPreview); });
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                var fd = new FormData();
                if (profileInput.files && profileInput.files[0]) fd.append('profile_image', profileInput.files[0]);
                if (logoInput.files && logoInput.files[0]) fd.append('logo_image', logoInput.files[0]);
                fetch(endpoint, { method: 'POST', body: fd }).then(function (r) { return r.json(); }).then(function (j) {
                    if (j && j.success && j.data) {
                        if (j.data.profile && j.data.profile.url) profilePreview.src = j.data.profile.url;
                        if (j.data.logo && j.data.logo.url) logoPreview.src = j.data.logo.url;
                        if (typeof showNotification === 'function') {
                            showNotification(j.message || 'Image uploaded successfully', 'success');
                        } else {
                            alert(j.message || 'Image uploaded successfully');
                        }
                    } else {
                        if (typeof showNotification === 'function') {
                            showNotification((j && j.message) ? j.message : 'Upload failed', 'error');
                        } else {
                            alert((j && j.message) ? j.message : 'Upload failed');
                        }
                    }
                }).catch(function () {
                    if (typeof showNotification === 'function') {
                        showNotification('Network error', 'error');
                    } else {
                        alert('Network error');
                    }
                });
            });
            loadActive();
        }());

        // Add Experience functionality
        (function () {
            const addExperienceBtn = document.getElementById('add-experience-btn');
            const experienceModal = document.getElementById('add-experience-modal');
            const closeExperienceModalBtn = document.getElementById('close-experience-modal');
            const cancelExperienceBtn = document.getElementById('cancel-experience');
            const experienceForm = document.getElementById('experience-form');

            if (addExperienceBtn && experienceModal) {
                addExperienceBtn.addEventListener('click', function () {
                    // Reset form fields
                    if (experienceForm) experienceForm.reset();
                    experienceModal.style.display = 'flex';
                });
            }

            if (closeExperienceModalBtn) closeExperienceModalBtn.addEventListener('click', () => {
                experienceModal.style.display = 'none';
                resetFormToDefault(experienceForm);
            });
            if (cancelExperienceBtn) cancelExperienceBtn.addEventListener('click', () => {
                experienceModal.style.display = 'none';
                resetFormToDefault(experienceForm);
            });

            // Close modal if clicked outside content
            window.addEventListener('click', function (event) {
                if (event.target === experienceModal) {
                    experienceModal.style.display = 'none';
                    resetFormToDefault(experienceForm);
                }
            });

            // Handle experience form submission
            if (experienceForm) {
                experienceForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const jobTitle = document.getElementById('job-title').value;
                    const companyName = document.getElementById('company-name').value;
                    const jobDuration = document.getElementById('job-duration').value;
                    const jobRating = document.getElementById('job-rating').value;
                    const jobDescription = document.getElementById('job-description').value;

                    // Basic validation
                    if (!jobTitle || !companyName || !jobDuration || !jobRating) {
                        if (typeof showNotification === 'function') {
                            showNotification('Please fill in all required fields', 'error');
                        } else {
                            alert('Please fill in all required fields');
                        }
                        return;
                    }

                    // Check if this is an update operation
                    const isUpdate = experienceForm.getAttribute('data-action') === 'update';
                    const recordId = experienceForm.getAttribute('data-record-id');

                    let apiUrl = './AdminApi/auth/addResumeRecord.php';
                    let method = 'POST';
                    let requestData = {
                        type: 'experience',
                        job_title: jobTitle,
                        company_name: companyName,
                        duration: jobDuration,
                        rating: jobRating,
                        description: jobDescription
                    };

                    if (isUpdate && recordId) {
                        // Update operation
                        apiUrl = './AdminApi/auth/updateResumeRecord.php';
                        requestData.id = recordId;
                    }

                    // Send data to server
                    fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(requestData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message, 'success');
                                } else {
                                    alert(data.message);
                                }

                                // Update the table
                                const tableBody = document.querySelector('#resume table:nth-of-type(1) tbody');
                                if (isUpdate && recordId) {
                                    // Update existing row
                                    const rowToUpdate = tableBody.querySelector(`tr[data-id="${recordId}"]`);
                                    if (rowToUpdate) {
                                        rowToUpdate.setAttribute('data-description', jobDescription || '');
                                        rowToUpdate.innerHTML = `
                                        <td>${jobTitle}</td>
                                        <td>${companyName}</td>
                                        <td>${jobDuration}</td>
                                        <td>${jobRating}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    `;
                                    }
                                } else {
                                    // Add new row
                                    const newRow = document.createElement('tr');
                                    newRow.setAttribute('data-id', data.id);
                                    newRow.setAttribute('data-description', jobDescription || '');
                                    newRow.innerHTML = `
                                    <td>${jobTitle}</td>
                                    <td>${companyName}</td>
                                    <td>${jobDuration}</td>
                                    <td>${jobRating}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                    tableBody.appendChild(newRow);
                                }

                                // Close modal and reset form
                                experienceModal.style.display = 'none';
                                experienceForm.reset();
                                resetFormToDefault(experienceForm);
                            } else {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message || 'Error processing experience', 'error');
                                } else {
                                    alert(data.message || 'Error processing experience');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (typeof showNotification === 'function') {
                                showNotification('Network error. Please try again.', 'error');
                            } else {
                                alert('Network error. Please try again.');
                            }
                        });
                });
            }
        }());

        // Add Education functionality
        (function () {
            const addEducationBtn = document.getElementById('add-education-btn');
            const educationModal = document.getElementById('add-education-modal');
            const closeEducationModalBtn = document.getElementById('close-education-modal');
            const cancelEducationBtn = document.getElementById('cancel-education');
            const educationForm = document.getElementById('education-form');

            // Add Education button functionality
            if (addEducationBtn && educationModal) {
                addEducationBtn.addEventListener('click', function () {
                    // Reset form fields
                    if (document.getElementById('education-form')) document.getElementById('education-form').reset();
                    educationModal.style.display = 'flex';
                });
            }

            if (closeEducationModalBtn) closeEducationModalBtn.addEventListener('click', () => {
                educationModal.style.display = 'none';
                resetFormToDefault(educationForm);
            });
            if (cancelEducationBtn) cancelEducationBtn.addEventListener('click', () => {
                educationModal.style.display = 'none';
                resetFormToDefault(educationForm);
            });

            // Close modal if clicked outside content
            window.addEventListener('click', function (event) {
                if (event.target === educationModal) {
                    educationModal.style.display = 'none';
                    resetFormToDefault(educationForm);
                }
            });

            // Handle education form submission
            if (educationForm) {
                educationForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const degree = document.getElementById('education-degree').value;
                    const institution = document.getElementById('education-institution').value;
                    const duration = document.getElementById('education-duration').value;
                    const grade = document.getElementById('education-grade').value;
                    const description = document.getElementById('education-description').value;

                    // Basic validation
                    if (!degree || !institution || !duration) {
                        if (typeof showNotification === 'function') {
                            showNotification('Please fill in all required fields', 'error');
                        } else {
                            alert('Please fill in all required fields');
                        }
                        return;
                    }

                    // Check if this is an update operation
                    const isUpdate = educationForm.getAttribute('data-action') === 'update';
                    const recordId = educationForm.getAttribute('data-record-id');

                    let apiUrl = './AdminApi/auth/addResumeRecord.php';
                    let requestData = {
                        type: 'education',
                        degree_course: degree,
                        institution: institution,
                        duration: duration,
                        grade_score: grade,
                        description: description
                    };

                    if (isUpdate && recordId) {
                        // Update operation
                        apiUrl = './AdminApi/auth/updateResumeRecord.php';
                        requestData.id = recordId;
                    }

                    // Send data to server
                    fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(requestData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message, 'success');
                                } else {
                                    alert(data.message);
                                }

                                // Update the table
                                const tableBody = document.querySelector('#resume table:nth-of-type(2) tbody');
                                if (isUpdate && recordId) {
                                    // Update existing row
                                    const rowToUpdate = tableBody.querySelector(`tr[data-id="${recordId}"]`);
                                    if (rowToUpdate) {
                                        rowToUpdate.setAttribute('data-description', description || '');
                                        rowToUpdate.innerHTML = `
                                        <td>${degree}</td>
                                        <td>${institution}</td>
                                        <td>${duration}</td>
                                        <td>${grade || 'N/A'}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    `;
                                    }
                                } else {
                                    // Add new row
                                    const newRow = document.createElement('tr');
                                    newRow.setAttribute('data-id', data.id);
                                    newRow.setAttribute('data-description', description || '');
                                    newRow.innerHTML = `
                                    <td>${degree}</td>
                                    <td>${institution}</td>
                                    <td>${duration}</td>
                                    <td>${grade || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                    tableBody.appendChild(newRow);
                                }

                                // Close modal and reset form
                                educationModal.style.display = 'none';
                                educationForm.reset();
                                resetFormToDefault(educationForm);
                            } else {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message || 'Error processing education', 'error');
                                } else {
                                    alert(data.message || 'Error processing education');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (typeof showNotification === 'function') {
                                showNotification('Network error. Please try again.', 'error');
                            } else {
                                alert('Network error. Please try again.');
                            }
                        });
                });
            }
        }());

        // Add Professional Skills functionality
        (function () {
            const addSkillsBtn = document.getElementById('add-skills-btn');
            const skillsModal = document.getElementById('add-skills-modal');
            const closeSkillsModalBtn = document.getElementById('close-skills-modal');
            const cancelSkillsBtn = document.getElementById('cancel-skills');
            const skillsForm = document.getElementById('skills-form');

            // Add Skills button functionality
            if (addSkillsBtn && skillsModal) {
                addSkillsBtn.addEventListener('click', function () {
                    // Reset form fields
                    if (document.getElementById('skills-form')) document.getElementById('skills-form').reset();
                    skillsModal.style.display = 'flex';
                });
            }

            if (closeSkillsModalBtn) closeSkillsModalBtn.addEventListener('click', () => {
                skillsModal.style.display = 'none';
                resetFormToDefault(skillsForm);
            });
            if (cancelSkillsBtn) cancelSkillsBtn.addEventListener('click', () => {
                skillsModal.style.display = 'none';
                resetFormToDefault(skillsForm);
            });

            // Close modal if clicked outside content
            window.addEventListener('click', function (event) {
                if (event.target === skillsModal) {
                    skillsModal.style.display = 'none';
                    resetFormToDefault(skillsForm);
                }
            });

            // Handle skills form submission
            if (skillsForm) {
                // Update rating display as slider changes
                const skillRatingInput = document.getElementById('skill-rating');
                const skillRatingValue = document.getElementById('skill-rating-value');

                if (skillRatingInput && skillRatingValue) {
                    skillRatingInput.addEventListener('input', function () {
                        skillRatingValue.textContent = this.value + '/5.0';
                    });
                }

                skillsForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const skillName = document.getElementById('skill-name').value;
                    const skillRating = document.getElementById('skill-rating').value;

                    // Basic validation
                    if (!skillName || !skillRating) {
                        if (typeof showNotification === 'function') {
                            showNotification('Please fill in all required fields', 'error');
                        } else {
                            alert('Please fill in all required fields');
                        }
                        return;
                    }

                    // Check if this is an update operation
                    const isUpdate = skillsForm.getAttribute('data-action') === 'update';
                    const recordId = skillsForm.getAttribute('data-record-id');

                    let apiUrl = './AdminApi/auth/addResumeRecord.php';
                    let requestData = {
                        type: 'skill',
                        skill_name: skillName,
                        rating: skillRating
                    };

                    if (isUpdate && recordId) {
                        // Update operation
                        apiUrl = './AdminApi/auth/updateResumeRecord.php';
                        requestData.id = recordId;
                    }

                    // Send data to server
                    fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(requestData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message, 'success');
                                } else {
                                    alert(data.message);
                                }

                                // Update the table
                                const tableBody = document.querySelector('#resume table:nth-of-type(3) tbody');
                                if (isUpdate && recordId) {
                                    // Update existing row
                                    const rowToUpdate = tableBody.querySelector(`tr[data-id="${recordId}"]`);
                                    if (rowToUpdate) {
                                        rowToUpdate.setAttribute('data-description', ''); // Skills don't have descriptions
                                        rowToUpdate.innerHTML = `
                                        <td>${skillName}</td>
                                        <td>${skillRating}/5.0</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    `;
                                    }
                                } else {
                                    // Add new row
                                    const newRow = document.createElement('tr');
                                    newRow.setAttribute('data-id', data.id);
                                    newRow.setAttribute('data-description', ''); // Skills don't have descriptions
                                    newRow.innerHTML = `
                                    <td>${skillName}</td>
                                    <td>${skillRating}/5.0</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                    tableBody.appendChild(newRow);
                                }

                                // Close modal and reset form
                                skillsModal.style.display = 'none';
                                skillsForm.reset();
                                // Reset the rating display
                                if (skillRatingValue) skillRatingValue.textContent = '0.0/5.0';
                                resetFormToDefault(skillsForm);
                            } else {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message || 'Error processing skill', 'error');
                                } else {
                                    alert(data.message || 'Error processing skill');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (typeof showNotification === 'function') {
                                showNotification('Network error. Please try again.', 'error');
                            } else {
                                alert('Network error. Please try again.');
                            }
                        });
                });
            }
        }());

        // Add Achievement functionality
        (function () {
            const addAchievementBtn = document.getElementById('add-achievement-btn');
            const achievementModal = document.getElementById('add-achievement-modal');
            const closeAchievementModalBtn = document.getElementById('close-achievement-modal');
            const cancelAchievementBtn = document.getElementById('cancel-achievement');
            const achievementForm = document.getElementById('achievement-form');

            // Add Achievement button functionality
            if (addAchievementBtn && achievementModal) {
                addAchievementBtn.addEventListener('click', function () {
                    // Reset form fields
                    if (document.getElementById('achievement-form')) document.getElementById('achievement-form').reset();
                    achievementModal.style.display = 'flex';
                });
            }

            if (closeAchievementModalBtn) closeAchievementModalBtn.addEventListener('click', () => {
                achievementModal.style.display = 'none';
                resetFormToDefault(achievementForm);
            });
            if (cancelAchievementBtn) cancelAchievementBtn.addEventListener('click', () => {
                achievementModal.style.display = 'none';
                resetFormToDefault(achievementForm);
            });

            // Close modal if clicked outside content
            window.addEventListener('click', function (event) {
                if (event.target === achievementModal) {
                    achievementModal.style.display = 'none';
                    resetFormToDefault(achievementForm);
                }
            });

            // Handle achievement form submission
            if (achievementForm) {
                achievementForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const title = document.getElementById('achievement-title').value;
                    const organization = document.getElementById('achievement-organization').value;
                    const date = document.getElementById('achievement-date').value;
                    const rating = document.getElementById('achievement-rating').value;
                    const description = document.getElementById('achievement-description').value;

                    // Basic validation
                    if (!title || !organization || !date) {
                        if (typeof showNotification === 'function') {
                            showNotification('Please fill in all required fields', 'error');
                        } else {
                            alert('Please fill in all required fields');
                        }
                        return;
                    }

                    // Check if this is an update operation
                    const isUpdate = achievementForm.getAttribute('data-action') === 'update';
                    const recordId = achievementForm.getAttribute('data-record-id');

                    let apiUrl = './AdminApi/auth/addResumeRecord.php';
                    let requestData = {
                        type: 'achievement',
                        title: title,
                        organization: organization,
                        date_achieved: date,
                        rating_score: rating,
                        description: description
                    };

                    if (isUpdate && recordId) {
                        // Update operation
                        apiUrl = './AdminApi/auth/updateResumeRecord.php';
                        requestData.id = recordId;
                    }

                    // Send data to server
                    fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(requestData)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message, 'success');
                                } else {
                                    alert(data.message);
                                }

                                // Update the table
                                const tableBody = document.querySelector('#resume table:nth-of-type(4) tbody');
                                if (isUpdate && recordId) {
                                    // Update existing row
                                    const rowToUpdate = tableBody.querySelector(`tr[data-id="${recordId}"]`);
                                    if (rowToUpdate) {
                                        rowToUpdate.setAttribute('data-description', description || '');
                                        rowToUpdate.innerHTML = `
                                        <td>${title}</td>
                                        <td>${organization}</td>
                                        <td>${date}</td>
                                        <td>${rating || 'N/A'}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary">Edit</button>
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </td>
                                    `;
                                    }
                                } else {
                                    // Add new row
                                    const newRow = document.createElement('tr');
                                    newRow.setAttribute('data-id', data.id);
                                    newRow.setAttribute('data-description', description || '');
                                    newRow.innerHTML = `
                                    <td>${title}</td>
                                    <td>${organization}</td>
                                    <td>${date}</td>
                                    <td>${rating || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                    tableBody.appendChild(newRow);
                                }

                                // Close modal and reset form
                                achievementModal.style.display = 'none';
                                achievementForm.reset();
                                resetFormToDefault(achievementForm);
                            } else {
                                if (typeof showNotification === 'function') {
                                    showNotification(data.message || 'Error processing achievement', 'error');
                                } else {
                                    alert(data.message || 'Error processing achievement');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (typeof showNotification === 'function') {
                                showNotification('Network error. Please try again.', 'error');
                            } else {
                                alert('Network error. Please try again.');
                            }
                        });
                });
            }
        }());

        // Function to fetch and populate resume data
        function loadResumeData() {
            fetch('./AdminApi/auth/fetchResume.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear existing table data (except headers)
                        const experienceTableBody = document.querySelector('#resume table:nth-of-type(1) tbody');
                        const educationTableBody = document.querySelector('#resume table:nth-of-type(2) tbody');
                        const skillsTableBody = document.querySelector('#resume table:nth-of-type(3) tbody');
                        const achievementsTableBody = document.querySelector('#resume table:nth-of-type(4) tbody');

                        if (experienceTableBody) {
                            experienceTableBody.innerHTML = '';
                            data.experiences.forEach(exp => {
                                const newRow = document.createElement('tr');
                                newRow.setAttribute('data-id', exp.id);
                                newRow.setAttribute('data-description', exp.description || '');
                                newRow.innerHTML = `
                                    <td>${exp.job_title}</td>
                                    <td>${exp.company_name}</td>
                                    <td>${exp.duration}</td>
                                    <td>${exp.rating}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                experienceTableBody.appendChild(newRow);
                            });
                        }

                        if (educationTableBody) {
                            educationTableBody.innerHTML = '';
                            data.educations.forEach(edu => {
                                const newRow = document.createElement('tr');
                                newRow.setAttribute('data-id', edu.id);
                                newRow.setAttribute('data-description', edu.description || '');
                                newRow.innerHTML = `
                                    <td>${edu.degree_course}</td>
                                    <td>${edu.institution}</td>
                                    <td>${edu.duration}</td>
                                    <td>${edu.grade_score || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                educationTableBody.appendChild(newRow);
                            });
                        }

                        if (skillsTableBody) {
                            skillsTableBody.innerHTML = '';
                            data.skills.forEach(skill => {
                                const newRow = document.createElement('tr');
                                newRow.setAttribute('data-id', skill.id);
                                newRow.setAttribute('data-description', ''); // Skills don't have descriptions in the DB
                                newRow.innerHTML = `
                                    <td>${skill.skill_name}</td>
                                    <td>${skill.rating}/5.0</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                skillsTableBody.appendChild(newRow);
                            });
                        }

                        if (achievementsTableBody) {
                            achievementsTableBody.innerHTML = '';
                            data.achievements.forEach(ach => {
                                const newRow = document.createElement('tr');
                                newRow.setAttribute('data-id', ach.id);
                                newRow.setAttribute('data-description', ach.description || '');
                                newRow.innerHTML = `
                                    <td>${ach.title}</td>
                                    <td>${ach.organization}</td>
                                    <td>${ach.date_achieved}</td>
                                    <td>${ach.rating_score || 'N/A'}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                `;
                                achievementsTableBody.appendChild(newRow);
                            });
                        }
                    } else {
                        console.error('Error fetching resume data:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error loading resume data:', error);
                });
        }

        // Load resume data when the page loads
        document.addEventListener('DOMContentLoaded', function () {
            loadResumeData();
        });

        // Add event listeners for edit and delete buttons
        function addEditDeleteEventListeners() {
            // Since we're dynamically adding rows, we use event delegation
            document.getElementById('resume').addEventListener('click', function (e) {
                // Handle delete button click
                if (e.target.classList.contains('btn-danger')) {
                    const row = e.target.closest('tr');
                    if (row) {
                        // Determine which table this row belongs to
                        const table = row.closest('table');
                        let recordType = '';

                        if (table === document.querySelector('#resume table:nth-of-type(1)')) {
                            recordType = 'experience';
                        } else if (table === document.querySelector('#resume table:nth-of-type(2)')) {
                            recordType = 'education';
                        } else if (table === document.querySelector('#resume table:nth-of-type(3)')) {
                            recordType = 'skill';
                        } else if (table === document.querySelector('#resume table:nth-of-type(4)')) {
                            recordType = 'achievement';
                        }

                        if (recordType) {
                            // Confirm deletion
                            if (confirm('Are you sure you want to delete this record?')) {
                                // Get the record ID from the row's data attribute
                                const recordId = row.getAttribute('data-id');

                                // Send delete request to server
                                fetch('./AdminApi/auth/deleteResumeRecord.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({
                                        type: recordType,
                                        id: recordId
                                    })
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            row.remove();
                                            if (typeof showNotification === 'function') {
                                                showNotification(data.message, 'success');
                                            } else {
                                                alert(data.message);
                                            }
                                        } else {
                                            if (typeof showNotification === 'function') {
                                                showNotification(data.message || 'Error deleting record', 'error');
                                            } else {
                                                alert(data.message || 'Error deleting record');
                                            }
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        if (typeof showNotification === 'function') {
                                            showNotification('Network error. Please try again.', 'error');
                                        } else {
                                            alert('Network error. Please try again.');
                                        }
                                    });
                            }
                        }
                    }
                }

                // Handle edit button click
                else if (e.target.classList.contains('btn-primary')) {
                    const row = e.target.closest('tr');
                    if (row) {
                        // Determine which table this row belongs to
                        const table = row.closest('table');
                        let recordType = '';

                        if (table === document.querySelector('#resume table:nth-of-type(1)')) {
                            recordType = 'experience';
                            openEditExperienceModal(row);
                        } else if (table === document.querySelector('#resume table:nth-of-type(2)')) {
                            recordType = 'education';
                            openEditEducationModal(row);
                        } else if (table === document.querySelector('#resume table:nth-of-type(3)')) {
                            recordType = 'skill';
                            openEditSkillsModal(row);
                        } else if (table === document.querySelector('#resume table:nth-of-type(4)')) {
                            recordType = 'achievement';
                            openEditAchievementModal(row);
                        }
                    }
                }
            });
        }

        // Function to open edit modal for experience
        function openEditExperienceModal(row) {
            const cells = row.querySelectorAll('td');
            const recordId = row.getAttribute('data-id');
            const description = row.getAttribute('data-description') || '';

            // Set values in the form
            document.getElementById('job-title').value = cells[0].textContent;
            document.getElementById('company-name').value = cells[1].textContent;
            document.getElementById('job-duration').value = cells[2].textContent;
            // Extract rating from text (e.g., "4.8/5" -> "4.8")
            const ratingText = cells[3].textContent;
            const rating = ratingText.split('/')[0];
            document.getElementById('job-rating').value = rating;
            document.getElementById('job-description').value = description;

            // Show the modal
            const modal = document.getElementById('add-experience-modal');
            modal.style.display = 'flex';

            // Change form submission to update instead of add
            const form = document.getElementById('experience-form');

            // Store the record ID in a hidden field or data attribute
            form.setAttribute('data-record-id', recordId);
            form.setAttribute('data-action', 'update');

            // Update the submit button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Update Experience';
        }

        // Function to open edit modal for education
        function openEditEducationModal(row) {
            const cells = row.querySelectorAll('td');
            const recordId = row.getAttribute('data-id');
            const description = row.getAttribute('data-description') || '';

            // Set values in the form
            document.getElementById('education-degree').value = cells[0].textContent;
            document.getElementById('education-institution').value = cells[1].textContent;
            document.getElementById('education-duration').value = cells[2].textContent;
            document.getElementById('education-grade').value = cells[3].textContent !== 'N/A' ? cells[3].textContent : '';
            document.getElementById('education-description').value = description;

            // Show the modal
            const modal = document.getElementById('add-education-modal');
            modal.style.display = 'flex';

            // Change form submission to update instead of add
            const form = document.getElementById('education-form');
            form.setAttribute('data-record-id', recordId);
            form.setAttribute('data-action', 'update');

            // Update the submit button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Update Education';
        }

        // Function to open edit modal for skills
        function openEditSkillsModal(row) {
            const cells = row.querySelectorAll('td');
            const recordId = row.getAttribute('data-id');

            // Set values in the form
            document.getElementById('skill-name').value = cells[0].textContent;
            // Extract rating from text (e.g., "4.8/5.0" -> "4.8")
            const ratingText = cells[1].textContent;
            const rating = parseFloat(ratingText.split('/')[0]);
            document.getElementById('skill-rating').value = rating;
            document.getElementById('skill-rating-value').textContent = rating + '/5.0';

            // Show the modal
            const modal = document.getElementById('add-skills-modal');
            modal.style.display = 'flex';

            // Change form submission to update instead of add
            const form = document.getElementById('skills-form');
            form.setAttribute('data-record-id', recordId);
            form.setAttribute('data-action', 'update');

            // Update the submit button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Update Skill';
        }

        // Function to open edit modal for achievement
        function openEditAchievementModal(row) {
            const cells = row.querySelectorAll('td');
            const recordId = row.getAttribute('data-id');
            const description = row.getAttribute('data-description') || '';

            // Set values in the form
            document.getElementById('achievement-title').value = cells[0].textContent;
            document.getElementById('achievement-organization').value = cells[1].textContent;
            document.getElementById('achievement-date').value = cells[2].textContent;
            document.getElementById('achievement-rating').value = cells[3].textContent !== 'N/A' ? cells[3].textContent : '';
            document.getElementById('achievement-description').value = description;

            // Show the modal
            const modal = document.getElementById('add-achievement-modal');
            modal.style.display = 'flex';

            // Change form submission to update instead of add
            const form = document.getElementById('achievement-form');
            form.setAttribute('data-record-id', recordId);
            form.setAttribute('data-action', 'update');

            // Update the submit button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Update Achievement';
        }

        // Function to reset form to add mode
        function resetFormToDefault(form) {
            form.removeAttribute('data-record-id');
            form.removeAttribute('data-action');

            // Reset submit button text
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                if (form.id === 'experience-form') {
                    submitBtn.textContent = 'Add Experience';
                } else if (form.id === 'education-form') {
                    submitBtn.textContent = 'Add Education';
                } else if (form.id === 'skills-form') {
                    submitBtn.textContent = 'Add Skill';
                } else if (form.id === 'achievement-form') {
                    submitBtn.textContent = 'Add Achievement';
                }
            }
        }

        // Call the function to add event listeners
        addEditDeleteEventListeners();

        // Function to save package data
        function savePackage(packageName) {
            let title, subtitle, price, description, features;
            let isRecommended = false;

            // Get the card element for the specific package
            let cardElement;
            if (packageName === 'Static Package') {
                cardElement = document.querySelector('.col-md-4:first-child .card');
            } else if (packageName === 'Standard Package') {
                cardElement = document.querySelector('.col-md-4:nth-child(2) .card');
            } else if (packageName === 'Premium Package') {
                cardElement = document.querySelector('.col-md-4:last-child .card');
            }

            if (cardElement) {
                // Get all form groups within the card
                const formGroups = cardElement.querySelectorAll('.form-group');

                // Extract values based on the order of form groups
                if (formGroups.length >= 5) { // Ensure we have enough form groups
                    title = formGroups[0].querySelector('input') ? formGroups[0].querySelector('input').value : '';
                    subtitle = formGroups[1].querySelector('input') ? formGroups[1].querySelector('input').value : '';
                    price = formGroups[2].querySelector('input') ? formGroups[2].querySelector('input').value : '';
                    description = formGroups[3].querySelector('textarea') ? formGroups[3].querySelector('textarea').value : '';
                    features = formGroups[4].querySelector('textarea') ? formGroups[4].querySelector('textarea').value : '';
                }

                // Handle recommended checkbox for Standard Package
                if (packageName === 'Standard Package') {
                    isRecommended = document.getElementById('recommended-standard') ? document.getElementById('recommended-standard').checked : false;
                }
            }

            const packageData = {
                package_name: packageName,
                title: title,
                subtitle: subtitle,
                price: price,
                description: description,
                features: features,
                is_recommended: isRecommended
            };

            fetch('AdminApi/auth/addPricing.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(packageData)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the package.');
                });
        }

        // Function to toggle recommended status
        function toggleRecommended(checkbox) {
            // This function handles the recommended checkbox change
            // The status is captured in the savePackage function
        }

        // Function to fetch and populate pricing data
        function fetchPricingData() {
            fetch('AdminApi/auth/fetchPricing.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const packages = data.data;
                        packages.forEach(packageData => {
                            populatePackageData(packageData);
                        });
                    } else {
                        console.error('Error fetching pricing data:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Function to populate package data in the form
        function populatePackageData(packageData) {
            let cardElement;

            if (packageData.package_name === 'Static Package') {
                cardElement = document.querySelector('.col-md-4:first-child .card');
            } else if (packageData.package_name === 'Standard Package') {
                cardElement = document.querySelector('.col-md-4:nth-child(2) .card');
            } else if (packageData.package_name === 'Premium Package') {
                cardElement = document.querySelector('.col-md-4:last-child .card');
            }

            if (cardElement) {
                const formGroups = cardElement.querySelectorAll('.form-group');

                if (formGroups.length >= 5) {
                    // Populate title
                    if (formGroups[0].querySelector('input')) {
                        formGroups[0].querySelector('input').value = packageData.title || '';
                    }

                    // Populate subtitle
                    if (formGroups[1].querySelector('input')) {
                        formGroups[1].querySelector('input').value = packageData.subtitle || '';
                    }

                    // Populate price
                    if (formGroups[2].querySelector('input')) {
                        formGroups[2].querySelector('input').value = packageData.price || '';
                    }

                    // Populate description
                    if (formGroups[3].querySelector('textarea')) {
                        formGroups[3].querySelector('textarea').value = packageData.description || '';
                    }

                    // Populate features
                    if (formGroups[4].querySelector('textarea')) {
                        formGroups[4].querySelector('textarea').value = packageData.features || '';
                    }

                    // Handle recommended checkbox for Standard Package
                    if (packageData.package_name === 'Standard Package') {
                        const recommendedCheckbox = document.getElementById('recommended-standard');
                        if (recommendedCheckbox) {
                            recommendedCheckbox.checked = packageData.is_recommended == 1;
                        }
                    }
                }
            }
        }

        // Load pricing data when the page loads
        document.addEventListener('DOMContentLoaded', function () {
            fetchPricingData();

        });
    </script>
    <!-- Add Category Modal -->
    <div id="add-category-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Category</h3>
                <span class="close" id="close-category-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="category-form">
                    <div class="form-group">
                        <label for="category-name">Category Name *</label>
                        <input type="text" id="category-name" class="form-control" required placeholder="Enter category name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancel-category">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitCategory()">Add Category</button>
            </div>
        </div>
    </div>
    
    <script>
        // Function to submit category
        async function submitCategory() {
            const categoryName = document.getElementById('category-name').value;
            
            if (!categoryName.trim()) {
                alert('Please enter a category name');
                return;
            }
            
            // Prepare data to send to server
            const categoryData = {
                name: categoryName,
                description: '' // Can be expanded later if needed
            };
            
            try {
                // Send data to server
                const response = await fetch('./AdminApi/auth/AwesomeClientsAdCategory.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(categoryData)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success notification
                    if (typeof showNotification === 'function') {
                        showNotification(result.message, 'success');
                    } else {
                        alert(result.message);
                    }
                    
                    // Close the modal and reset the form
                    document.getElementById('add-category-modal').style.display = 'none';
                    document.getElementById('category-form').reset();
                } else {
                    // Show error message
                    if (typeof showNotification === 'function') {
                        showNotification(result.message, 'error');
                    } else {
                        alert('Error: ' + result.message);
                    }
                }
            } catch (error) {
                console.error('Error adding category:', error);
                if (typeof showNotification === 'function') {
                    showNotification('Network error. Please try again.', 'error');
                } else {
                    alert('Network error. Please try again.');
                }
            }
        }
        
        // Add Category button functionality
        (function () {
            const addCategoryBtn = document.getElementById('add-category-btn');
            const categoryModal = document.getElementById('add-category-modal');
            const closeCategoryModalBtn = document.getElementById('close-category-modal');
            const cancelCategoryBtn = document.getElementById('cancel-category');
            
            if (addCategoryBtn && categoryModal) {
                addCategoryBtn.addEventListener('click', function () {
                    // Reset form fields
                    if (document.getElementById('category-form')) document.getElementById('category-form').reset();
                    categoryModal.style.display = 'flex';
                });
            }
            
            if (closeCategoryModalBtn) closeCategoryModalBtn.addEventListener('click', () => {
                categoryModal.style.display = 'none';
            });
            if (cancelCategoryBtn) cancelCategoryBtn.addEventListener('click', () => {
                categoryModal.style.display = 'none';
            });
            
            // Close modal if clicked outside content
            window.addEventListener('click', function (event) {
                if (event.target === categoryModal) {
                    categoryModal.style.display = 'none';
                }
            });
        }());
    </script>
    
    <!-- Add Awesome Client Modal -->
    <div id="add-awesome-client-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Awesome Client</h3>
                <span class="close" id="close-awesome-client-modal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="awesome-client-form">
                    <div class="form-group">
                        <label for="client-category">Category *</label>
                        <select id="client-category" class="form-control" required>
                            <option value="">Select a category</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="client-name">Client Name *</label>
                        <input type="text" id="client-name" class="form-control" required placeholder="Enter client name">
                    </div>
                    
                    <div class="form-group">
                        <label for="client-testimonial">Testimonial Text</label>
                        <textarea id="client-testimonial" class="form-control" rows="3" placeholder="Enter testimonial text"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="client-rating">Rating (0-5)</label>
                        <input type="number" id="client-rating" class="form-control" min="0" max="5" step="0.1" placeholder="Enter rating (e.g. 4.5)">
                    </div>
                    
                    <div class="form-group">
                        <label for="client-logo">Logo Image</label>
                        <input type="file" id="client-logo" class="form-control" accept="image/*">
                    </div>
                    
                    <div class="form-group">
                        <label for="client-status">Status</label>
                        <select id="client-status" class="form-control">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancel-awesome-client">Cancel</button>
                <button type="button" class="btn btn-success" onclick="submitAwesomeClient()">Add Client</button>
            </div>
        </div>
    </div>
    
    <script>
        // Function to submit awesome client
        async function submitAwesomeClient() {
            // Get form values
            const category = document.getElementById('client-category').value;
            const clientName = document.getElementById('client-name').value;
            const testimonialText = document.getElementById('client-testimonial').value;
            const rating = document.getElementById('client-rating').value;
            const status = document.getElementById('client-status').value;
            
            // Validate required fields
            if (!category.trim()) {
                alert('Please enter a category');
                return;
            }
            
            if (!clientName.trim()) {
                alert('Please enter a client name');
                return;
            }
            
            // Prepare form data (using FormData to handle file upload)
            const formData = new FormData();
            formData.append('category', category);
            formData.append('client_name', clientName);
            formData.append('testimonial_text', testimonialText);
            formData.append('status', status);
            
            if (rating) {
                formData.append('rating', parseFloat(rating));
            }
            
            // Add logo file if selected
            const logoFile = document.getElementById('client-logo').files[0];
            console.log('Logo file selected:', logoFile);
            if (logoFile) {
                console.log('Appending logo file to form data:', logoFile.name);
                formData.append('logo', logoFile);
                
                // Debug: Log form data contents
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
            } else {
                console.log('No logo file selected');
            }
            
            try {
                // Send data to server
                const response = await fetch('./AdminApi/auth/AddAwesomeClients.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Show success notification
                    if (typeof showNotification === 'function') {
                        showNotification(result.message, 'success');
                    } else {
                        alert(result.message);
                    }
                    
                    // Close the modal and reset the form
                    document.getElementById('add-awesome-client-modal').style.display = 'none';
                    document.getElementById('awesome-client-form').reset();
                } else {
                    // Show error message
                    if (typeof showNotification === 'function') {
                        showNotification(result.message, 'error');
                    } else {
                        alert('Error: ' + result.message);
                    }
                }
            } catch (error) {
                console.error('Error adding awesome client:', error);
                if (typeof showNotification === 'function') {
                    showNotification('Network error. Please try again.', 'error');
                } else {
                    alert('Network error. Please try again.');
                }
            }
        }
        
        // Function to load categories into dropdown
        async function loadCategories() {
            try {
                const response = await fetch('./AdminApi/auth/fetchCategories.php');
                const result = await response.json();
                
                const categorySelect = document.getElementById('client-category');
                
                if (result.success && result.data.length > 0) {
                    // Clear existing options except the first one
                    categorySelect.innerHTML = '<option value="">Select a category</option>';
                    
                    // Add categories to dropdown
                    result.data.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.name;
                        option.textContent = category.name;
                        categorySelect.appendChild(option);
                    });
                } else {
                    categorySelect.innerHTML = '<option value="">No categories available</option>';
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                const categorySelect = document.getElementById('client-category');
                categorySelect.innerHTML = '<option value="">Error loading categories</option>';
            }
        }
        
        // Add Awesome Client button functionality
        (function () {
            const addAwesomeClientBtn = document.getElementById('add-awesome-client-btn');
            const awesomeClientModal = document.getElementById('add-awesome-client-modal');
            const closeAwesomeClientModalBtn = document.getElementById('close-awesome-client-modal');
            const cancelAwesomeClientBtn = document.getElementById('cancel-awesome-client');
            
            if (addAwesomeClientBtn && awesomeClientModal) {
                addAwesomeClientBtn.addEventListener('click', function () {
                    // Reset form fields
                    if (document.getElementById('awesome-client-form')) document.getElementById('awesome-client-form').reset();
                    
                    // Load categories when modal opens
                    loadCategories();
                    
                    awesomeClientModal.style.display = 'flex';
                });
            }
            
            if (closeAwesomeClientModalBtn) closeAwesomeClientModalBtn.addEventListener('click', () => {
                awesomeClientModal.style.display = 'none';
            });
            if (cancelAwesomeClientBtn) cancelAwesomeClientBtn.addEventListener('click', () => {
                awesomeClientModal.style.display = 'none';
            });
            
            // Close modal if clicked outside content
            window.addEventListener('click', function (event) {
                if (event.target === awesomeClientModal) {
                    awesomeClientModal.style.display = 'none';
                }
            });
        }());
    </script>
    <script src="script.js"></script>
</body>

</html>