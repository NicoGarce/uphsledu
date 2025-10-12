<?php
/**
 * UPHSL Environmental Policy Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description Environmental Policy and sustainability initiatives of the University of Perpetual Help System Laguna
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Set page title
$page_title = "Environmental Policy";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
/* Professional Environmental Colors */
:root {
    --primary-green: #047857;
    --secondary-green: #059669;
    --accent-blue: #0ea5e9;
    --earth-brown: #92400e;
    --text-dark: #1f2937;
    --text-gray: #6b7280;
    --border-light: #e5e7eb;
    --bg-light: #f0fdf4;
    --bg-accent: #ecfdf5;
}

.environmental-hero {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
    color: white;
    padding: 5rem 0 3rem;
    position: relative;
    min-height: 50vh;
    display: flex;
    align-items: center;
}

.environmental-hero::before {
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

.environmental-hero::after {
    content: '🌱 🌿 🌳 🌍 🌊 ☀️ 🌱 🌿 🌳 🌍 🌊 ☀️';
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
    text-align: center;
}

.hero-content .subtitle {
    font-size: 1.4rem;
    font-weight: 400;
    margin-bottom: 2rem;
    opacity: 0.9;
    color: #ffffff;
}

.hero-content .motto {
    font-size: 1.1rem;
    font-style: italic;
    font-weight: 400;
    background: rgba(255,255,255,0.15);
    padding: 1rem 2rem;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,0.2);
    display: inline-block;
    margin-top: 1.5rem;
    color: #ffffff;
}

.policy-content {
    padding: 3rem 0;
    background: var(--bg-light);
    position: relative;
    width: 100%;
}


.content-section {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    position: relative;
    border: 1px solid var(--border-light);
    max-width: 1000px;
    margin-left: auto;
    margin-right: auto;
    width: 95%;
}

.content-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-green);
    border-radius: 8px 8px 0 0;
}

.content-section h2 {
    color: var(--primary-green);
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: left;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.content-section h2::before {
    content: '🌍';
    font-size: 1.5rem;
    opacity: 0.7;
}

.content-section h3 {
    color: var(--secondary-green);
    font-size: 1.4rem;
    font-weight: 600;
    margin: 1.5rem 0 1rem 0;
    border-left: 3px solid var(--secondary-green);
    padding-left: 1rem;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.content-section h3::before {
    content: '🌱';
    font-size: 1.2rem;
    opacity: 0.8;
}

.content-section p {
    font-size: 1rem;
    line-height: 1.6;
    color: var(--text-gray);
    margin-bottom: 1.2rem;
    text-align: left;
    text-indent: 0;
    max-width: 100%;
}

.objectives-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
    gap: 2rem;
    margin: 2rem 0;
    max-width: 100%;
}

.objective-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid var(--border-light);
    transition: all 0.2s ease;
    position: relative;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.objective-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--secondary-green);
    border-radius: 8px 8px 0 0;
}

.objective-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: var(--secondary-green);
}

.objective-card h4 {
    color: var(--primary-green);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.objective-card h4::before {
    content: '🌿';
    font-size: 1.1rem;
    opacity: 0.8;
}

.objective-card ul {
    list-style: none;
    padding: 0;
}

.objective-card li {
    padding: 0.5rem 0;
    position: relative;
    padding-left: 1.5rem;
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--text-gray);
}

.objective-card li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: white;
    background: var(--secondary-green);
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
}

.energy-conservation {
    background: #f0f9ff;
    border: 1px solid var(--border-light);
    position: relative;
}

.energy-conservation h3 {
    color: var(--accent-blue);
    border-left-color: var(--accent-blue);
}

.energy-conservation h3::before {
    content: '⚡';
}

