<?php
// General Coming Soon Placeholder Component
// This can be used for any section that doesn't have content yet
?>

<section class="general-coming-soon-section">
    <div class="container">
        <div class="general-coming-soon-content">
            <div class="coming-soon-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h2>Coming Soon</h2>
            <p>We're working hard to bring you exciting new content and features. Stay tuned for updates!</p>
            
            <div class="coming-soon-features">
                <div class="feature-item">
                    <i class="fas fa-cog"></i>
                    <span>New Features</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-star"></i>
                    <span>Enhanced Experience</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-bolt"></i>
                    <span>Improved Performance</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-heart"></i>
                    <span>Better Service</span>
                </div>
            </div>
            
            <div class="coming-soon-actions">
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>index.php" class="btn btn-primary">
                    <i class="fas fa-home"></i> Go Home
                </a>
                <a href="<?php echo isset($base_path) ? $base_path : ''; ?>programs.php" class="btn btn-outline">
                    <i class="fas fa-graduation-cap"></i> View Programs
                </a>
                <a href="mailto:info@uphsl.edu.ph" class="btn btn-outline">
                    <i class="fas fa-envelope"></i> Contact Us
                </a>
            </div>
            
            <div class="coming-soon-footer">
                <p>Thank you for your patience and continued support!</p>
                <div class="social-links">
                    <a href="#" class="social-link" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.general-coming-soon-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 80vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.general-coming-soon-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.general-coming-soon-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

.coming-soon-icon {
    font-size: 5rem;
    color: #fff;
    margin-bottom: 2rem;
    animation: float 3s ease-in-out infinite;
    text-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.general-coming-soon-content h2 {
    font-size: 3.5rem;
    color: #fff;
    margin-bottom: 1.5rem;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    letter-spacing: -1px;
}

.general-coming-soon-content p {
    font-size: 1.3rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 3rem;
    line-height: 1.6;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.coming-soon-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 4rem;
}

.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 2rem 1.5rem;
    background: rgba(255,255,255,0.15);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.feature-item:hover {
    transform: translateY(-10px);
    background: rgba(255,255,255,0.25);
    box-shadow: 0 12px 40px rgba(0,0,0,0.2);
}

.feature-item i {
    font-size: 2.5rem;
    color: #fff;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.feature-item span {
    font-weight: 600;
    color: #fff;
    font-size: 1.1rem;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

.coming-soon-actions {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-bottom: 3rem;
}

.btn {
    padding: 15px 30px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    border: 2px solid transparent;
}

.btn-primary {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    color: white;
    border-color: #ff6b6b;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #ee5a24, #ff6b6b);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255,107,107,0.4);
}

.btn-outline {
    background: rgba(255,255,255,0.1);
    color: white;
    border-color: rgba(255,255,255,0.3);
    backdrop-filter: blur(10px);
}

.btn-outline:hover {
    background: rgba(255,255,255,0.2);
    border-color: rgba(255,255,255,0.5);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255,255,255,0.2);
}

.coming-soon-footer {
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 2rem;
}

.coming-soon-footer p {
    color: rgba(255,255,255,0.8);
    margin-bottom: 1.5rem;
    font-size: 1.1rem;
}

.social-links {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.1);
    color: white;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
}

.social-link:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255,255,255,0.2);
}

.social-link i {
    font-size: 1.2rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .general-coming-soon-section {
        padding: 60px 0;
    }
    
    .general-coming-soon-content h2 {
        font-size: 2.5rem;
    }
    
    .general-coming-soon-content p {
        font-size: 1.1rem;
    }
    
    .coming-soon-features {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .feature-item {
        padding: 1.5rem 1rem;
    }
    
    .coming-soon-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 250px;
        justify-content: center;
    }
    
    .social-links {
        gap: 0.5rem;
    }
    
    .social-link {
        width: 45px;
        height: 45px;
    }
}

@media (max-width: 480px) {
    .general-coming-soon-content h2 {
        font-size: 2rem;
    }
    
    .coming-soon-features {
        grid-template-columns: 1fr 1fr;
    }
    
    .feature-item {
        padding: 1rem 0.5rem;
    }
    
    .feature-item i {
        font-size: 2rem;
    }
    
    .feature-item span {
        font-size: 0.9rem;
    }
}
</style>
