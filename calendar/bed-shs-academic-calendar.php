<?php
/**
 * UPHSL BED & SHS Academic Calendar Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Basic Education Department (Kinder to Grade 12) Academic Calendar for AY 2024-2025
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "BED & SHS Academic Calendar";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
/* New page hero */
.page-hero { position: relative; padding: 80px 0; color: #fff; text-align: center; isolation: isolate; overflow: hidden; background: url('../assets/images/banners/UPHSL%20Facade.png') center/cover no-repeat; }
.page-hero::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(28,77,161,.85), rgba(82,123,189,.85)); z-index: 1; }
.page-hero .content { position: relative; z-index: 2; display: inline-block; padding: 24px 28px; border-radius: 16px; background: rgba(0,0,0,.35); -webkit-backdrop-filter: blur(8px); backdrop-filter: blur(8px); box-shadow: 0 10px 30px rgba(0,0,0,.25); }
.page-hero .title { font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,.3); }
.page-hero .subtitle { font-size: 1.3rem; margin: 0; }
@media (max-width: 1024px){ .page-hero{ padding:60px 0; } .page-hero .content{ padding:16px 18px; border-radius:12px; } .page-hero .title{ font-size:2.5rem; } .page-hero .subtitle{ font-size:1.1rem; } }
/* BED & SHS Calendar Page Colors - inherit site branding */
:root {
    --primary-blue: var(--primary-color);
    --secondary-blue: var(--secondary-color);
    --accent-green: #059669;
    --text-dark: #1f2937;
    --text-gray: #6b7280;
    --border-light: #e5e7eb;
    --bg-light: #f8fafc;
    --bg-accent: #f1f5f9;
    --calendar-red: #dc2626;
    --calendar-orange: #ea580c;
    --calendar-purple: #7c3aed;
    --calendar-pink: #ec4899;
    --calendar-indigo: #6366f1;
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
    content: '🎒';
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
    content: '📚';
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

.school-days-info {
    background: linear-gradient(135deg, var(--accent-purple) 0%, #8b5cf6 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    margin-bottom: 2rem;
}

.school-days-info h4 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.school-days-info p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.calendar-months {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.calendar-month {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid var(--primary-blue);
}

.calendar-month h4 {
    color: var(--primary-blue);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-bottom: 2px solid var(--border-light);
    padding-bottom: 0.5rem;
}

.calendar-month h4::before {
    content: '📅';
    font-size: 1.2rem;
    opacity: 0.8;
}

.month-days {
    font-size: 0.9rem;
    color: var(--text-gray);
    margin-bottom: 1rem;
    font-weight: 500;
}

.calendar-events {
    list-style: none;
    padding: 0;
    margin: 0;
}

.calendar-events li {
    padding: 0.8rem 0;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
}

.calendar-events li:last-child {
    border-bottom: none;
}

.event-date {
    font-weight: 600;
    color: var(--text-dark);
    min-width: 60px;
    font-size: 0.9rem;
}

.event-details {
    flex: 1;
    color: var(--text-gray);
    font-size: 0.9rem;
    line-height: 1.4;
}

.event-venue {
    color: var(--primary-blue);
    font-size: 0.8rem;
    font-weight: 500;
    margin-top: 0.2rem;
}

.core-value {
    background: var(--bg-accent);
    padding: 0.3rem 0.8rem;
    border-radius: 6px;
    font-size: 0.8rem;
    color: var(--accent-purple);
    font-weight: 500;
    margin-top: 0.5rem;
    display: inline-block;
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
    
    .calendar-months {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .calendar-container {
        padding: 0 1rem;
    }
    
    .calendar-intro,
    .academic-year-info,
    .calendar-month,
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
    
    .calendar-events li {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .event-date {
        min-width: auto;
    }
}
</style>

<!-- New Banner -->
<section class="page-hero">
    <div class="container">
        <div class="content">
            <h1 class="title">BED & SHS Academic Calendar</h1>
            <p class="subtitle">Basic Education Department • Academic Year 2024-2025</p>
        </div>
    </div>
</section>

<!-- Calendar Content -->
<section class="calendar-content">
    <div class="calendar-container">
        <!-- Introduction -->
        <div class="calendar-intro">
            <h2>Basic Education Department Academic Calendar</h2>
            <p>Comprehensive academic calendar for Kindergarten to Grade 12 students, including important dates, activities, examinations, and special events for the Academic Year 2024-2025.</p>
        </div>

        <!-- Academic Year Information -->
        <div class="academic-year-info">
            <h3>Academic Year 2024-2025</h3>
            <div class="year-highlight">
                <h4>BASIC EDUCATION DEPARTMENT</h4>
                <p>University of Perpetual Help System Laguna</p>
            </div>
            
            <div class="school-days-info">
                <h4>Total School Days</h4>
                <p>197 School Days for the Academic Year</p>
            </div>
        </div>

        <!-- Calendar Months -->
        <div class="calendar-months">
            <!-- July 2024 -->
            <div class="calendar-month">
                <h4>July 2024</h4>
                <div class="month-days">8 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">17</span>
                        <div class="event-details">
                            <div>Parents' Orientation (K-12) (thru Zoom)</div>
                            <div class="event-venue">Online/Zoom</div>
                            <div class="core-value">Mission and Vision Month</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">22</span>
                        <div class="event-details">
                            <div>Opening of Classes</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">23-26</span>
                        <div class="event-details">
                            <div>General Students' Orientation</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">25</span>
                        <div class="event-details">
                            <div>Mental Health Awareness Seminar (K-12)</div>
                            <div class="event-venue">Online/Zoom</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">26</span>
                        <div class="event-details">
                            <div>Mass of the Holy Spirit (K-12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">31</span>
                        <div class="event-details">
                            <div>Nutrition Month Culminating Activity</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- August 2024 -->
            <div class="calendar-month">
                <h4>August 2024</h4>
                <div class="month-days">20 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">21</span>
                        <div class="event-details">
                            <div>Ninoy Aquino Day</div>
                            <div class="core-value">UPHS and the Perpetualite</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">26</span>
                        <div class="event-details">
                            <div>National Heroes' Day</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">29</span>
                        <div class="event-details">
                            <div>Culminating Activity for Buwan ng Wika (Kinder to Grade 10) (Filipino)</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">30</span>
                        <div class="event-details">
                            <div>Culminating Activity for Buwan ng Wika (Senior High School) (Filipino)</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- September 2024 -->
            <div class="calendar-month">
                <h4>September 2024</h4>
                <div class="month-days">21 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">4</span>
                        <div class="event-details">
                            <div>International Literacy Day and E-Drive Launching & Campaign (English)</div>
                            <div class="event-venue">PAT</div>
                            <div class="core-value">Celebration Of Life, Value Of Catholic Doctrine</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">6</span>
                        <div class="event-details">
                            <div>Birthday of Mama Mary - Mass</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">10</span>
                        <div class="event-details">
                            <div>FOUNDER'S DAY</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">11-13</span>
                        <div class="event-details">
                            <div>Midterm Examination/1st Quarterly Examination (K-12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">16-17</span>
                        <div class="event-details">
                            <div>Special Examination (K-12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">19</span>
                        <div class="event-details">
                            <div>Suicide Prevention Month Celebration (K-12)</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">23-27</span>
                        <div class="event-details">
                            <div>Encoding and Deliberation of Grades (K-12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">27</span>
                        <div class="event-details">
                            <div>E-Drive Culminating Activity</div>
                            <div class="event-venue">PAT/Mini Audi</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- October 2024 -->
            <div class="calendar-month">
                <h4>October 2024</h4>
                <div class="month-days">23 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">4</span>
                        <div class="event-details">
                            <div>Teacher's Day Celebration Launching of Holy Rosary Month and Mass</div>
                            <div class="event-venue">PAT</div>
                            <div class="core-value">Love of God, Self, and Neighbor</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">18</span>
                        <div class="event-details">
                            <div>Celebration and Parade of Saints and United Nation (Araling Panlipunan & FCL)</div>
                            <div class="event-venue">Mini Gym</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">21-25</span>
                        <div class="event-details">
                            <div>University Week 2023</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">24</span>
                        <div class="event-details">
                            <div>PRESIDENT'S DAY</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">28 - Nov 1</span>
                        <div class="event-details">
                            <div>Academic Break (K-12)</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- November 2024 -->
            <div class="calendar-month">
                <h4>November 2024</h4>
                <div class="month-days">20 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">1</span>
                        <div class="event-details">
                            <div>All Saints' Day</div>
                            <div class="core-value">Love Of Country And Good</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">2</span>
                        <div class="event-details">
                            <div>All Souls' Day</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">4</span>
                        <div class="event-details">
                            <div>Resumption of Classes</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">6</span>
                        <div class="event-details">
                            <div>First Wednesday Mass</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">15</span>
                        <div class="event-details">
                            <div>Science and Math Culminating Activity</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- December 2024 -->
            <div class="calendar-month">
                <h4>December 2024</h4>
                <div class="month-days">15 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">2</span>
                        <div class="event-details">
                            <div>Start of Second Semester</div>
                            <div class="core-value">Peace And Global Solidarity</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">2</span>
                        <div class="event-details">
                            <div>Special Examination</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">2</span>
                        <div class="event-details">
                            <div>Mass and Opening of Advent Season</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">2-6</span>
                        <div class="event-details">
                            <div>Research Proposal - Grade 12</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">6</span>
                        <div class="event-details">
                            <div>First Communion (Grade 3)</div>
                            <div class="event-venue">UPHSL Shrine</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">8</span>
                        <div class="event-details">
                            <div>Feast of the Immaculate Conception</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">9-13</span>
                        <div class="event-details">
                            <div>Deliberation and Encoding of Grades (K-12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">13</span>
                        <div class="event-details">
                            <div>MAPEH Culminating Activity</div>
                            <div class="event-venue">Mini Gym/PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">16</span>
                        <div class="event-details">
                            <div>Homeroom Christmas Party</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">20</span>
                        <div class="event-details">
                            <div>Family Day</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- January 2025 -->
            <div class="calendar-month">
                <h4>January 2025</h4>
                <div class="month-days">19 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">6</span>
                        <div class="event-details">
                            <div>Class Resumption (K-12)</div>
                            <div class="core-value">Health And Ecological Consciousness</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">8</span>
                        <div class="event-details">
                            <div>First Wednesday Mass</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">29</span>
                        <div class="event-details">
                            <div>Chinese New Year</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">30</span>
                        <div class="event-details">
                            <div>Business High School Culminating Activity</div>
                            <div class="event-venue">Mini-Gym/Mini Audi</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">31</span>
                        <div class="event-details">
                            <div>Science High School Culminating Activity</div>
                            <div class="event-venue">Mini-Gym/Mini Audi</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">30-31</span>
                        <div class="event-details">
                            <div>Strand Camp 2025</div>
                            <div class="event-venue">Big Gym, PAT, Mini Gym, Mini Auditorium</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- February 2025 -->
            <div class="calendar-month">
                <h4>February 2025</h4>
                <div class="month-days">19 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">3</span>
                        <div class="event-details">
                            <div>Biñan Day/Liberation</div>
                            <div class="core-value">Filipino Christian Leadership</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">5-7</span>
                        <div class="event-details">
                            <div>Midterm Examination (SHS)/3rd Quarterly Examination - (K-10)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">10-11</span>
                        <div class="event-details">
                            <div>Special Examination (K-12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">11-14</span>
                        <div class="event-details">
                            <div>Research Proposal - Grade 11</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">14</span>
                        <div class="event-details">
                            <div>TLE/HELE Culminating Activity and Mass Celebration</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">17-21</span>
                        <div class="event-details">
                            <div>Deliberation and Encoding of Grades (K-12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">21</span>
                        <div class="event-details">
                            <div>Junior High School Prom</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">22</span>
                        <div class="event-details">
                            <div>Dra. Josefina Tamayo Memorial Day</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">25</span>
                        <div class="event-details">
                            <div>EDSA People Power Revolution</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- March 2025 -->
            <div class="calendar-month">
                <h4>March 2025</h4>
                <div class="month-days">21 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">5</span>
                        <div class="event-details">
                            <div>First Wednesday Mass/Sacrament of Confirmation</div>
                            <div class="event-venue">UPHSL-Shrine</div>
                            <div class="core-value">Character Building is Nation Building Month</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">5</span>
                        <div class="event-details">
                            <div>Recollection</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">14</span>
                        <div class="event-details">
                            <div>Career Day (GS) and Career Talk (JHS)</div>
                            <div class="event-venue">Mini-Gym/Mini Audi</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">17-21</span>
                        <div class="event-details">
                            <div>Final Defense (Grade 6 and 10)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">27-28</span>
                        <div class="event-details">
                            <div>Final Examination for Grade 11 and 12</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">30</span>
                        <div class="event-details">
                            <div>Eid al-Fitr</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- April 2025 -->
            <div class="calendar-month">
                <h4>April 2025</h4>
                <div class="month-days">19 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">2-4</span>
                        <div class="event-details">
                            <div>Basic Education Days and Family Day 2024</div>
                            <div class="event-venue">Big Gym, PAT, Mini Gym, Mini Auditorium</div>
                            <div class="core-value">Perpetualite Family</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">7-11</span>
                        <div class="event-details">
                            <div>Deliberation and Encoding of Grades (Grade 12)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">8</span>
                        <div class="event-details">
                            <div>Research Colloquium/Capstone Presentation</div>
                            <div class="event-venue">PAT and College Lobby</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">8, 10-11</span>
                        <div class="event-details">
                            <div>Final Examination (K to 11)</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">21-25</span>
                        <div class="event-details">
                            <div>Deliberation and Encoding of Grades</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- May 2025 -->
            <div class="calendar-month">
                <h4>May 2025</h4>
                <div class="month-days">21 School Days</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">1</span>
                        <div class="event-details">
                            <div>Labor Day</div>
                            <div class="core-value">Perpetualites – The Helpers of God Month</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">2</span>
                        <div class="event-details">
                            <div>Student Council Election Day</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">6</span>
                        <div class="event-details">
                            <div>Thanksgiving Mass</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">7</span>
                        <div class="event-details">
                            <div>Recognition Grade School</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">7</span>
                        <div class="event-details">
                            <div>Recognition JHS</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">8</span>
                        <div class="event-details">
                            <div>Recognition SHS</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">9</span>
                        <div class="event-details">
                            <div>Graduation Grade School</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">9</span>
                        <div class="event-details">
                            <div>Completers Day</div>
                            <div class="event-venue">PAT</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">16</span>
                        <div class="event-details">
                            <div>Graduation SHS</div>
                            <div class="event-venue">PICC</div>
                        </div>
                    </li>
                    <li>
                        <span class="event-date">23</span>
                        <div class="event-details">
                            <div>Distribution of Report Card – (K-12)</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- June 2025 -->
            <div class="calendar-month">
                <h4>June 2025</h4>
                <div class="month-days">Special Activities</div>
                <ul class="calendar-events">
                    <li>
                        <span class="event-date">5-6</span>
                        <div class="event-details">
                            <div>Basic Ed Team Building</div>
                            <div class="event-venue">Baguio City</div>
                            <div class="core-value">Mission and Vision Month</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Important Dates Notice -->
        <div class="important-dates">
            <h4>Important Notice</h4>
            <p>Please note that specific dates may be subject to change. Students, parents, and faculty are advised to regularly check official announcements and updates from the Basic Education Department. For the most current information, please refer to the official academic calendar or contact the Basic Education Director's Office.</p>
        </div>

        <!-- Download Section -->
        <div class="download-section">
            <h3>Download Full Calendar</h3>
            <p>Get the complete BED & SHS Academic Calendar 2024-2025 in PDF format for offline reference and printing.</p>
            <a href="https://uphsl.edu.ph/academic-calendar/BED%20&%20SHS%20ACADEMIC%20CALENDAR%202024-2025.pdf" target="_blank" class="download-btn">
                📥 Download PDF Calendar
            </a>
        </div>

        <!-- Contact Information -->
        <div class="contact-section">
            <h3>Need More Information?</h3>
            <p>For questions about the academic calendar or specific dates, please contact the Basic Education Director's Office or visit the university website for the most up-to-date information.</p>
        </div>
    </div>
</section>

<script>
// Add interactive features for the calendar
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects for calendar months
    const calendarMonths = document.querySelectorAll('.calendar-month');
    
    calendarMonths.forEach(month => {
        month.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        month.addEventListener('mouseleave', function() {
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
