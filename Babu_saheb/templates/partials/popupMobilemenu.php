<div class="popup-mobile-menu">
        <div class="inner">
            <div class="menu-top">
                <div class="menu-header">
                    <a class="logo" href="{{ url_for('index') }}">
                        <img src="/static/images/logo/logos-circle.png" alt="Personal Portfolio">
                    </a>
                    <div class="close-button">
                        <button class="close-menu-activation close"><i data-feather="x"></i></button>
                    </div>
                </div>
                <p class="discription">Lorem ipsum dolor sit amet consect adipisicing elit repellendus.
                </p>
            </div>
            <div class="content">
                <ul class="primary-menu">
                    <li><a class="nav-link" href="#">Home</a></li>
                    <li><a class="nav-link" href="#">Experience</a></li>
                    <li><a class="nav-link" href="#">Education</a></li>
                    <li><a class="nav-link" href="#">Resume</a></li>
                    <li class="has-droupdown"><a class="nav-link" href="#">Pages</a>
                        <ul class="submenu">
                            <li><a href="{{ url_for('blog') }}">Blog</a></li>
                            <li><a href="{{ url_for('blogWhiteVersion') }}">Blog White</a></li>
                            <li><a href="{{ url_for('button') }}">Button</a></li>
                            <li><a href="{{ url_for('buttonWhite') }}">Button White</a></li>
                            <li><a href="{{ url_for('brand') }}">Brand</a></li>
                            <li><a href="{{ url_for('brandWhiteVersion') }}">Brand White</a></li>
                            <li><a href="{{ url_for('gallery') }}">Gallery</a></li>
                            <li><a href="{{ url_for('galleryWhiteVersion') }}">Gallery White</a></li>
                            <li><a href="{{ url_for('portfolio') }}">Portfolio</a></li>
                            <li><a href="{{ url_for('portfolioWhiteVersion') }}">Portfolio White</a></li>
                            <li><a href="{{ url_for('pricing') }}">Pricing</a></li>
                            <li><a href="{{ url_for('pricingWhiteVersion') }}">Pricing White</a></li>
                            <li><a href="{{ url_for('progressbar') }}">Progressbar</a></li>
                            <li><a href="{{ url_for('progressbarWhiteVersion') }}">Progressbar White</a></li>
                            <li><a href="{{ url_for('socialIcones') }}">Social</a></li>
                            <li><a href="{{ url_for('socialIconesWhiteVersion') }}">Social White</a></li>
                            <li><a href="{{ url_for('tab') }}">Tab</a></li>
                            <li><a href="{{ url_for('tabWhiteVersion') }}">Tab White</a></li>
                            <li><a href="{{ url_for('testimonial') }}">Testimonial</a></li>
                            <li><a href="{{ url_for('testimonialWhiteVersion') }}">Testimonial White</a></li>
                            <li><a href="{{ url_for('videoPopup') }}">Video Popup</a></li>
                            <li><a href="{{ url_for('videoPopupWhiteVersion') }}">Video Popup White</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link" href="#">Blog</a></li>
                    <li><a class="nav-link" href="#">Footer</a></li>
                </ul>
                <!-- social sharea area -->
                <div class="social-share-style-1 mt--40">
                    <span class="title">find with me</span>
                    <ul class="social-share d-flex liststyle">
                        <li class="facebook"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg></a>
                        </li>
                        <li class="instagram"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                                </svg></a>
                        </li>
                        <li class="linkedin"><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin">
                                    <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                    </path>
                                    <rect x="2" y="9" width="4" height="12"></rect>
                                    <circle cx="4" cy="4" r="2"></circle>
                                </svg></a>
                        </li>
                    </ul>
                </div>
                <!-- end -->
            </div>
        </div>
    </div>