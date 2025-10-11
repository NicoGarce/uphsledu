<?php
// Coming Soon Placeholder Component
// This can be included in pages that don't have content yet
?>

<section class="coming-soon-section">
    <div class="container">
        <div class="coming-soon-content">
            <div class="coming-soon-icon">
                <i class="fas fa-tools"></i>
            </div>
            <h2>Coming Soon</h2>
            <p>We're working hard to bring you comprehensive information about this program. Please check back soon for updates.</p>
            <div class="coming-soon-features">
                <div class="feature-item">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Program Details</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-briefcase"></i>
                    <span>Career Opportunities</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-users"></i>
                    <span>Faculty Information</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-building"></i>
                    <span>Facilities & Resources</span>
                </div>
            </div>
            <div class="coming-soon-actions">
                <a href="index.php" class="btn btn-primary">Return to Home</a>
                <a href="programs.php" class="btn btn-outline">View All Programs</a>
            </div>
        </div>
    </div>
</section>

<style>
.coming-soon-section {
    padding: 80px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 60vh;
    display: flex;
    align-items: center;
}

.coming-soon-content {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.coming-soon-icon {
    font-size: 4rem;
    color: #2c5aa0;
    margin-bottom: 2rem;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.coming-soon-content h2 {
    font-size: 2.5rem;
    color: #2c5aa0;
    margin-bottom: 1rem;
    font-weight: 700;
}

.coming-soon-content p {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 3rem;
    line-height: 1.6;
}

.coming-soon-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.feature-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.feature-item:hover {
    transform: translateY(-5px);
}

.feature-item i {
    font-size: 2rem;
    color: #2c5aa0;
    margin-bottom: 1rem;
}

.feature-item span {
    font-weight: 600;
    color: #333;
}

.coming-soon-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 30px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-primary {
    background: #2c5aa0;
    color: white;
    border: 2px solid #2c5aa0;
}

.btn-primary:hover {
    background: #1e3d6f;
    border-color: #1e3d6f;
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: #2c5aa0;
    border: 2px solid #2c5aa0;
}

.btn-outline:hover {
    background: #2c5aa0;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .coming-soon-content h2 {
        font-size: 2rem;
    }
    
    .coming-soon-content p {
        font-size: 1.1rem;
    }
    
    .coming-soon-features {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .coming-soon-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 200px;
    }
}
</style>
