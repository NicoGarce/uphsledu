<?php
/**
 * UPHSL College Academic Calendar Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description College Academic Calendar for AY 2024-2025
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "College Academic Calendar";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
/* New page hero */
.page-hero { position: relative; padding: 80px 0; color: #fff; text-align: center; isolation: isolate; overflow: hidden; background: url('../assets/images/banners/UPHSL%20Facade.png') center/cover no-repeat; }
.page-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(28,77,161,.85), rgba(82,123,189,.85)); z-index: 1; }
.page-hero .content { position: relative; z-index: 2; display: inline-block; padding: 24px 28px; border-radius: 16px; background: rgba(0,0,0,.55); -webkit-backdrop-filter: blur(10px); backdrop-filter: blur(10px); box-shadow: 0 16px 40px rgba(0,0,0,.35); }
.page-hero .title { font-size: 3rem; font-weight: 800; line-height: 1.1; margin-bottom: 18px; text-shadow: 2px 2px 4px rgba(0,0,0,.3); }
.page-hero .subtitle { font-size: 1.05rem; margin: 0; }
@media (max-width: 1024px){ .page-hero{ padding:60px 0; } .page-hero .content{ padding:16px 18px; border-radius:12px; } .page-hero .title{ font-size:2.2rem; } .page-hero .subtitle{ font-size:1rem; } }
/* Calendar Page Colors */
:root {
    --primary-blue: #1e40af;
    --secondary-blue: #3b82f6;
    --accent-green: #059669;
    --text-dark: #1f2937;
    --text-gray: #6b7280;
    --border-light: #e5e7eb;
    --bg-light: #f8fafc;
    --bg-accent: #f1f5f9;
    --calendar-red: #dc2626;
    --calendar-orange: #ea580c;
    --calendar-purple: #7c3aed;
}

/* Use global Programs banner styles (no page-specific overrides) */

.calendar-content {
    padding: 3rem 0;
    background: var(--bg-light);
    position: relative;
    width: 100%;
}

.calendar-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.calendar-intro {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-left: 5px solid var(--primary-blue);
    text-align: center;
}

