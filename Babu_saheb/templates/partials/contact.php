<div class="rn-contact-area rn-section-gap section-separator" id="contacts">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title text-center">
                    <span class="subtitle">Contact</span>
                    <h2 class="title">Contact With Me</h2>
                </div>
            </div>
        </div>

        <div class="row mt--50 mt_md--40 mt_sm--40 mt-contact-sm">
            <div class="col-lg-5">
                <div class="contact-about-area">
                    <div class="thumbnail">
                        <img src="../../static/images/contact/contact2.png" alt="contact-img">
                    </div>
                    <div class="title-area">
                        <h4 class="title">Nevine Acotanza</h4>
                        <span>Chief Operating Officer</span>
                    </div>
                    <div class="description">
                        <p>I am available for freelance work. Connect with me via and call in to my account.
                        </p>
                        <span class="phone">Phone: <a href="tel:01941043264">+01234567890</a></span>
                        <span class="mail">Email: <a href="mailto:admin@example.com">admin@example.com</a></span>
                    </div>
                    <div class="social-area">
                        <div class="name">FIND WITH ME</div>
                        <div class="social-icone">
                            <a href="#"><i data-feather="facebook"></i></a>
                            <a href="#"><i data-feather="linkedin"></i></a>
                            <a href="#"><i data-feather="instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div data-aos-delay="600" class="col-lg-7 contact-input">
                <div class="contact-form-wrapper">
                    <div class="introduce">

                        <form class="rnt-contact-form rwt-dynamic-form row" id="contact-form" method="POST" action="#">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="contact-name">Your Name</label>
                                    <input class="form-control form-control-lg" name="contact-name" id="contact-name"
                                        type="text">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="contact-phone">Phone Number</label>
                                    <input class="form-control" name="contact-phone" id="contact-phone" type="text">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="contact-email">Email</label>
                                    <input class="form-control form-control-sm" id="contact-email" name="contact-email"
                                        type="email">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="subject">subject</label>
                                    <input class="form-control form-control-sm" id="subject" name="subject" type="text">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="contact-message">Your Message</label>
                                    <textarea name="contact-message" id="contact-message" cols="30"
                                        rows="10"></textarea>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button name="submit" type="submit" id="submit" class="rn-btn">
                                    <span id="submit-text">SEND MESSAGE</span>
                                    <i data-feather="arrow-right"></i>
                                </button>
                            </div>

                            <!-- Response Message -->
                            <div class="col-lg-12">
                                <div id="form-messages" style="margin-top: 20px; display: none;"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Form API Integration Script -->
<style>
    #form-messages {
        padding: 15px 20px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.6;
    }

    #form-messages.success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    #form-messages.error {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    #form-messages ul {
        margin: 10px 0 0 20px;
        padding: 0;
    }

    #form-messages li {
        margin-bottom: 5px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const contactForm = document.getElementById('contact-form');
        const submitBtn = document.getElementById('submit');
        const submitText = document.getElementById('submit-text');
        const formMessages = document.getElementById('form-messages');

        if (contactForm) {
            contactForm.addEventListener('submit', async function (e) {
                e.preventDefault();

                // Disable submit button
                submitBtn.disabled = true;
                submitText.textContent = 'SENDING...';

                // Hide previous messages
                formMessages.style.display = 'none';
                formMessages.className = '';

                // Collect form data
                const formData = {
                    name: document.getElementById('contact-name').value.trim(),
                    email: document.getElementById('contact-email').value.trim(),
                    phone_number: document.getElementById('contact-phone').value.trim(),
                    subject: document.getElementById('subject').value.trim(),
                    message: document.getElementById('contact-message').value.trim()
                };

                try {
                    // Send data to API
                    const response = await fetch('../home/AllfrontentApi/contactUs.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    const result = await response.json();

                    // Display response
                    formMessages.style.display = 'block';

                    if (result.success) {
                        // Success message
                        formMessages.className = 'success';
                        formMessages.innerHTML = `
                                    <strong>✅ Success!</strong><br>
                                    ${result.message}
                                `;

                        // Reset form
                        contactForm.reset();

                        // Auto-hide success message after 5 seconds
                        setTimeout(() => {
                            formMessages.style.display = 'none';
                        }, 5000);
                    } else {
                        // Error message
                        formMessages.className = 'error';
                        let errorHTML = `<strong>❌ Error</strong><br>${result.message}`;

                        if (result.errors && result.errors.length > 0) {
                            errorHTML += '<ul>';
                            result.errors.forEach(error => {
                                errorHTML += `<li>${error}</li>`;
                            });
                            errorHTML += '</ul>';
                        }

                        formMessages.innerHTML = errorHTML;
                    }

                } catch (error) {
                    // Network error
                    formMessages.style.display = 'block';
                    formMessages.className = 'error';
                    formMessages.innerHTML = `
                                <strong>❌ Network Error</strong><br>
                                Failed to submit the form. Please check your internet connection and try again.
                            `;
                    console.error('Contact form error:', error);
                } finally {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitText.textContent = 'SEND MESSAGE';
                }
            });
        }
    });
</script>