.energy-conservation .energy-item {
    background: white;
    padding: 1.5rem;
    margin: 1rem 0;
    border-radius: 6px;
    border-left: 3px solid var(--accent-blue);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.energy-conservation .energy-item:hover {
    transform: translateX(5px);
}

.energy-conservation .energy-item h4 {
    color: var(--accent-blue);
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.energy-conservation .energy-item h4::before {
    content: '💡';
    font-size: 1rem;
    opacity: 0.8;
}

.responsibilities {
    background: #fefce8;
    border: 1px solid var(--border-light);
    position: relative;
}

.responsibilities h3 {
    color: var(--earth-brown);
    border-left-color: var(--earth-brown);
}

.responsibilities h3::before {
    content: '🏛️';
}

.responsibilities .responsibility-item {
    background: white;
    padding: 1.5rem;
    margin: 1rem 0;
    border-radius: 6px;
    border-left: 3px solid var(--earth-brown);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.responsibilities .responsibility-item:hover {
    transform: translateX(5px);
}

.responsibilities .responsibility-item h4 {
    color: var(--earth-brown);
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.responsibilities .responsibility-item h4::before {
    content: '👥';
    font-size: 1rem;
    opacity: 0.8;
}

.quote-section {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--secondary-green) 100%);
    color: white;
    padding: 2rem 0;
    text-align: center;
    margin: 2rem auto;
    position: relative;
    max-width: 1000px;
    width: 95%;
    border-radius: 8px;
}

.quote-section h2 {
    color: white;
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.quote-section p {
    font-size: 1.1rem;
    font-style: italic;
    opacity: 0.9;
    max-width: 500px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.intro-quote {
    background: var(--bg-accent);
    border: 1px solid var(--secondary-green);
    border-radius: 8px;
    padding: 1.5rem 2rem;
    margin: 1.5rem 0;
    text-align: center;
    position: relative;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    font-style: italic;
    font-weight: 500;
    color: var(--primary-green);
}

.intro-quote::before {
    content: '"';
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 2rem;
    color: var(--primary-green);
    background: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--secondary-green);
    font-weight: bold;
}

.intro-quote p {
    font-size: 1.1rem;
    font-weight: 500;
    color: var(--primary-green);
    margin: 0;
    font-style: italic;
}

/* Full width layout adjustments */
.container {
    max-width: 100%;
    padding: 0 2rem;
}

.policy-content .container {
    max-width: 100%;
    padding: 0 1rem;
}

@media (max-width: 1200px) {
    .content-section {
        width: 98%;
        padding: 2.5rem 2.5rem;
    }
    
    .quote-section {
        width: 98%;
    }
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .hero-content .subtitle {
        font-size: 1.2rem;
    }
    
    .content-section {
        padding: 2rem 1.5rem;
        width: 98%;
    }
    
    .content-section h2 {
        font-size: 2rem;
    }
    
    .content-section h2::after {
        display: none;
    }
    
    .content-section p {
        text-indent: 0;
        font-size: 1rem;
    }
    
    .objectives-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .objective-card {
        padding: 1.5rem;
    }
    
    .quote-section {
        width: 98%;
        padding: 2.5rem 1.5rem;
    }
    
    .quote-section h2 {
        font-size: 1.8rem;
    }
    
    .quote-section p {
        font-size: 1.1rem;
    }
    
    .intro-quote {
        padding: 1.2rem 1.5rem;
        margin: 1.2rem 0;
        max-width: 90%;
    }
}
</style>

<!-- Environmental Policy Hero Section -->
<section class="environmental-hero">
    <div class="container">
        <div class="hero-content">
            <h1>Environmental Policy</h1>
            <p class="subtitle">Leading the way to give awareness about the changing climate</p>
            <div class="motto">"Character Building is Nation Building"</div>
        </div>
    </div>
</section>

<!-- Policy Content -->
<section class="policy-content">
    <div class="container">
        <!-- Introduction -->
        <div class="content-section">
            <h2>We Believe in the Bottom Line Concept of the Changing Climate</h2>
            
            <div class="intro-quote">
                <p>"Character Building is Nation Building"</p>
            </div>
            
            <p>The University of Perpetual Help System Laguna (UPHSL) as an institution of basic and higher learning strongly recognizes that environmental policy is pivotal to protecting and nurturing the environment, and thus shall be crafted, implemented, and evaluated. As part of its quality policy dovetailed with the university vision and mission statement, the environmental policy is subsumed under corporate social responsibility of the institution, guiding all stakeholders in terms of resources use sustainability and prevention of practices that lead to waste and damage to the environment.</p>
            
            <p>UPHSL aims at managing its school operations which observe environmental protection and sustainability, social responsibility and economic practicability.</p>
            
            <p>Guided by the university's commitment to safeguarding and conserving the environment, this document was laid out, providing the aims and objectives for protecting the environment as well as the details showing the relationship between the university stakeholders and the natural environment with mutual reciprocation. It also provides information covering the responsibility of the concerned units of the institution and the mechanisms followed for implementing and monitoring environmental protection.</p>
            
            <p>This policy applies to all land, premises and activities involving university control which has been approved by the university's chief executives in coordination with college deans, department heads and other concerned stakeholders. As may be necessary, this policy is subject to review in order to make the policy continuously responsive and relevant to the changing landscape of our time.</p>
        </div>

        <!-- Aims and Objectives -->
        <div class="content-section">
            <h2>Let the younger mind be aware about the changing climate</h2>
            <h3>Aims and Objectives</h3>
            
            <div class="objectives-grid">
                <div class="objective-card">
                    <h4>Environmental Management</h4>
                    <ul>
                        <li>To promote comprehensive environmental management policies and practices throughout the University</li>
                        <li>As a minimum, to abide with the requirements of relevant legislation</li>
                        <li>To lessen and, where realistic, avoid pollution</li>
                        <li>To implement the objectives for improving environmental performance</li>
                        <li>To guarantee an all-encompassing understanding of existing environmental performance</li>
                    </ul>
                </div>

                <div class="objective-card">
                    <h4>Carbon Management</h4>
                    <ul>
                        <li>To implement a carbon management scheme, as well as the ideal and effective use of energy</li>
                        <li>To decrease greenhouse gas emissions in cognizance with the University targets; 6% on 2005/06 levels by 2025</li>
                        <li>To warrant the application of low carbon technologies in buildings and equipment</li>
                    </ul>
                </div>

                <div class="objective-card">
                    <h4>Transport</h4>
                    <ul>
                        <li>To implement maintainable transport practices across all activities with the aim of attaining the University's carbon reduction goals</li>
                    </ul>
                </div>

                <div class="objective-card">
                    <h4>Water</h4>
                    <ul>
                        <li>To make effective and environmentally responsible use of water, including identifying projections for water reuse</li>
                    </ul>
                </div>

                <div class="objective-card">
                    <h4>Procurement</h4>
                    <ul>
                        <li>To promote long-term cycle in the procurement policies of goods and services</li>
                        <li>To work closely with suppliers to promote sustainable resource management practices</li>
                    </ul>
                </div>

                <div class="objective-card">
                    <h4>Waste Reduction and Recycling</h4>
                    <ul>
                        <li>To set and achieve targets for reducing resource use</li>
                        <li>To abate the adverse environmental impacts of the decommissioning and disposal of University assets</li>
                        <li>To intensify the rate of recycling of all proper materials, based on life-cycle principles</li>
                        <li>To implement justifiable resource management practices, based on reduce, reuse and recycle principles</li>
                    </ul>
                </div>

                <div class="objective-card">
                    <h4>University Estate</h4>
                    <ul>
                        <li>To improve and implement a University estate strategy based on sound environmental and sustainability principles</li>
                        <li>To manage the University resources with an end view of improving biodiversity wherever possible</li>
                        <li>To entail a sustainable construction plan for any new University development and renovation project</li>
                    </ul>
                </div>

                <div class="objective-card">
                    <h4>Awareness and Training</h4>
                    <ul>
                        <li>To internally and externally disseminate the University's environmental objectives and performance to various stakeholders</li>
                        <li>To increase awareness of the teaching, non-teaching staffs and students of the University's environmental effect, activities and performance and good practice</li>
                        <li>To offer suitable environmental educational programs for teaching, non-teaching staffs and students</li>
                        <li>To encourage and enable feedback and suggestions on ensuring good practice</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Energy Conservation -->
        <div class="content-section energy-conservation">
            <h2>Energy Conservation</h2>
            <p>The University strictly implements the following energy conservation guide for all employees, students and other concerned stakeholders.</p>
            
            <div class="energy-item">
                <h4>1. Interior Environment</h4>
                <p>For all air-conditioned rooms and offices, the temperature shall be set from 20 to 24 degrees Celsius. Special environmental needs requiring colder temperatures are excluded like server rooms and computer laboratories. Meanwhile, the control of airconditioning system is operated only by authorized personnel (housekeepers and security guards).</p>
            </div>

            <div class="energy-item">
                <h4>2. Lighting</h4>
                <p>University personnel and students shall make every effort to reduce the amount of energy associated with lighting in all campus' facilities. This is implemented by switching off lights when not in use and using more efficient lighting (e.g., compact fluorescent or light-emitting diode (LED). Whenever possible, natural light should be used.</p>
            </div>

            <div class="energy-item">
                <h4>3. Computers</h4>
                <p>The University should purchase computer units with energy-saving features and should utilize computer power management software (excluding units with unique computational function) that can minimize electricity operation and consumption when computer units are not used. Peripheral equipment of computer units should be turned off, whenever possible.</p>
            </div>

            <div class="energy-item">
                <h4>4. Office Equipment</h4>
                <p>When not in use, all office equipment and appliance, if any, should be turned off (unless otherwise specified in the operation specification). University personnel shall turn off electrical items like printers and copiers at the end of the work period.</p>
            </div>

            <div class="energy-item">
                <h4>5. Fume Hoods (CIHM students and faculty members)</h4>
                <p>When not used, fume hood sashes should be closed in order to minimize energy use and improved laboratory safety. Moreover, fume hoods that will not be used for a long period of time should be reported to the Engineering Service Department for shutdown.</p>
            </div>
        </div>

        <!-- Evaluation -->
        <div class="content-section">
            <h2>Evaluation of Environmental Policy</h2>
            <p>To conduct a systematic review of environmental management measures and activities to guarantee suitability, appropriateness and efficiency.</p>
        </div>

        <!-- Responsibilities -->
        <div class="content-section responsibilities">
            <h2>Responsibilities</h2>
            
            <div class="responsibility-item">
                <h4>1. Executive Leadership</h4>
                <p>The core responsibility for implementation of this policy lies with the Executive School Director and the assistant School Director as the University's Chief Executives.</p>
            </div>

            <div class="responsibility-item">
                <h4>2. College and Department Heads</h4>
                <p>The Deans of the various colleges and the Director of the Student-Personnel Services are responsible for safeguarding compliance with University Environment Policy within their area of control.</p>
            </div>

            <div class="responsibility-item">
                <h4>3. Performance Monitoring</h4>
                <p>The University will dynamically monitor the performance of Colleges and Divisions in the implementation of the goals and purposes of this policy in the activities under their control.</p>
            </div>

            <div class="responsibility-item">
                <h4>4. Stakeholder Cooperation</h4>
                <p>While the University takes the accountability for the execution of this policy, stakeholders have a very important role in cooperating with those responsible for the protection of the environment. They are required to abide by the guidelines and requirements made under the authority of this policy.</p>
            </div>
        </div>

        <!-- Quote Section -->
        <div class="quote-section">
            <h2>Environmental Commitment</h2>
            <p>"We're leading the way to give awareness about the changing Climate"</p>
        </div>
    </div>
</section>

<?php
// Include footer
include '../app/includes/footer.php';
?>