.calendar-intro h2 {
    color: var(--primary-blue);
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.calendar-intro h2::before {
    content: '📅';
    font-size: 1.8rem;
}

.calendar-intro p {
    font-size: 1.1rem;
    color: var(--text-gray);
    line-height: 1.6;
    margin: 0;
}

.academic-year-info {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.academic-year-info h3 {
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

.academic-year-info h3::before {
    content: '🎓';
    font-size: 1.3rem;
}

.year-highlight {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    padding: 2rem;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 2rem;
}

.year-highlight h4 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.year-highlight p {
    font-size: 1.2rem;
    opacity: 0.9;
    margin: 0;
}

.calendar-sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.calendar-section {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid var(--primary-blue);
}

.calendar-section h4 {
    color: var(--primary-blue);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.calendar-section h4::before {
    content: '📆';
    font-size: 1.2rem;
    opacity: 0.8;
}

.calendar-dates {
    list-style: none;
    padding: 0;
    margin: 0;
}

.calendar-dates li {
    padding: 0.8rem 0;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.calendar-dates li:last-child {
    border-bottom: none;
}

.date-label {
    font-weight: 600;
    color: var(--text-dark);
    flex: 1;
}

.date-value {
    color: var(--text-gray);
    font-size: 0.9rem;
    background: var(--bg-accent);
    padding: 0.3rem 0.8rem;
    border-radius: 6px;
    font-weight: 500;
}

.important-dates {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 1px solid #fecaca;
    border-left: 5px solid var(--calendar-red);
    padding: 2rem;
    margin: 2rem 0;
    border-radius: 12px;
}

.important-dates h4 {
    color: var(--calendar-red);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.important-dates h4::before {
    content: '⚠️';
    font-size: 1.2rem;
}

.download-section {
    background: linear-gradient(135deg, var(--accent-green) 0%, #10b981 100%);
    color: white;
    padding: 2.5rem;
    margin: 2rem 0;
    border-radius: 12px;
    text-align: center;
}

.download-section h3 {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.download-section h3::before {
    content: '📥';
    font-size: 1.3rem;
}

.download-btn {
    display: inline-block;
    background: white;
    color: var(--accent-green);
    padding: 1rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    margin-top: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.download-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    color: var(--accent-green);
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

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .hero-content .subtitle {
        font-size: 1.2rem;
    }
    
    .calendar-sections {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .calendar-container {
        padding: 0 1rem;
    }
    
    .calendar-intro,
    .academic-year-info,
    .calendar-section,
    .important-dates,
    .download-section,
    .contact-section {
        padding: 1.5rem;
    }
    
    .year-highlight h4 {
        font-size: 2rem;
    }
    
    .year-highlight p {
        font-size: 1rem;
    }
}
</style>

<!-- New Banner -->
<section class="page-hero">
    <div class="container">
        <div class="content">
            <h1 class="title">College Academic Calendar</h1>
            <p class="subtitle">Academic Year 2024-2025</p>
        </div>
    </div>
</section>

<!-- Calendar Content -->
<section class="calendar-content">
    <div class="calendar-container">
        <!-- Introduction -->
        <div class="calendar-intro">
            <h2>College Academic Calendar</h2>
            <p>Stay informed about important academic dates, events, and deadlines for the College Academic Year 2024-2025. This calendar provides comprehensive information for students, faculty, and staff.</p>
        </div>

        <!-- Academic Year Information -->
        <div class="academic-year-info">
            <h3>Academic Year 2024-2025</h3>
            <div class="year-highlight">
                <h4>COLLEGIATE AY 2024-2025</h4>
                <p>University of Perpetual Help System JONELTA</p>
            </div>
            
            <div class="calendar-sections">
                <div class="calendar-section">
                    <h4>First Semester</h4>
                    <ul class="calendar-dates">
                        <li>
                            <span class="date-label">Opening of Classes</span>
                            <span class="date-value">August 2024</span>
                        </li>
                        <li>
                            <span class="date-label">Midterm Examinations</span>
                            <span class="date-value">October 2024</span>
                        </li>
                        <li>
                            <span class="date-label">Final Examinations</span>
                            <span class="date-value">December 2024</span>
                        </li>
                        <li>
                            <span class="date-label">Semester Break</span>
                            <span class="date-value">December 2024 - January 2025</span>
                        </li>
                    </ul>
                </div>

                <div class="calendar-section">
                    <h4>Second Semester</h4>
                    <ul class="calendar-dates">
                        <li>
                            <span class="date-label">Opening of Classes</span>
                            <span class="date-value">January 2025</span>
                        </li>
                        <li>
                            <span class="date-label">Midterm Examinations</span>
                            <span class="date-value">March 2025</span>
                        </li>
                        <li>
                            <span class="date-label">Final Examinations</span>
                            <span class="date-value">May 2025</span>
                        </li>
                        <li>
                            <span class="date-label">Summer Break</span>
                            <span class="date-value">May - August 2025</span>
                        </li>
                    </ul>
                </div>

                <div class="calendar-section">
                    <h4>Important Events</h4>
                    <ul class="calendar-dates">
                        <li>
                            <span class="date-label">Enrollment Period</span>
                            <span class="date-value">July - August 2024</span>
                        </li>
                        <li>
                            <span class="date-label">Orientation Week</span>
                            <span class="date-value">August 2024</span>
                        </li>
                        <li>
                            <span class="date-label">Foundation Day</span>
                            <span class="date-value">February 2025</span>
                        </li>
                        <li>
                            <span class="date-label">Graduation Ceremony</span>
                            <span class="date-value">May 2025</span>
                        </li>
                    </ul>
                </div>

                <div class="calendar-section">
                    <h4>Holidays & Breaks</h4>
                    <ul class="calendar-dates">
                        <li>
                            <span class="date-label">Christmas Break</span>
                            <span class="date-value">December 2024</span>
                        </li>
                        <li>
                            <span class="date-label">New Year Break</span>
                            <span class="date-value">January 2025</span>
                        </li>
                        <li>
                            <span class="date-label">Holy Week</span>
                            <span class="date-value">March 2025</span>
                        </li>
                        <li>
                            <span class="date-label">Summer Vacation</span>
                            <span class="date-value">May - August 2025</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Important Dates Notice -->
        <div class="important-dates">
            <h4>Important Notice</h4>
            <p>Please note that specific dates may be subject to change. Students and faculty are advised to regularly check official announcements and updates from the university administration. For the most current information, please refer to the official academic calendar or contact the Registrar's Office.</p>
        </div>

        <!-- Download Section -->
        <div class="download-section">
            <h3>Download Full Calendar</h3>
            <p>Get the complete College Academic Calendar 2024-2025 in PDF format for offline reference and printing.</p>
            <a href="https://uphsl.edu.ph/academic-calendar/COLLEGE%20ACADEMIC%20CALENDAR%202024-2025.pdf" target="_blank" class="download-btn">
                📥 Download PDF Calendar
            </a>
        </div>

        <!-- Contact Information -->
        <div class="contact-section">
            <h3>Need More Information?</h3>
            <p>For questions about the academic calendar or specific dates, please contact the Registrar's Office or visit the university website for the most up-to-date information.</p>
        </div>
    </div>
</section>

<script>
// Add interactive features for the calendar
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects for calendar sections
    const calendarSections = document.querySelectorAll('.calendar-section');
    
    calendarSections.forEach(section => {
        section.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        section.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add click effects for download button
    const downloadBtn = document.querySelector('.download-btn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    }
});
</script>

<?php
// Include footer
include '../app/includes/footer.php';
?>
