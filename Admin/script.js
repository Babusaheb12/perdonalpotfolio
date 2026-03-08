// Admin Panel JavaScript Functionality
console.log('script.js loading started...');

// Check if we're on the login page
const isLoginPage = window.location.pathname.includes('login.php');

if (isLoginPage) {
    // Login page functionality
    document.addEventListener('DOMContentLoaded', function () {
        const loginForm = document.getElementById('login-form');

        if (loginForm) {
            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                const emailError = document.getElementById('email-error');
                const passwordError = document.getElementById('password-error');

                // Simple validation
                let isValid = true;

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    emailError.classList.add('show');
                    isValid = false;
                } else {
                    emailError.classList.remove('show');
                }

                // Password validation
                if (password.length < 6) {
                    passwordError.classList.add('show');
                    isValid = false;
                } else {
                    passwordError.classList.remove('show');
                }

                if (isValid) {
                    // Send credentials to the server for authentication
                    fetch('./AdminApi/auth/login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            email: email,
                            password: password
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('Login successful! Redirecting to admin panel...', 'success');
                                // Small delay to ensure notification is seen before redirect
                                setTimeout(() => {
                                    window.location.replace('index.php');
                                }, 1000); // 1000ms delay to ensure proper redirect after notification
                            } else {
                                showNotification(data.message || 'Login failed', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Login error:', error);
                            showNotification('Network error. Please try again.', 'error');
                        });
                }
            });

            // Add input validation as user types
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');

            if (emailInput) {
                emailInput.addEventListener('input', function () {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    const emailError = document.getElementById('email-error');
                    if (emailRegex.test(this.value)) {
                        emailError.classList.remove('show');
                    }
                });
            }

            if (passwordInput) {
                passwordInput.addEventListener('input', function () {
                    const passwordError = document.getElementById('password-error');
                    if (this.value.length >= 6) {
                        passwordError.classList.remove('show');
                    }
                });
            }

            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            if (togglePassword) {
                togglePassword.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }
        }
    });
} else {
    // Navigation functionality
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = document.querySelectorAll('.menu-item');
        const contentSections = document.querySelectorAll('.content-section');
        const sidebar = document.querySelector('.sidebar');

        // Navigation between sections
        menuItems.forEach(item => {
            item.addEventListener('click', function () {
                const sectionId = this.getAttribute('data-section');

                // Update active menu item
                menuItems.forEach(menuItem => menuItem.classList.remove('active'));
                this.classList.add('active');

                // Show selected section
                contentSections.forEach(section => {
                    section.classList.remove('active');
                    if (section.id === sectionId) {
                        section.classList.add('active');
                    }
                });

                // Special handling for Lead Management
                if (sectionId === 'leads' && typeof loadLeads === 'function') {
                    console.log('Navigation: leads section selected, calling loadLeads()');
                    loadLeads(document.getElementById('lead-status')?.value || 'all');
                }
            });
        });

        // Initialize services section if it exists
        const updateServicesBtn = document.getElementById('update-services-btn');
        if (updateServicesBtn) {
            updateServicesBtn.addEventListener('click', function () {
                showNotification('Services section updated successfully!', 'success');
            });
        }

        // Profile image preview functionality
        const profileImageInput = document.getElementById('profile-image');
        const profilePreview = document.getElementById('profile-preview');

        if (profileImageInput) {
            profileImageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        profilePreview.src = event.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Form submission handlers with validation
        const heroForm = document.getElementById('hero-form');
        if (heroForm) {
            heroForm.addEventListener('submit', function (e) {
                e.preventDefault();
                showNotification('Hero form submitted', 'success');
            });
        }

        // Handle general settings form
        const generalSettingsForm = document.getElementById('general-settings-form');
        if (generalSettingsForm) {
            generalSettingsForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const title = document.getElementById('site-title').value;
                const description = document.getElementById('site-description').value;

                if (title.trim() === '' || description.trim() === '') {
                    alert('Please fill in all required fields');
                    return;
                }

                // Simulate saving
                showNotification('Settings saved successfully!', 'success');
                // Auto-refresh functionality after successful operation
                setTimeout(() => {
                    // In a real implementation, this would refresh the content from the server
                }, 1000);
            });
        }

        // Admin management functionality: open Add Admin modal and handle submission
        const addAdminBtn = document.getElementById('add-admin-btn');
        const addAdminModal = document.getElementById('add-admin-modal');
        const closeAdminModalBtn = document.getElementById('close-admin-modal');
        const cancelAdminBtn = document.getElementById('cancel-admin');
        const adminForm = document.getElementById('admin-form');

        if (addAdminBtn && addAdminModal) {
            addAdminBtn.addEventListener('click', function () {
                // Reset form fields
                if (adminForm) adminForm.reset();
                addAdminModal.style.display = 'flex';
            });
        }

        if (closeAdminModalBtn) closeAdminModalBtn.addEventListener('click', () => addAdminModal.style.display = 'none');
        if (cancelAdminBtn) cancelAdminBtn.addEventListener('click', () => addAdminModal.style.display = 'none');

        // Close modal if clicked outside content
        window.addEventListener('click', function (event) {
            if (event.target === addAdminModal) {
                addAdminModal.style.display = 'none';
            }
        });

        // Load admin list from server and render into #admin-list
        async function loadAdminList() {
            try {
                console.log('Fetching admin list...');
                const res = await fetch('./AdminApi/auth/fetchAdmin.php', { method: 'GET' });  /// this is api in fetch Admin.php
                const data = await res.json();
                console.log('fetchAdmin response:', data);

                if (data && data.success && Array.isArray(data.data)) {
                    const adminListEl = document.getElementById('admin-list');
                    adminListEl.innerHTML = '';
                    data.data.forEach(admin => {
                        const tr = document.createElement('tr');
                        tr.setAttribute('data-id', admin.id);
                        const statusBadge = admin.status === 'active'
                            ? '<span class="badge badge-success">Active</span>'
                            : '<span class="badge badge-warning">Inactive</span>';
                        tr.innerHTML = `
                        <td>${admin.email}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-action="toggle-status">Update</button>
                            <button class="btn btn-sm btn-danger" data-action="delete-admin">Delete</button>
                        </td>`;
                        adminListEl.appendChild(tr);
                    });
                } else {
                    console.warn('Unexpected fetchAdmin response', data);
                }
            } catch (err) {
                console.error('Error loading admin list:', err);
            }
        }

        // Handle admin form submission via fetch to the API and log request/response in console
        // Fetch initial admin list on load
        loadAdminList();
        if (adminForm) {
            adminForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                const email = document.getElementById('admin-email').value.trim();
                const password = document.getElementById('admin-password').value;
                const status = document.getElementById('admin-status').value;

                // Basic client-side validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showNotification('Please enter a valid email address', 'error');
                    return;
                }
                if (password.length < 6) {
                    showNotification('Password must be at least 6 characters', 'error');
                    return;
                }

                const payload = { email, password, status };

                // Log request to console (redact password in displayed object)
                console.log('Sending add-admin request:', { email, password: '***REDACTED***', status });

                const submitBtn = adminForm.querySelector('button[type="submit"]');
                if (submitBtn) submitBtn.disabled = true;

                try {
                    const res = await fetch('./AdminApi/auth/addAdmin.php', { /// fixed: relative path for add admin API
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload)
                    });

                    const text = await res.text();
                    let data;
                    try { data = JSON.parse(text); } catch (err) { data = text; }

                    console.log('add-admin response status:', res.status);
                    console.log('add-admin response body:', data);

                    if (res.status === 201) {
                        showNotification('Admin created successfully', 'success');
                        // Refresh admin list from server to keep UI consistent
                        try {
                            await loadAdminList();
                        } catch (uiErr) {
                            console.warn('Could not refresh admin list UI:', uiErr);
                        }

                        // Close modal
                        addAdminModal.style.display = 'none';
                        adminForm.reset();
                    } else if (res.status === 409) {
                        showNotification((data && data.message) || 'Email already registered', 'error');
                    } else if (res.status === 422) {
                        showNotification((data && data.message) || 'Validation error', 'error');
                    } else {
                        showNotification((data && data.message) || 'Server error', 'error');
                    }

                } catch (err) {
                    console.error('Network or server error when adding admin:', err);
                    showNotification('Network error. Check console for details.', 'error');
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                }
            });
        }

        // Handle admin actions (update status and delete)
        const adminList = document.getElementById('admin-list');
        if (adminList) {
            adminList.addEventListener('click', async function (e) {
                const target = e.target.closest('button');
                if (target) {
                    const action = target.getAttribute('data-action');
                    const row = target.closest('tr');
                    const email = row.cells[0].textContent;

                    if (action === 'toggle-status') {
                        // Toggle status server-side via updateAdmin.php
                        const statusSpan = row.querySelector('.badge');
                        const current = statusSpan && statusSpan.textContent.trim().toLowerCase() === 'active' ? 'active' : 'inactive';
                        const newStatus = current === 'active' ? 'inactive' : 'active';
                        const id = row.getAttribute('data-id') ? parseInt(row.getAttribute('data-id')) : null;

                        if (!id) {
                            showNotification('Missing admin id; cannot update status', 'error');
                            return;
                        }

                        console.log('Updating admin status:', { id, from: current, to: newStatus });
                        try {
                            const resp = await fetch('./AdminApi/auth/updateAdmin.php', { /// this is api in updateAdmin.php
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ id: id, status: newStatus })
                            });

                            const text = await resp.text();
                            let data;
                            try { data = JSON.parse(text); } catch (err) { data = text; }
                            console.log('updateAdmin response status:', resp.status, 'body:', data);

                            if (resp.ok && data && data.success) {
                                // Update UI badge
                                if (newStatus === 'active') {
                                    statusSpan.className = 'badge badge-success';
                                    statusSpan.textContent = 'Active';
                                    showNotification(`Admin ${email} status updated to Active`, 'success');
                                } else {
                                    statusSpan.className = 'badge badge-warning';
                                    statusSpan.textContent = 'Inactive';
                                    showNotification(`Admin ${email} status updated to Inactive`, 'info');
                                }
                                // Optionally refresh the whole list to sync
                                try { await loadAdminList(); } catch (e) { /* ignore */ }
                            } else {
                                showNotification((data && data.message) || 'Failed to update status', 'error');
                            }
                        } catch (err) {
                            console.error('Network error updating admin status:', err);
                            showNotification('Network error. Check console for details.', 'error');
                        }
                    } else if (action === 'delete-admin') {
                        if (!confirm(`Are you sure you want to delete admin ${email}?`)) return;

                        const id = row.getAttribute('data-id') ? parseInt(row.getAttribute('data-id')) : null;

                        // If no id is present (static/demo row), just remove client-side
                        if (!id) {
                            row.style.animation = 'fadeOut 0.5s forwards';
                            setTimeout(() => {
                                row.remove();
                                showNotification(`Admin ${email} deleted locally`, 'success');
                            }, 500);
                            return;
                        }

                        console.log('Requesting delete for admin id:', id);
                        try {
                            const resp = await fetch('./AdminApi/auth/deleteAdmin.php', { //// this is api in deleteAdmin.php
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json' },
                                body: JSON.stringify({ id: id })
                            });

                            const text = await resp.text();
                            let data;
                            try { data = JSON.parse(text); } catch (err) { data = text; }
                            console.log('deleteAdmin response:', resp.status, data);

                            if (resp.ok && data && data.success) {
                                showNotification(`Admin ${email} deleted`, 'success');
                                // Refresh list from server
                                try { await loadAdminList(); } catch (e) { /* ignore */ }
                            } else {
                                showNotification((data && data.message) || 'Failed to delete admin', 'error');
                            }
                        } catch (err) {
                            console.error('Network error deleting admin:', err);
                            showNotification('Network error. Check console for details.', 'error');
                        }
                    }
                }
            });
        }

        // Add event listeners to all save buttons
        const saveButtons = document.querySelectorAll('.btn-primary');
        saveButtons.forEach(button => {
            if (!button.closest('#hero-form') && !button.closest('#settings-form')) {
                button.addEventListener('click', function () {
                    if (this.textContent.includes('Save') || this.textContent.includes('Add')) {
                        showNotification('Changes saved successfully!', 'success');
                    }
                });
            }
        });

        // Add event listeners to delete buttons
        const deleteButtons = document.querySelectorAll('.btn-danger');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                if (this.textContent.includes('Delete') || this.querySelector('i')?.classList.contains('fa-trash')) {
                    if (confirm('Are you sure you want to delete this item?')) {
                        const row = this.closest('tr');
                        const galleryItem = this.closest('.gallery-item');

                        if (row) {
                            row.style.animation = 'fadeOut 0.5s forwards';
                            setTimeout(() => {
                                row.remove();
                                showNotification('Item deleted successfully!', 'success');
                            }, 500);
                        } else if (galleryItem) {
                            galleryItem.style.animation = 'fadeOut 0.5s forwards';
                            setTimeout(() => {
                                galleryItem.remove();
                                showNotification('Project deleted successfully!', 'success');
                            }, 500);
                        } else {
                            showNotification('Item deleted successfully!', 'success');
                        }
                    }
                }
            });
        });

        // Portfolio filtering functionality
        const filterCheckboxes = document.querySelectorAll('.checkbox-group input[type="checkbox"]');
        const portfolioItems = document.querySelectorAll('.gallery-item');

        if (filterCheckboxes.length > 0 && portfolioItems.length > 0) {
            filterCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const filterValue = this.value;

                    portfolioItems.forEach(item => {
                        const itemType = item.getAttribute('data-type');

                        if (this.checked) {
                            if (itemType.includes(filterValue) ||
                                (filterValue === 'motion' && itemType === 'motion') ||
                                (filterValue === 'video' && itemType === 'video') ||
                                (filterValue === 'animation' && itemType === 'motion') ||
                                (filterValue === 'strategy' && itemType === 'strategy')) {
                                item.style.display = 'block';
                            }
                        } else {
                            if (itemType.includes(filterValue) ||
                                (filterValue === 'motion' && itemType === 'motion') ||
                                (filterValue === 'video' && itemType === 'video') ||
                                (filterValue === 'animation' && itemType === 'motion') ||
                                (filterValue === 'strategy' && itemType === 'strategy')) {
                                item.style.display = 'none';
                            }
                        }
                    });
                });
            });
        }

        // Add project button functionality
        const addProjectBtn = document.getElementById('add-project-btn');
        const modal = document.getElementById('add-project-modal');
        const closeBtn = document.getElementById('close-project-modal');
        const cancelBtn = document.getElementById('cancel-project');
        const projectForm = document.getElementById('project-form');
        const projectCategory = document.getElementById('project-category');
        const projectType = document.getElementById('project-type');
        const videoUrlGroup = document.getElementById('video-url-group');
        const videoDescriptionGroup = document.getElementById('video-description-group');
        const videoUploadGroup = document.getElementById('video-upload-group');
        const projectImage = document.getElementById('project-image');
        const projectPreview = document.getElementById('project-preview');

        // Show modal when add project button is clicked
        if (addProjectBtn) {
            addProjectBtn.addEventListener('click', function () {
                modal.style.display = 'flex';
                // Reset form
                projectForm.reset();
                projectPreview.style.display = 'none';
                // Check if video project type is selected and show video fields accordingly
                toggleVideoFields(projectType.value);
            });
        }

        // Close modal functions
        function closeModal() {
            modal.style.display = 'none';
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }

        // Close modal if clicked outside content
        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        // Toggle video fields based on project type selection
        if (projectType) {
            projectType.addEventListener('change', function () {
                toggleVideoFields(this.value);
            });
        }

        function toggleVideoFields(type) {
            if (type === 'video') {
                videoUrlGroup.style.display = 'block';
                videoDescriptionGroup.style.display = 'block';
                videoUploadGroup.style.display = 'block';
            } else {
                videoUrlGroup.style.display = 'none';
                videoDescriptionGroup.style.display = 'none';
                videoUploadGroup.style.display = 'none';
            }
        }

        // Preview image when selected
        if (projectImage) {
            projectImage.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        projectPreview.src = event.target.result;
                        projectPreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Handle form submission
        if (projectForm) {
            projectForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Get form data
                const title = document.getElementById('project-title').value;
                const description = document.getElementById('project-description').value;
                const category = projectCategory.value;
                const projectTypeValue = projectType.value;
                const videoUrl = document.getElementById('video-url').value;
                const videoDescription = document.getElementById('video-description').value;
                const tags = document.getElementById('project-tags').value;
                const status = document.getElementById('project-status').value;
                const isFeatured = document.getElementById('project-featured').checked;
                const thumbnailFile = document.getElementById('project-image').files[0];

                if (!title || !category || !projectTypeValue || !thumbnailFile) {
                    showNotification('Please fill in all required fields and select a thumbnail image', 'error');
                    return;
                }

                // Create FormData object to send file and other data
                const formData = new FormData();
                formData.append('title', title);
                formData.append('description', description);
                formData.append('category', category);
                formData.append('project_type', projectTypeValue);
                formData.append('video_url', videoUrl);
                formData.append('video_description', videoDescription);
                formData.append('tags', tags);
                formData.append('is_featured', isFeatured ? 1 : 0);
                formData.append('status', status);
                formData.append('thumbnail', thumbnailFile);

                // Show loading state
                const submitBtn = projectForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Adding Project...';
                submitBtn.disabled = true;

                // Send the request to Addproject.php API
                fetch('./AdminApi/auth/Addproject.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message || 'Project added successfully!', 'success');

                            // Close modal and reset form
                            closeModal();
                            projectForm.reset();
                            projectPreview.style.display = 'none';
                            videoUrlGroup.style.display = 'none';
                            videoDescriptionGroup.style.display = 'none';

                            // Reset submit button to add mode
                            submitBtn.textContent = 'Add Project';
                            submitBtn.disabled = false;
                            delete submitBtn.dataset.editId;

                            // Refresh the project list to show the new project
                            loadProjects();
                        } else {
                            showNotification(data.message || 'Failed to add project', 'error');
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error adding project:', error);
                        showNotification('Network error. Please try again.', 'error');
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            });
        }

        // Function to load projects from server
        async function loadProjects() {
            try {
                // API call to fetch projects
                const response = await fetch('./AdminApi/auth/fetchProjects.php');
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message || 'Failed to fetch projects');
                }

                const projects = data.data || [];
                const tableBody = document.getElementById('portfolio-projects-table');
                tableBody.innerHTML = ''; // Clear existing content

                projects.forEach(project => {
                    const newRow = document.createElement('tr');

                    const featuredBadge = project.is_featured ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-warning">No</span>';
                    const statusBadge = project.status === 'active' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                    const videoLink = project.video_url ? `<a href="${project.video_url}" target="_blank">View Video</a>` : '-';

                    newRow.innerHTML = `
                    <td>${project.id}</td>
                    <td><img src="${project.thumbnail}" alt="Thumbnail" style="width: 60px; height: 60px; object-fit: cover;"></td>
                    <td>${project.title}</td>
                    <td>${project.description}</td>
                    <td>${project.category}</td>
                    <td>${project.project_type}</td>
                    <td>${videoLink}</td>
                    <td>${project.tags}</td>
                    <td>${project.likes}</td>
                    <td>${featuredBadge}</td>
                    <td>${statusBadge}</td>
                    <td>${project.created_at.split(' ')[0]}</td>
                    <td>
        <!-- <button class="btn btn-sm btn-primary edit-project-btn">Edit</button> -->
                        <button class="btn btn-sm btn-danger delete-project-btn">Delete</button>
                    </td>
                `;

                    tableBody.appendChild(newRow);

                    // Add event listeners to the buttons
                    const editBtn = newRow.querySelector('.edit-project-btn');
                    const deleteBtn = newRow.querySelector('.delete-project-btn');

                    if (editBtn) {
                        editBtn.addEventListener('click', function () {
                            // Load project data into the form for editing
                            document.getElementById('project-title').value = project.title;
                            document.getElementById('project-description').value = project.description;
                            document.getElementById('project-category').value = project.category;
                            document.getElementById('project-type').value = project.project_type;
                            document.getElementById('video-url').value = project.video_url || '';
                            document.getElementById('video-description').value = project.video_description || '';
                            document.getElementById('project-tags').value = project.tags;
                            document.getElementById('project-status').value = project.status;
                            document.getElementById('project-featured').checked = project.is_featured;

                            // Update the form to be in edit mode
                            const submitBtn = projectForm.querySelector('button[type="submit"]');
                            submitBtn.textContent = 'Update Project';

                            // Show modal for editing
                            modal.style.display = 'flex';

                            // Update toggleVideoFields based on selected type
                            toggleVideoFields(project.project_type);
                        });
                    }

                    if (deleteBtn) {
                        deleteBtn.addEventListener('click', function () {
                            if (confirm('Are you sure you want to delete this project?')) {
                                // Get project ID from the first cell of the row
                                const projectId = parseInt(newRow.cells[0].textContent);

                                if (!projectId) {
                                    showNotification('Project ID not found', 'error');
                                    return;
                                }

                                // Call the deleteProject API
                                fetch('./AdminApi/auth/deleteProject.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify({ id: projectId })
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Remove the row with animation
                                            newRow.style.animation = 'fadeOut 0.5s forwards';
                                            setTimeout(() => {
                                                newRow.remove();
                                                showNotification(data.message || 'Project deleted successfully!', 'success');
                                            }, 500);
                                        } else {
                                            showNotification(data.message || 'Failed to delete project', 'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error deleting project:', error);
                                        showNotification('Network error. Please try again.', 'error');
                                    });
                            }
                        });
                    }
                });

            } catch (error) {
                console.error('Error loading projects:', error);
                showNotification('Error loading projects: ' + error.message, 'error');
            }
        }

        // Load projects when the portfolio section becomes active
        const portfolioSection = document.getElementById('portfolio');
        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (portfolioSection.classList.contains('active')) {
                        loadProjects();
                    }
                }
            });
        });

        observer.observe(portfolioSection, { attributes: true });

        // Also load projects when portfolio menu item is clicked
        const portfolioMenuItem = document.querySelector('[data-section="portfolio"]');
        if (portfolioMenuItem) {
            portfolioMenuItem.addEventListener('click', function () {
                setTimeout(() => {
                    if (portfolioSection.classList.contains('active')) {
                        loadProjects();
                    }
                }, 100);
            });
        }

        // Resume/Skills/Education Management
        const resumeModal = document.getElementById('resume-modal');
        const closeResumeModalBtn = document.getElementById('close-resume-modal');
        const cancelResumeBtn = document.getElementById('cancel-resume');
        const resumeForm = document.getElementById('resume-form');
        const addExperienceBtn = document.getElementById('add-experience-btn');
        const addSkillsBtn = document.getElementById('add-skills-btn');
        const addEducationBtn = document.getElementById('add-education-btn');

        // Resume Modal Functions
        function openResumeModal(sectionType) {
            resumeModal.style.display = 'flex';
            document.getElementById('resume-section-type').value = sectionType;
            resumeForm.reset();

            // Configure modal based on section type
            const modalTitle = document.getElementById('resume-modal-title');
            const orgGroup = document.getElementById('resume-org-group');
            const orgLabel = document.getElementById('resume-org-label');
            const ratingGroup = document.getElementById('resume-rating-group');
            const skillGroup = document.getElementById('resume-skill-group');
            const startGroup = document.getElementById('resume-start-group');
            const endGroup = document.getElementById('resume-end-group');

            switch (sectionType) {
                case 'experience':
                    modalTitle.textContent = 'Add Experience';
                    orgGroup.style.display = 'block';
                    orgLabel.textContent = 'Company/Organization';
                    ratingGroup.style.display = 'block';
                    skillGroup.style.display = 'none';
                    startGroup.style.display = 'block';
                    endGroup.style.display = 'block';
                    break;
                case 'skill':
                    modalTitle.textContent = 'Add New Professional Skill';
                    orgGroup.style.display = 'none';
                    ratingGroup.style.display = 'block';
                    skillGroup.style.display = 'block';
                    startGroup.style.display = 'none';
                    endGroup.style.display = 'none';
                    break;
                case 'education':
                    modalTitle.textContent = 'Add Education';
                    orgGroup.style.display = 'block';
                    orgLabel.textContent = 'Institution/University';
                    ratingGroup.style.display = 'block';
                    skillGroup.style.display = 'none';
                    startGroup.style.display = 'block';
                    endGroup.style.display = 'block';
                    break;
            }
        }

        function closeResumeModal() {
            resumeModal.style.display = 'none';
        }

        if (addExperienceBtn) {
            addExperienceBtn.addEventListener('click', () => openResumeModal('experience'));
        }

        if (addSkillsBtn) {
            addSkillsBtn.addEventListener('click', () => openResumeModal('skill'));
        }

        if (addEducationBtn) {
            addEducationBtn.addEventListener('click', () => openResumeModal('education'));
        }

        if (closeResumeModalBtn) {
            closeResumeModalBtn.addEventListener('click', closeResumeModal);
        }

        if (cancelResumeBtn) {
            cancelResumeBtn.addEventListener('click', closeResumeModal);
        }

        // Close modal on outside click
        window.addEventListener('click', function (event) {
            if (event.target === resumeModal) {
                closeResumeModal();
            }
        });

        // Handle Resume Form Submission
        if (resumeForm) {
            resumeForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = {
                    section_type: document.getElementById('resume-section-type').value,
                    title: document.getElementById('resume-title').value,
                    organization: document.getElementById('resume-organization').value,
                    start_year: document.getElementById('resume-start').value,
                    end_year: document.getElementById('resume-end').value,
                    rating: document.getElementById('resume-rating').value,
                    skill_level: document.getElementById('resume-skill-level').value,
                    description: document.getElementById('resume-description').value,
                    status: document.getElementById('resume-status').value
                };

                fetch('./AdminApi/auth/addResumeRecord.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Record added successfully!', 'success');
                            closeResumeModal();
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification(data.message || 'Failed to add record', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('An error occurred. Please try again.', 'error');
                    });
            });
        }

        // Menu toggle logic
        const menuToggle = document.createElement('div');
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        menuToggle.classList.add('menu-toggle');
        menuToggle.style.cssText = `
        display: none;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1000;
        background: var(--primary-color);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    `;

        if (window.innerWidth <= 768) {
            document.body.appendChild(menuToggle);
            menuToggle.style.display = 'flex';

            menuToggle.addEventListener('click', function () {
                sidebar.classList.toggle('collapsed');
                document.querySelector('.main-content').classList.toggle('shifted');
            });
        }

        // Responsive handling
        window.addEventListener('resize', function () {
            if (window.innerWidth <= 768) {
                if (!document.querySelector('.menu-toggle')) {
                    document.body.appendChild(menuToggle);
                    menuToggle.style.display = 'flex';
                }
            } else {
                menuToggle.style.display = 'none';
                sidebar.classList.remove('collapsed');
                document.querySelector('.main-content').classList.remove('shifted');
            }
        });

        // Blog Management
        const blogModal = document.getElementById('blog-modal');
        const closeBlogModalBtn = document.getElementById('close-blog-modal');
        const cancelBlogBtn = document.getElementById('cancel-blog');
        const blogForm = document.getElementById('blog-form');
        const addBlogBtn = document.getElementById('add-blog-btn');
        const thumbnailInput = document.getElementById('blog-thumbnail');
        const thumbnailPreview = document.getElementById('thumbnail-preview');
        const bannerInput = document.getElementById('blog-banner');
        const bannerPreview = document.getElementById('banner-preview');
        const blogTitle = document.getElementById('blog-title');
        const blogSlug = document.getElementById('blog-slug');
        const shortDesc = document.getElementById('blog-shortdesc');
        const charCounter = document.querySelector('#blog-shortdesc').nextElementSibling;
        const blogContent = document.getElementById('blog-content');

        // Initialize character counter for short description
        if (shortDesc) {
            shortDesc.addEventListener('input', function () {
                const maxLength = 200;
                const currentLength = this.value.length;
                charCounter.textContent = `${currentLength}/${maxLength}`;

                if (currentLength > maxLength * 0.9) {
                    charCounter.style.color = '#dc3545';
                } else {
                    charCounter.style.color = '#6c757d';
                }
            });
        }

        // Auto-generate slug from title
        if (blogTitle && blogSlug) {
            blogTitle.addEventListener('input', function () {
                if (!blogSlug.dataset.userEdited) {
                    const title = this.value;
                    const slug = title.toLowerCase()
                        .replace(/[\s\W-]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    blogSlug.value = slug;
                }
            });

            blogSlug.addEventListener('input', function () {
                this.dataset.userEdited = 'true';
            });
        }

        // Thumbnail preview
        if (thumbnailInput && thumbnailPreview) {
            thumbnailInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        thumbnailPreview.src = event.target.result;
                        thumbnailPreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Banner preview
        if (bannerInput && bannerPreview) {
            bannerInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        bannerPreview.src = event.target.result;
                        bannerPreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        let editor;

        // Open blog modal
        function openBlogModal(isEdit = false, blogData = null) {
            blogModal.style.display = 'flex';

            // Initialize CKEditor after modal is displayed
            setTimeout(() => {
                if (!window.ckeditorInitialized) {
                    ClassicEditor
                        .create(document.querySelector('#blog-content'), {
                            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'],
                            image: {
                                toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:alignRight', 'imageStyle:alignCenter', 'imageStyle:full'],
                                styles: ['full', 'side', 'alignLeft', 'alignCenter', 'alignRight']
                            },
                            table: {
                                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                            },
                            licenseKey: '',
                        })
                        .then(newEditor => {
                            editor = newEditor;
                            window.ckeditorInitialized = true;

                            // Set content after editor is ready
                            if (isEdit && blogData) {
                                editor.setData(blogData.content || '');
                            }
                        })
                        .catch(error => {
                            console.error('There was a problem initializing the CKEditor:', error);
                        });
                } else {
                    // If editor is already initialized, set content directly
                    if (isEdit && blogData && editor) {
                        editor.setData(blogData.content || '');
                    } else if (editor) {
                        editor.setData('');
                    }
                }
            }, 100); // Small delay to ensure modal is visible

            if (isEdit && blogData) {
                document.getElementById('blog-modal-title').textContent = 'Edit Blog Post';
                document.getElementById('blog-title').value = blogData.title || '';
                document.getElementById('blog-subtitle').value = blogData.subtitle || '';
                document.getElementById('blog-slug').value = blogData.slug || '';
                document.getElementById('blog-category').value = blogData.category || '';
                document.getElementById('blog-author').value = blogData.author || '';
                document.getElementById('blog-readtime').value = blogData.readTime || '';
                document.getElementById('blog-status').value = blogData.status || 'active';
                document.getElementById('blog-shortdesc').value = blogData.shortDesc || '';

                // Update character counter
                const maxLength = 200;
                const currentLength = document.getElementById('blog-shortdesc').value.length;
                charCounter.textContent = `${currentLength}/${maxLength}`;

                // Set edit mode flag
                blogForm.dataset.editId = blogData.id;
                document.querySelector('#blog-form button[type="submit"]').textContent = 'Update Blog Post';
            } else {
                document.getElementById('blog-modal-title').textContent = 'Add New Blog Post';
                blogForm.reset();

                // Reset previews
                if (thumbnailPreview) thumbnailPreview.style.display = 'none';
                if (bannerPreview) bannerPreview.style.display = 'none';

                // Reset character counter
                charCounter.textContent = '0/200';
                charCounter.style.color = '#6c757d';

                // Reset edit mode flag
                delete blogForm.dataset.editId;
                document.querySelector('#blog-form button[type="submit"]').textContent = 'Save Blog Post';

                // Reset slug user edited flag
                if (blogSlug) blogSlug.dataset.userEdited = '';
            }
        }

        // Close blog modal
        function closeBlogModal() {
            blogModal.style.display = 'none';
        }

        // Event listeners for blog functionality
        if (addBlogBtn) {
            addBlogBtn.addEventListener('click', () => openBlogModal());
        }

        if (closeBlogModalBtn) {
            closeBlogModalBtn.addEventListener('click', closeBlogModal);
        }

        if (cancelBlogBtn) {
            cancelBlogBtn.addEventListener('click', closeBlogModal);
        }

        // Close modal on outside click
        window.addEventListener('click', function (event) {
            if (event.target === blogModal) {
                closeBlogModal();
            }
        });

        // Handle blog form submission
        if (blogForm) {
            blogForm.addEventListener('submit', function (e) {
                e.preventDefault();

                // Validate required fields first
                const title = document.getElementById('blog-title').value;
                const category = document.getElementById('blog-category').value;
                const author = document.getElementById('blog-author').value;
                const readTime = document.getElementById('blog-readtime').value;

                if (!title || !category || !author || !readTime) {
                    showNotification('Please fill in all required fields', 'error');
                    return;
                }

                // Check if in edit mode
                const isEdit = blogForm.dataset.editId;

                // Show loading state
                const submitBtn = blogForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = isEdit ? 'Updating...' : 'Saving...';
                submitBtn.disabled = true;

                // Get content from CKEditor if it's available, otherwise from the textarea
                let content = '';
                if (editor && typeof editor.getData === 'function') {
                    content = editor.getData();
                } else {
                    // Fallback to textarea value if editor is not ready
                    content = document.getElementById('blog-content').value;
                }

                // Create FormData for the request
                const formData = new FormData();

                // Add text fields to form data
                formData.append('title', title);
                formData.append('subtitle', document.getElementById('blog-subtitle').value);
                formData.append('slug', document.getElementById('blog-slug').value);
                formData.append('category', category);
                formData.append('author', author);
                formData.append('readTime', readTime);
                formData.append('content', content);
                formData.append('shortDesc', document.getElementById('blog-shortdesc').value);
                formData.append('status', document.getElementById('blog-status').value);

                // Add files to form data
                const thumbnailFile = document.getElementById('blog-thumbnail').files[0];
                if (thumbnailFile) {
                    formData.append('thumbnail', thumbnailFile);
                }

                const bannerFile = document.getElementById('blog-banner').files[0];
                if (bannerFile) {
                    formData.append('banner', bannerFile);
                }

                const videoFile = document.getElementById('blog-video').files[0];
                if (videoFile) {
                    formData.append('video', videoFile);
                }

                // If editing, add the ID
                if (isEdit) {
                    formData.append('id', blogForm.dataset.editId);
                }

                // Determine API endpoint
                const apiUrl = isEdit ? './AdminApi/auth/updateblog.php' : './AdminApi/auth/addblog.php';

                // Send the request to the API
                fetch(apiUrl, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(data.message, 'success');

                            // Reset button
                            submitBtn.textContent = originalText;
                            submitBtn.disabled = false;

                            // Close modal and reset form
                            closeBlogModal();

                            // Refresh the blog list to show the new/updated post
                            loadBlogPosts();
                        } else {
                            throw new Error(data.message || 'Operation failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Error: ' + error.message, 'error');

                        // Reset button
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
            });
        }

        // Function to load blog posts from the API
        async function loadBlogPosts() {
            try {
                const response = await fetch('./AdminApi/auth/fetchblogs.php');
                const data = await response.json();

                if (data.success) {
                    const blogTableBody = document.querySelector('#blog-posts-table');
                    blogTableBody.innerHTML = ''; // Clear existing content

                    data.data.forEach(blog => {
                        const newRow = document.createElement('tr');

                        const statusBadge = blog.status === 'active'
                            ? '<span class="badge badge-success">Active</span>'
                            : '<span class="badge badge-danger">Inactive</span>';

                        newRow.innerHTML = `
                        <td>${blog.title}</td>
                        <td>${blog.category}</td>
                        <td>${blog.author}</td>
                        <td>${new Date(blog.created_at).toLocaleDateString()}</td>
                        <td>${statusBadge}</td>
                        <td>${blog.read_time}</td>
                        <td>
                            <button class="btn btn-sm btn-primary edit-blog-btn" data-id="${blog.id}">Edit</button>
                            <button class="btn btn-sm btn-danger delete-blog-btn" data-id="${blog.id}">Delete</button>
                        </td>
                    `;

                        // Store the blog ID in the row element for easy access
                        newRow.dataset.blogId = blog.id;

                        blogTableBody.appendChild(newRow);
                    });
                } else {
                    console.error('Failed to fetch blog posts:', data.message);
                    showNotification('Failed to load blog posts: ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error loading blog posts:', error);
                showNotification('Error loading blog posts: ' + error.message, 'error');
            }
        }

        // Load blog posts when blog section becomes active
        const blogSection = document.getElementById('blog');
        const blogObserver = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (blogSection.classList.contains('active')) {
                        loadBlogPosts();
                    }
                }
            });
        });

        blogObserver.observe(blogSection, { attributes: true });

        // Also load blog posts when blog menu item is clicked
        const blogMenuItem = document.querySelector('[data-section="blog"]');
        if (blogMenuItem) {
            blogMenuItem.addEventListener('click', function () {
                setTimeout(() => {
                    if (blogSection.classList.contains('active')) {
                        loadBlogPosts();
                    }
                }, 100);
            });
        }

        // Add event listeners to edit/delete buttons in blog table
        const blogTable = document.getElementById('blog-posts-table');
        if (blogTable) {
            blogTable.addEventListener('click', function (e) {
                const target = e.target;

                if (target.classList.contains('edit-blog-btn')) {
                    const blogId = target.getAttribute('data-id');

                    // Fetch full blog data from API for editing
                    fetch('./AdminApi/auth/fetchblogs.php')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const fullBlogData = data.data.find(blog => blog.id == blogId);

                                if (fullBlogData) {
                                    openBlogModal(true, fullBlogData);
                                } else {
                                    console.error('Blog post not found with ID:', blogId);
                                    showNotification('Blog post not found', 'error');
                                }
                            } else {
                                console.error('Failed to fetch blog details:', data.message);
                                showNotification('Failed to fetch blog details: ' + data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching blog details:', error);
                            showNotification('Error fetching blog details: ' + error.message, 'error');
                        });
                }

                if (target.classList.contains('delete-blog-btn')) {
                    const blogId = target.getAttribute('data-id');
                    const row = target.closest('tr');

                    if (confirm('Are you sure you want to delete this blog post?')) {
                        // Call the delete API
                        fetch('./AdminApi/auth/deleteblog.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: parseInt(blogId) })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    row.style.animation = 'fadeOut 0.5s forwards';
                                    setTimeout(() => {
                                        row.remove();
                                        showNotification(data.message, 'success');
                                    }, 500);
                                } else {
                                    showNotification('Failed to delete blog post: ' + data.message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error deleting blog:', error);
                                showNotification('Error deleting blog: ' + error.message, 'error');
                            });
                    }
                }
            });
        }

        // Initialize tooltips if available
        initTooltips();
    });

    // Function to show notifications
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification');
        existingNotifications.forEach(notification => notification.remove());

        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
        max-width: 300px;
    `;

        // Set background color based on type
        if (type === 'success') {
            notification.style.background = '#28a745';
        } else if (type === 'error') {
            notification.style.background = '#dc3545';
        } else {
            notification.style.background = '#4361ee';
        }

        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'fadeOut 0.5s forwards';
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    }

    // Initialize tooltips
    function initTooltips() {
        // Add tooltip functionality to elements with data-tooltip attribute
        const tooltipElements = document.querySelectorAll('[data-tooltip]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function () {
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip';
                tooltip.textContent = this.getAttribute('data-tooltip');
                tooltip.style.cssText = `
                position: absolute;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 5px 10px;
                border-radius: 4px;
                font-size: 0.8rem;
                z-index: 1000;
                pointer-events: none;
                white-space: nowrap;
            `;

                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';

                this._tooltip = tooltip;
            });

            element.addEventListener('mouseleave', function () {
                if (this._tooltip) {
                    this._tooltip.remove();
                    this._tooltip = null;
                }
            });
        });
    }

    // Utility functions
    function formatDate(date) {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function formatTime(date) {
        return new Date(date).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Animation for fadeOut (needed for delete functionality)
    const style = document.createElement('style');
    style.innerHTML = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
`;
    document.head.appendChild(style);

    // Function to handle real-time character count for textareas
    function initCharacterCounter() {
        const textareas = document.querySelectorAll('textarea[maxlength]');
        textareas.forEach(textarea => {
            const maxLength = textarea.getAttribute('maxlength');
            const counter = document.createElement('div');
            counter.className = 'character-counter';
            counter.style.cssText = `
            text-align: right;
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 5px;
        `;
            counter.textContent = `${textarea.value.length}/${maxLength}`;
            textarea.parentNode.appendChild(counter);

            textarea.addEventListener('input', function () {
                counter.textContent = `${this.value.length}/${maxLength}`;
                if (this.value.length > maxLength * 0.9) {
                    counter.style.color = '#dc3545';
                } else {
                    counter.style.color = '#6c757d';
                }
            });
        });
    }

    // Initialize character counters when DOM is loaded
    document.addEventListener('DOMContentLoaded', initCharacterCounter);

    // Function to handle file uploads with preview
    function initFileUploads() {
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const fileType = file.type.split('/')[0];

                    if (fileType === 'image') {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            // Look for a preview element associated with this input
                            const previewId = input.id.replace('-image', '-preview').replace('-file', '-preview');
                            const preview = document.getElementById(previewId);
                            if (preview) {
                                preview.src = event.target.result;
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        });
    }

    // Initialize file uploads when DOM is loaded
    document.addEventListener('DOMContentLoaded', initFileUploads);

    // Function to handle range sliders for skill ratings
    function initSkillSliders() {
        const sliders = document.querySelectorAll('input[type="range"]');
        sliders.forEach(slider => {
            // Update the associated value display when slider changes
            const valueDisplay = slider.parentNode.querySelector('span');
            if (valueDisplay) {
                valueDisplay.textContent = `${slider.value}/5.0`;

                slider.addEventListener('input', function () {
                    valueDisplay.textContent = `${this.value}/5.0`;
                });
            }
        });
    }

    // Initialize skill sliders when DOM is loaded
    document.addEventListener('DOMContentLoaded', initSkillSliders);

    // Lead Management Functionality
    async function loadLeads(status = 'all') {
        console.log('--- loadLeads called ---');
        console.log('Status filter:', status);
        const tableBody = document.getElementById('leads-table-body');
        if (!tableBody) {
            console.error('CRITICAL: Element #leads-table-body not found!');
            return;
        }
        console.log('Found table body, starting fetch...');
        try {
            const response = await fetch(`./AdminApi/auth/fetchContactUs.php?status=${status}`);
            console.log('API Response status:', response.status);
            const data = await response.json();
            console.log('API Data:', data);

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

                    let actionsHtml = `<button class="btn btn-sm btn-primary view-lead-btn" data-id="${lead.id}">View</button> `;

                    if (lead.status === 'new') {
                        actionsHtml += `<button class="btn btn-sm btn-warning status-lead-btn" data-id="${lead.id}" data-status="in-progress">Mark In Progress</button> `;
                    } else if (lead.status === 'in-progress') {
                        actionsHtml += `<button class="btn btn-sm btn-success status-lead-btn" data-id="${lead.id}" data-status="completed">Mark Completed</button> `;
                    }

                    actionsHtml += `<button class="btn btn-sm btn-danger delete-lead-btn" data-id="${lead.id}">Delete</button>`;

                    row.innerHTML = `
                        <td>${lead.name}</td>
                        <td>${lead.email}</td>
                        <td>${lead.subject}</td>
                        <td><span class="badge ${statusBadgeClass}">${lead.status.charAt(0).toUpperCase() + lead.status.slice(1)}</span></td>
                        <td>${date}</td>
                        <td>${actionsHtml}</td>
                    `;
                    tableBody.appendChild(row);

                    // Attach event listeners to buttons
                    row.querySelector('.view-lead-btn').addEventListener('click', () => showLeadDetails(lead));
                    const statusBtn = row.querySelector('.status-lead-btn');
                    if (statusBtn) {
                        statusBtn.addEventListener('click', () => updateLeadStatus(lead.id, statusBtn.dataset.status));
                    }
                    row.querySelector('.delete-lead-btn').addEventListener('click', () => deleteLead(lead.id, lead.name));
                });
            } else {
                showNotification(data.message || 'Failed to fetch leads', 'error');
            }
        } catch (error) {
            console.error('Error fetching leads:', error);
            showNotification('Network error while fetching leads', 'error');
        }
    }

    async function updateLeadStatus(id, newStatus) {
        try {
            const response = await fetch('./AdminApi/auth/updateContactUsStatus.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id, status: newStatus })
            });
            const data = await response.json();

            if (data.success) {
                showNotification(data.message || 'Status updated successfully', 'success');
                const activeFilter = document.getElementById('lead-status').value;
                loadLeads(activeFilter);
            } else {
                showNotification(data.message || 'Failed to update status', 'error');
            }
        } catch (error) {
            console.error('Error updating lead status:', error);
            showNotification('Network error while updating status', 'error');
        }
    }

    async function deleteLead(id, name) {
        if (!confirm(`Are you sure you want to delete lead from ${name}?`)) return;

        try {
            const response = await fetch('./AdminApi/auth/deleteContactUs.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            const data = await response.json();

            if (data.success) {
                showNotification(data.message || 'Lead deleted successfully', 'success');
                const activeFilter = document.getElementById('lead-status').value;
                loadLeads(activeFilter);
            } else {
                showNotification(data.message || 'Failed to delete lead', 'error');
            }
        } catch (error) {
            console.error('Error deleting lead:', error);
            showNotification('Network error while deleting lead', 'error');
        }
    }

    function showLeadDetails(lead) {
        const modal = document.getElementById('view-lead-modal');
        const content = document.getElementById('lead-details-content');
        if (!modal || !content) return;

        content.innerHTML = `
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
        modal.style.display = 'flex';
    }

    // Modal Close logic for Lead View
    const closeLeadModal = document.getElementById('close-lead-modal');
    const closeLeadDetailsBtn = document.getElementById('close-lead-details');
    const leadModal = document.getElementById('view-lead-modal');

    if (closeLeadModal) closeLeadModal.addEventListener('click', () => leadModal.style.display = 'none');
    if (closeLeadDetailsBtn) closeLeadDetailsBtn.addEventListener('click', () => leadModal.style.display = 'none');
    window.addEventListener('click', (e) => {
        if (e.target === leadModal) leadModal.style.display = 'none';
    });

    // Lead filter event listener
    const leadStatusFilter = document.getElementById('lead-status');
    if (leadStatusFilter) {
        leadStatusFilter.addEventListener('change', function () {
            loadLeads(this.value);
        });
    }

    // Load leads when Lead Management section is active
    const leadsSection = document.getElementById('leads');
    if (leadsSection) {
        const leadsObserver = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (leadsSection.classList.contains('active')) {
                        loadLeads(leadStatusFilter ? leadStatusFilter.value : 'all');
                    }
                }
            });
        });
        leadsObserver.observe(leadsSection, { attributes: true });

        // Immediate check in case it's already active
        if (leadsSection.classList.contains('active')) {
            console.log('Leads section is already active, loading leads...');
            loadLeads(leadStatusFilter ? leadStatusFilter.value : 'all');
        }
    }

    // Also load leads when menu item is clicked
    const leadsMenuItem = document.querySelector('[data-section="leads"]');
    if (leadsMenuItem) {
        leadsMenuItem.addEventListener('click', function () {
            setTimeout(() => {
                const leadsSection = document.getElementById('leads');
                const leadStatusFilter = document.getElementById('lead-status');
                if (leadsSection && leadsSection.classList.contains('active')) {
                    console.log('Menu click: loading leads...');
                    loadLeads(leadStatusFilter ? leadStatusFilter.value : 'all');
                }
            }, 100);
        });
    }

    // Window load fallback to ensure it runs even if MutationObserver missed it
    window.addEventListener('load', () => {
        console.log('Window LOAD event fired');
        const leadsSection = document.getElementById('leads');
        if (leadsSection && leadsSection.classList.contains('active')) {
            console.log('Leads section active on page load, fetching...');
            loadLeads(document.getElementById('lead-status')?.value || 'all');
        }
    });

    console.log('Lead Management scripts initialized.');

    // Add logout functionality
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async function () {
            if (confirm('Are you sure you want to logout?')) {
                try {
                    const response = await fetch('./logout.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                    });

                    const data = await response.json();

                    if (data.success) {
                        showNotification('Logged out successfully', 'success');
                        // Redirect to login page after a short delay
                        setTimeout(() => {
                            window.location.href = 'login.php';
                        }, 1500);
                    } else {
                        showNotification(data.message || 'Logout failed', 'error');
                    }
                } catch (error) {
                    console.error('Logout error:', error);
                    showNotification('Network error during logout', 'error');
                }
            }
        });
    }

    // Function to add creative animations to UI elements
    function addCreativeAnimations() {
        // Add animations to dashboard cards
        const dashboardCards = document.querySelectorAll('.card');
        dashboardCards.forEach((card, index) => {
            // Add staggered animation delay
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('fade-in-up');
        });

        // Add hover effects to menu items
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            // Add subtle animation to menu items
            item.classList.add('creative-pulse');
        });

        // Add animations to gallery items
        const galleryItems = document.querySelectorAll('.gallery-item');
        galleryItems.forEach(item => {
            item.classList.add('creative-pulse');
        });

        // Add floating animation to the user profile in header
        const userAvatar = document.querySelector('.user-info img');
        if (userAvatar) {
            userAvatar.classList.add('creative-float');
        }

        // Add glow effect to primary buttons
        const primaryButtons = document.querySelectorAll('.btn-primary');
        primaryButtons.forEach(button => {
            button.classList.add('creative-glow');
        });
    }



    // Close the else block
}
