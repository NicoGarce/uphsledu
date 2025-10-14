<?php
/**
 * UPHSL Campus Map Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Interactive campus map and building locations for UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Campus Map";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
/* Map Page Colors */
:root {
    --primary-blue: #1e40af;
    --secondary-blue: #3b82f6;
    --accent-green: #059669;
    --text-dark: #1f2937;
    --text-gray: #6b7280;
    --border-light: #e5e7eb;
    --bg-light: #f8fafc;
    --bg-accent: #f1f5f9;
    --map-brown: #92400e;
    --map-orange: #ea580c;
}

.map-hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 5rem 0 3rem;
    position: relative;
    min-height: 50vh;
    display: flex;
    align-items: center;
}

.map-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
    z-index: 0;
}

.map-hero::after {
    content: '🗺️ 📍 🏢 🏥 🎓 🗺️ 📍 🏢 🏥 🎓 🗺️ 📍 🏢 🏥 🎓';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    font-size: 1.2rem;
    opacity: 0.08;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    overflow: hidden;
    animation: float 30s linear infinite;
    z-index: 0;
}

@keyframes float {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

.hero-content {
    position: relative;
    z-index: 1;
    text-align: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    width: 100%;
}

.hero-content h1 {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
    color: #ffffff;
}

.hero-content .subtitle {
    font-size: 1.4rem;
    font-weight: 400;
    margin-bottom: 2rem;
    opacity: 0.9;
    color: #ffffff;
}

.map-content {
    padding: 3rem 0;
    background: var(--bg-light);
    position: relative;
    width: 100%;
}

.map-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.map-intro {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-left: 5px solid var(--primary-blue);
    text-align: center;
}

.map-intro h2 {
    color: var(--primary-blue);
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.map-intro h2::before {
    content: '🗺️';
    font-size: 1.8rem;
}

.map-intro p {
    font-size: 1.1rem;
    color: var(--text-gray);
    line-height: 1.6;
    margin: 0;
}

.buildings-section {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.buildings-section h3 {
    color: var(--text-dark);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 2px solid var(--border-light);
    padding-bottom: 0.5rem;
}

.buildings-section h3::before {
    content: '🏢';
    font-size: 1.3rem;
}

.buildings-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.building-item {
    background: var(--bg-accent);
    padding: 2rem;
    border-radius: 12px;
    border-left: 4px solid var(--primary-blue);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.building-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    border-radius: 12px 12px 0 0;
}

.building-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    border-left-color: var(--secondary-blue);
}

.building-item h4 {
    color: var(--primary-blue);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.building-item h4::before {
    content: '🏛️';
    font-size: 1.2rem;
    opacity: 0.8;
}

.building-item p {
    color: var(--text-gray);
    font-size: 0.85rem;
    line-height: 1.5;
    margin: 0;
}

.map-image-section {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}

.map-image-section h3 {
    color: var(--text-dark);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.map-image-section h3::before {
    content: '📍';
    font-size: 1.3rem;
}

.map-image {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transition: transform 0.3s ease;
}

.map-image:hover {
    transform: scale(1.02);
}


.contact-section {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 2.5rem;
    margin: 2rem 0;
    border-radius: 12px;
    text-align: center;
}

.contact-section h3 {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.contact-section h3::before {
    content: '📞';
    font-size: 1.3rem;
}

.contact-section p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.buildings-gallery {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.buildings-gallery h3 {
    color: var(--text-dark);
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 2px solid var(--border-light);
    padding-bottom: 0.5rem;
}

.buildings-gallery h3::before {
    content: '📸';
    font-size: 1.3rem;
}

.gallery-section {
    margin-bottom: 3rem;
}

.gallery-section h4 {
    color: var(--primary-blue);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.gallery-section h4::before {
    content: '🏢';
    font-size: 1.2rem;
    opacity: 0.8;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.gallery-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
    cursor: pointer;
}

.gallery-image:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
}

/* Floor Plan Grid Styles */
.floor-plan-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 1.5rem;
    margin-top: 1rem;
}

/* Special grid for 5 items (3 top, 2 bottom) */
.floor-plan-grid.five-items .floor-plan-item:nth-child(4) {
    grid-column: 1;
    grid-row: 2;
}

.floor-plan-grid.five-items .floor-plan-item:nth-child(5) {
    grid-column: 2;
    grid-row: 2;
}

.floor-plan-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
}

.floor-plan-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
}

.floor-plan-image {
    width: 100%;
    height: 300px;
    object-fit: contain;
    background: #f8f9fa;
    padding: 1rem;
}

.floor-plan-label {
    background: var(--primary-blue);
    color: white;
    text-align: center;
    padding: 0.8rem;
    font-weight: 600;
    font-size: 0.9rem;
}

/* Hospital Plan Styles */
.hospital-plan-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    overflow: hidden;
    margin-top: 1rem;
}

.hospital-plan-image {
    width: 100%;
    height: auto;
    max-height: 600px;
    object-fit: contain;
    background: #f8f9fa;
    padding: 1rem;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.hospital-plan-image:hover {
    transform: scale(1.02);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.9);
    animation: fadeIn 0.3s ease;
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
    from { transform: scale(0.8); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.modal-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 1001;
    transition: color 0.3s ease;
}

.modal-close:hover {
    color: #ccc;
}

.modal-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
    z-index: 1001;
    padding: 10px 15px;
    background-color: rgba(0,0,0,0.5);
    border-radius: 50%;
    transition: background-color 0.3s ease;
}

.modal-nav:hover {
    background-color: rgba(0,0,0,0.8);
}

.modal-prev {
    left: 20px;
}

.modal-next {
    right: 20px;
}

.modal-counter {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    background-color: rgba(0,0,0,0.7);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    z-index: 1001;
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .hero-content .subtitle {
        font-size: 1.2rem;
    }
    
    .buildings-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    @media (max-width: 480px) {
        .buildings-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
    
    .map-container {
        padding: 0 1rem;
    }
    
    .map-intro,
    .buildings-section,
    .map-image-section,
    .buildings-gallery {
        padding: 1.5rem;
    }
    
    .building-item {
        padding: 1.5rem;
    }
    
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.8rem;
    }
    
    .gallery-image {
        height: 150px;
    }
    
    .floor-plan-grid {
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 1rem;
    }
    
    .floor-plan-image {
        height: 200px;
    }
    
    .hospital-plan-image {
        max-height: 400px;
    }
    
    @media (max-width: 480px) {
        .floor-plan-grid {
            grid-template-columns: 1fr;
            grid-template-rows: repeat(6, 1fr);
            gap: 0.8rem;
        }
        
        .floor-plan-image {
            height: 180px;
        }
        
        .hospital-plan-image {
            max-height: 300px;
        }
    }
}
</style>

<!-- Campus Map Hero Section -->
<section class="map-hero">
    <div class="container">
        <div class="hero-content">
            <h1>Campus Map</h1>
            <p class="subtitle">Navigate UPHSL Campus with Ease</p>
        </div>
    </div>
</section>

<!-- Map Content -->
<section class="map-content">
    <div class="map-container">
        <!-- Introduction -->
        <div class="map-intro">
            <h2>UPHSL Campus Map</h2>
            <p>Explore our campus facilities and find your way around the University of Perpetual Help System Laguna. Our interactive map helps you locate buildings, departments, and key areas of interest.</p>
        </div>

        <!-- Campus Buildings -->
        <div class="buildings-section">
            <h3>Campus Buildings & Facilities</h3>
            <div class="buildings-grid">
                <div class="building-item">
                    <h4>Main Building</h4>
                    <p>The central hub of UPHSL, housing administrative offices, classrooms, and student services. This is where you'll find the main entrance, registrar's office, and key academic departments.</p>
                </div>
                
                <div class="building-item">
                    <h4>Medical University</h4>
                    <p>Dedicated to medical education and healthcare training, this building houses state-of-the-art medical laboratories, simulation centers, and specialized classrooms for medical students.</p>
                </div>
                
                <div class="building-item">
                    <h4>Hospital Building</h4>
                    <p>Our teaching hospital facility that provides hands-on clinical training for medical students while serving the community with quality healthcare services.</p>
                </div>
                
                <div class="building-item">
                    <h4>Basic Education Building</h4>
                    <p>Home to our Grade School, Junior High School, and Senior High School programs. Features modern classrooms, science laboratories, and recreational facilities for younger students.</p>
                </div>
            </div>
        </div>

        <!-- Overall Campus Map -->
        <div class="map-image-section">
            <h3>Overall Campus Map</h3>
            <img src="<?php echo $base_path; ?>assets/images/map/overallmap.jpg" alt="UPHSL Overall Campus Map" class="map-image">
        </div>

        <!-- Building Galleries -->
        <div class="buildings-gallery">
            <h3>Building Galleries</h3>
            
            <!-- Main Building Gallery -->
            <div class="gallery-section">
                <h4>Main Building</h4>
                <div class="floor-plan-grid five-items">
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mainbuild1.jpg" alt="Main Building View 1" class="floor-plan-image">
                        <div class="floor-plan-label">Main Building View 1</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mainbuild2.jpg" alt="Main Building View 2" class="floor-plan-image">
                        <div class="floor-plan-label">Main Building View 2</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mainbuild3.jpg" alt="Main Building View 3" class="floor-plan-image">
                        <div class="floor-plan-label">Main Building View 3</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mainbuild4.jpg" alt="Main Building View 4" class="floor-plan-image">
                        <div class="floor-plan-label">Main Building View 4</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mainbuildpwd.jpg" alt="Main Building PWD Access" class="floor-plan-image">
                        <div class="floor-plan-label">PWD Access</div>
                    </div>
                </div>
            </div>

            <!-- Medical University Gallery -->
            <div class="gallery-section">
                <h4>Medical University Floor Plans</h4>
                <div class="floor-plan-grid">
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mu1.jpg" alt="Medical University Ground Floor" class="floor-plan-image">
                        <div class="floor-plan-label">Ground Floor</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mu2.jpg" alt="Medical University 2nd Floor" class="floor-plan-image">
                        <div class="floor-plan-label">2nd Floor</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mu3.jpg" alt="Medical University 3rd Floor" class="floor-plan-image">
                        <div class="floor-plan-label">3rd Floor</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mu4.jpg" alt="Medical University 4th Floor" class="floor-plan-image">
                        <div class="floor-plan-label">4th Floor</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mu5.jpg" alt="Medical University 5th Floor" class="floor-plan-image">
                        <div class="floor-plan-label">5th Floor</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/mu6.jpg" alt="Medical University 6th Floor" class="floor-plan-image">
                        <div class="floor-plan-label">6th Floor</div>
                    </div>
                </div>
            </div>

            <!-- Hospital Building Gallery -->
            <div class="gallery-section">
                <h4>Hospital Building Floor Plan</h4>
                <div class="hospital-plan-container">
                    <img src="<?php echo $base_path; ?>assets/images/map/hos1.jpg" alt="Hospital Building Floor Plan" class="hospital-plan-image">
                </div>
            </div>

            <!-- Basic Education Gallery -->
            <div class="gallery-section">
                <h4>Basic Education Building</h4>
                <div class="floor-plan-grid">
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/besd1.jpg" alt="Basic Education View 1" class="floor-plan-image">
                        <div class="floor-plan-label">Basic Education View 1</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/besd2.jpg" alt="Basic Education View 2" class="floor-plan-image">
                        <div class="floor-plan-label">Basic Education View 2</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/besd3.jpg" alt="Basic Education View 3" class="floor-plan-image">
                        <div class="floor-plan-label">Basic Education View 3</div>
                    </div>
                    <div class="floor-plan-item">
                        <img src="<?php echo $base_path; ?>assets/images/map/besd4.jpg" alt="Basic Education View 4" class="floor-plan-image">
                        <div class="floor-plan-label">Basic Education View 4</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="contact-section">
            <h3>Need Directions?</h3>
            <p>If you need assistance finding a specific building or department, please contact our Information Desk or visit the Registrar's Office in the Main Building.</p>
        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="modal-close">&times;</span>
    <div class="modal-nav modal-prev" id="prevBtn">&#10094;</div>
    <div class="modal-nav modal-next" id="nextBtn">&#10095;</div>
    <img class="modal-content" id="modalImage">
    <div class="modal-counter" id="modalCounter"></div>
</div>

<script>
// Add interactive features for the map
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects for building items
    const buildingItems = document.querySelectorAll('.building-item');
    
    buildingItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add click effects for map image
    const mapImage = document.querySelector('.map-image');
    if (mapImage) {
        mapImage.addEventListener('click', function() {
            this.style.transform = 'scale(1.05)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    }
    
    // Modal functionality
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalCounter = document.getElementById('modalCounter');
    const closeBtn = document.querySelector('.modal-close');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    // Get all gallery images and floor plan images
    const galleryImages = document.querySelectorAll('.gallery-image');
    const floorPlanImages = document.querySelectorAll('.floor-plan-image');
    const hospitalPlanImage = document.querySelector('.hospital-plan-image');
    
    // Combine all images for modal navigation
    const allImages = [...galleryImages, ...floorPlanImages, hospitalPlanImage].filter(img => img);
    let currentImageIndex = 0;
    
    // Open modal when any image is clicked
    allImages.forEach((image, index) => {
        image.addEventListener('click', function() {
            currentImageIndex = index;
            openModal();
        });
    });
    
    // Open modal function
    function openModal() {
        modal.classList.add('show');
        modal.style.display = 'flex';
        updateModalImage();
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }
    
    // Close modal function
    function closeModal() {
        modal.classList.remove('show');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto'; // Restore scrolling
    }
    
    // Update modal image
    function updateModalImage() {
        const currentImage = allImages[currentImageIndex];
        modalImg.src = currentImage.src;
        modalImg.alt = currentImage.alt;
        modalCounter.textContent = `${currentImageIndex + 1} / ${allImages.length}`;
    }
    
    // Navigate to previous image
    function showPrevImage() {
        currentImageIndex = (currentImageIndex - 1 + allImages.length) % allImages.length;
        updateModalImage();
    }
    
    // Navigate to next image
    function showNextImage() {
        currentImageIndex = (currentImageIndex + 1) % allImages.length;
        updateModalImage();
    }
    
    // Event listeners
    closeBtn.addEventListener('click', closeModal);
    prevBtn.addEventListener('click', showPrevImage);
    nextBtn.addEventListener('click', showNextImage);
    
    // Close modal when clicking outside the image
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === 'flex') {
            if (e.key === 'Escape') {
                closeModal();
            } else if (e.key === 'ArrowLeft') {
                showPrevImage();
            } else if (e.key === 'ArrowRight') {
                showNextImage();
            }
        }
    });
});
</script>

<?php
// Include footer
include '../app/includes/footer.php';
?>
