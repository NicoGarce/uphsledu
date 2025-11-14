<?php
/**
 * UPHSL University Policy Page
 * 
 * @author Nico Roell D. Garce
 * @title UPHSL Web Administrator 2025
 * @description University regulations, codes of conduct and policies for UPHSL
 */

session_start();
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

// Check if this sub-page or About section is in maintenance
if (isSectionInMaintenance('about', 'university-policy') || isSectionInMaintenance('about')) {
    $page_title = "University Policy - Maintenance";
    $base_path = '../';
    include '../app/includes/header.php';
    if (displaySectionMaintenance('about', $base_path, 'university-policy')) {
        include '../app/includes/footer.php';
        exit;
    }
}

// Set page title
$page_title = "University Policy";
$base_path = '../';

// Include header
include '../app/includes/header.php';
?>

<style>
/* Professional Rule Book Colors */
:root {
    --primary-blue: #1e40af;
    --secondary-blue: #3b82f6;
    --accent-green: #059669;
    --text-dark: #1f2937;
    --text-gray: #6b7280;
    --border-light: #e5e7eb;
    --bg-light: #f8fafc;
    --bg-accent: #f1f5f9;
    --rule-red: #dc2626;
    --rule-orange: #ea580c;
}

.policy-hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    padding: 5rem 0 3rem;
    position: relative;
    min-height: 50vh;
    display: flex;
    align-items: center;
}

.policy-hero::before {
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

.policy-hero::after {
    content: '📋 📖 📜 ⚖️ 📋 📖 📜 ⚖️ 📋 📖 📜 ⚖️';
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

.hero-content .description {
    font-size: 1.1rem;
    font-weight: 400;
    opacity: 0.8;
    color: #ffffff;
    max-width: 600px;
    margin: 0 auto;
}

.policy-content {
    padding: 3rem 0;
    background: var(--bg-light);
    position: relative;
    width: 100%;
}

.rule-book-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

.rule-book-intro {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-left: 5px solid var(--primary-blue);
    text-align: center;
}

.rule-book-intro h2 {
    color: var(--primary-blue);
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.rule-book-intro h2::before {
    content: '📚';
    font-size: 1.8rem;
}

.rule-book-intro p {
    font-size: 1.1rem;
    color: var(--text-gray);
    line-height: 1.6;
    margin: 0;
}

.alphabet-nav {
    background: white;
    padding: 2rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-align: center;
}

.alphabet-nav h3 {
    color: var(--text-dark);
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.alphabet-nav h3::before {
    content: '🔤';
    font-size: 1.2rem;
}

.alphabet-grid {
    display: grid;
    grid-template-columns: repeat(13, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 0.4rem;
    max-width: 800px;
    margin: 0 auto;
    width: 100%;
    padding: 0 2rem;
    justify-content: center;
}

.alphabet-letter {
    background: var(--bg-accent);
    color: var(--primary-blue);
    padding: 0.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.2s ease;
    border: 2px solid transparent;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 45px;
    aspect-ratio: 1;
}

.alphabet-letter:hover {
    background: var(--primary-blue);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.alphabet-letter.active {
    background: var(--primary-blue);
    color: white;
    border-color: var(--secondary-blue);
}

.policies-section {
    background: white;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 3px solid var(--primary-blue);
}

.letter-indicator {
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
    position: relative;
    flex-shrink: 0;
}

.letter-indicator::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    border-radius: 50%;
    z-index: -1;
    opacity: 0.3;
}

.policies-section h3 {
    color: var(--text-dark);
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    flex: 1;
}

.policies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.policy-item {
    background: var(--bg-accent);
    padding: 1.5rem;
    border-radius: 8px;
    border-left: 4px solid var(--primary-blue);
    transition: all 0.2s ease;
    position: relative;
}

.policy-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-left-color: var(--secondary-blue);
}

.policy-item h4 {
    color: var(--primary-blue);
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.policy-item h4::before {
    content: '📄';
    font-size: 1rem;
    opacity: 0.8;
}

.policy-item p {
    color: var(--text-gray);
    font-size: 0.95rem;
    line-height: 1.5;
    margin: 0;
}

.policy-content {
    margin-top: 1rem;
}

.policy-content p {
    margin-bottom: 1rem;
    font-size: 0.95rem;
    line-height: 1.6;
}

.policy-content ul {
    margin: 0.5rem 0 1rem 1.5rem;
    padding: 0;
}

.policy-content li {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
    color: var(--text-gray);
}

.policy-content strong {
    color: var(--primary-blue);
    font-weight: 600;
}

.important-notice {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border: 1px solid #fecaca;
    border-left: 5px solid var(--rule-red);
    padding: 2rem;
    margin: 2rem 0;
    border-radius: 8px;
    position: relative;
}

.important-notice::before {
    content: '⚠️';
    position: absolute;
    top: 1rem;
    right: 1rem;
    font-size: 1.5rem;
}

.important-notice h4 {
    color: var(--rule-red);
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.important-notice h4::before {
    content: '🚨';
    font-size: 1.1rem;
}

.important-notice p {
    color: #7f1d1d;
    font-size: 1rem;
    line-height: 1.6;
    margin: 0;
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
    
    .alphabet-grid {
        grid-template-columns: repeat(13, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 0.2rem;
        max-width: 100%;
        padding: 0 1rem;
        width: calc(100% - 2rem);
    }
    
    .alphabet-letter {
        padding: 0.2rem;
        font-size: 0.7rem;
        min-height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (max-width: 480px) {
        .alphabet-grid {
            gap: 0.1rem;
            padding: 0 0.25rem;
            width: calc(100% - 0.5rem);
        }
        
        .alphabet-letter {
            padding: 0.1rem;
            font-size: 0.6rem;
            min-height: 24px;
        }
    }
    
    @media (max-width: 360px) {
        .alphabet-grid {
            gap: 0.05rem;
            padding: 0 0.1rem;
            width: calc(100% - 0.2rem);
        }
        
        .alphabet-letter {
            padding: 0.05rem;
            font-size: 0.55rem;
            min-height: 22px;
        }
    }
    
    .policies-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .rule-book-container {
        padding: 0 1rem;
    }
    
    .rule-book-intro,
    .policies-section,
    .alphabet-nav {
        padding: 1.5rem;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .letter-indicator {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .policies-section h3 {
        font-size: 1.3rem;
    }
}
</style>

<!-- University Policy Hero Section -->
<section class="policy-hero">
    <div class="container">
        <div class="hero-content">
            <h1>University Policy</h1>
            <p class="subtitle">Regulations, Codes of Conduct and Policies</p>
            <p class="description">View the comprehensive A-Z list for guidance on University regulations, codes of conduct and policies that govern our academic community.</p>
        </div>
    </div>
</section>

<!-- Policy Content -->
<section class="policy-content">
    <div class="rule-book-container">
        <!-- Introduction -->
        <div class="rule-book-intro">
            <h2>University Regulations and Policies</h2>
            <p>This comprehensive guide contains all University regulations, codes of conduct, and policies that govern our academic community. Browse through our core policies below to access detailed information and official policy documents.</p>
        </div>

        <!-- Alphabetical Navigation -->
        <div class="alphabet-nav">
            <h3>Quick Policy Access A-Z</h3>
            <div class="alphabet-grid">
                <a href="#A" class="alphabet-letter">A</a>
                <a href="#B" class="alphabet-letter">B</a>
                <a href="#C" class="alphabet-letter">C</a>
                <a href="#D" class="alphabet-letter">D</a>
                <a href="#E" class="alphabet-letter">E</a>
                <a href="#F" class="alphabet-letter">F</a>
                <a href="#G" class="alphabet-letter">G</a>
                <a href="#H" class="alphabet-letter">H</a>
                <a href="#I" class="alphabet-letter">I</a>
                <a href="#J" class="alphabet-letter">J</a>
                <a href="#K" class="alphabet-letter">K</a>
                <a href="#L" class="alphabet-letter">L</a>
                <a href="#M" class="alphabet-letter">M</a>
                <a href="#N" class="alphabet-letter">N</a>
                <a href="#O" class="alphabet-letter">O</a>
                <a href="#P" class="alphabet-letter">P</a>
                <a href="#Q" class="alphabet-letter">Q</a>
                <a href="#R" class="alphabet-letter">R</a>
                <a href="#S" class="alphabet-letter">S</a>
                <a href="#T" class="alphabet-letter">T</a>
                <a href="#U" class="alphabet-letter">U</a>
                <a href="#V" class="alphabet-letter">V</a>
                <a href="#W" class="alphabet-letter">W</a>
                <a href="#X" class="alphabet-letter">X</a>
                <a href="#Y" class="alphabet-letter">Y</a>
                <a href="#Z" class="alphabet-letter">Z</a>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="important-notice">
            <h4>Important Notice</h4>
            <p>All policies and regulations are subject to periodic review and updates. Students, faculty, and staff are responsible for staying informed about current policies. For the most up-to-date information, please refer to the official policy documents or contact the appropriate administrative office.</p>
        </div>

        <!-- Policies by Letter -->
        <div class="policies-section" id="A">
            <div class="section-header">
                <div class="letter-indicator">A</div>
                <h3>Academic & Administrative Policies</h3>
            </div>
            <div class="policies-grid">
                <div class="policy-item">
                    <h4>Anti-Bribery and Corruption Policy</h4>
                    <div class="policy-content">
                        <p><strong>Overview:</strong> UPHSL is committed to upholding the highest standards of integrity, transparency, and accountability across all areas of operation. The institution maintains a strict zero-tolerance policy on bribery and corruption.</p>
                        <p><strong>Scope:</strong> This policy applies to all employees, faculty, administrators, officers, contractors, students, and third-party representatives acting on behalf of the institution.</p>
                        <p><strong>Key Points:</strong></p>
                        <ul>
                            <li>Bribery—defined as offering, giving, receiving, or soliciting anything of value to improperly influence a decision—is strictly prohibited</li>
                            <li>Corruption, or the misuse of power for personal gain, will not be tolerated in any form</li>
                            <li>Prohibited practices include: offering or accepting bribes, misusing institutional funds, exchanging gifts or favors to influence decisions, or engaging in nepotism or favoritism</li>
                            <li>All personnel have a duty to report suspected cases through appropriate institutional channels</li>
                            <li>Reports will be handled with confidentiality, and whistleblowers will be protected from retaliation</li>
                            <li>Violations may result in disciplinary action, including termination, and may lead to legal consequences</li>
                        </ul>
                    </div>
                </div>
                <div class="policy-item">
                    <h4>Academic Freedom Policy</h4>
                    <div class="policy-content">
                        <p><strong>Purpose:</strong> UPHSL affirms its commitment to upholding and protecting the principle of academic freedom, ensuring that faculty, students, and staff can engage in intellectual inquiry, scholarship, and teaching without undue interference or restrictions.</p>
                        <p><strong>Definition:</strong> Academic freedom entails the rights of educational institutions, faculty, and students in higher education to determine for themselves on academic grounds who may teach, what may be taught, how it shall be taught, and who may be admitted to study.</p>
                        <p><strong>Key Rights Include:</strong></p>
                        <ul>
                            <li><strong>Teaching:</strong> Freedom to teach and discuss all relevant matters in the classroom</li>
                            <li><strong>Research:</strong> Freedom to explore any area of scholarship and to publish findings</li>
                            <li><strong>Expression:</strong> Freedom to express one's opinions as citizens without institutional censorship or discipline</li>
                            <li><strong>Curricula:</strong> The autonomy to determine the curricula and academic programs offered</li>
                        </ul>
                        <p><strong>Legal Framework:</strong> The 1987 Philippine Constitution guarantees academic freedom within the UPHSL community, stating "Academic freedom shall be enjoyed in all institutions of higher learning."</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="policies-section" id="D">
            <div class="section-header">
                <div class="letter-indicator">D</div>
                <h3>Diversity & Inclusion Policies</h3>
            </div>
            <div class="policies-grid">
                <div class="policy-item">
                    <h4>Diversity and Inclusion Policy</h4>
                    <div class="policy-content">
                        <p><strong>Mission:</strong> UPHSL promotes a community that is open, safe, and supportive, welcoming Perpetualites of all gender identities and backgrounds to thrive in an inclusive environment.</p>
                        <p><strong>Core Principles:</strong></p>
                        <ul>
                            <li>Fostering a diverse and inclusive environment that respects and values all members of the university community</li>
                            <li>Ensuring equal opportunities for all students, faculty, and staff regardless of background</li>
                            <li>Promoting understanding and respect for different perspectives and experiences</li>
                            <li>Creating safe spaces for open dialogue and learning</li>
                            <li>Addressing bias and discrimination through education and policy enforcement</li>
                        </ul>
                        <p><strong>Implementation:</strong> The policy is implemented through comprehensive training programs, inclusive hiring practices, student support services, and regular policy reviews to ensure effectiveness.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="policies-section" id="I">
            <div class="section-header">
                <div class="letter-indicator">I</div>
                <h3>Investment & Institutional Policies</h3>
            </div>
            <div class="policies-grid">
                <div class="policy-item">
                    <h4>Investment Policy Statement</h4>
                    <div class="policy-content">
                        <p><strong>Purpose:</strong> Provides a transparent framework for UPHSL's investment management, ensuring prudent financial management aligned with academic mission and institutional development.</p>
                        <p><strong>Key Objectives:</strong></p>
                        <ul>
                            <li>Preserve and grow the university's financial assets to support long-term institutional goals</li>
                            <li>Ensure investments align with the university's educational mission and values</li>
                            <li>Maintain appropriate risk management and diversification strategies</li>
                            <li>Ensure transparency and accountability in investment decisions</li>
                            <li>Comply with all applicable laws and regulations</li>
                        </ul>
                        <p><strong>Governance:</strong> The policy is overseen by the Board of Trustees and implemented by qualified investment professionals, with regular reporting and review processes.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="policies-section" id="M">
            <div class="section-header">
                <div class="letter-indicator">M</div>
                <h3>Modern Slavery & Management Policies</h3>
            </div>
            <div class="policies-grid">
                <div class="policy-item">
                    <h4>Modern Slavery Policy</h4>
                    <div class="policy-content">
                        <p><strong>Commitment:</strong> UPHSL is committed to preventing modern slavery, human trafficking, and forced labor in all aspects of university operations and supply chains.</p>
                        <p><strong>Scope:</strong> This policy applies to all university activities, including employment practices, procurement, partnerships, and supply chain management.</p>
                        <p><strong>Key Measures:</strong></p>
                        <ul>
                            <li>Conducting due diligence on all suppliers and business partners</li>
                            <li>Implementing robust recruitment and employment practices</li>
                            <li>Providing training and awareness programs for staff and students</li>
                            <li>Establishing clear reporting mechanisms for suspected cases</li>
                            <li>Regular auditing and monitoring of compliance</li>
                            <li>Collaboration with external organizations and authorities</li>
                        </ul>
                        <p><strong>Reporting:</strong> Any suspected cases of modern slavery should be reported immediately through the designated channels, with full protection for whistleblowers.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="policies-section" id="S">
            <div class="section-header">
                <div class="letter-indicator">S</div>
                <h3>Sustainable & Safety Policies</h3>
            </div>
            <div class="policies-grid">
                <div class="policy-item">
                    <h4>Sustainable Donations and Funding</h4>
                    <div class="policy-content">
                        <p><strong>Purpose:</strong> Guidelines for accepting and managing donations and funding in accordance with sustainability principles and ethical standards.</p>
                        <p><strong>Key Principles:</strong></p>
                        <ul>
                            <li>All donations must align with the university's mission and values</li>
                            <li>Transparency in the use of donated funds</li>
                            <li>Environmental and social impact considerations</li>
                            <li>Compliance with all applicable laws and regulations</li>
                            <li>Regular reporting to donors on fund utilization</li>
                        </ul>
                    </div>
                </div>
                <div class="policy-item">
                    <h4>Sustainable Procurement/Purchasing</h4>
                    <div class="policy-content">
                        <p><strong>Objective:</strong> Procedures for sustainable procurement practices that consider environmental and social impact in purchasing decisions.</p>
                        <p><strong>Implementation:</strong></p>
                        <ul>
                            <li>Preference for environmentally friendly products and services</li>
                            <li>Support for local and socially responsible suppliers</li>
                            <li>Life-cycle cost analysis in procurement decisions</li>
                            <li>Regular supplier audits and assessments</li>
                            <li>Training for procurement staff on sustainable practices</li>
                        </ul>
                    </div>
                </div>
                <div class="policy-item">
                    <h4>Student Safety Policy</h4>
                    <div class="policy-content">
                        <p><strong>Mission:</strong> Comprehensive safety protocols and procedures to ensure the well-being, security, and protection of all students within the university community.</p>
                        <p><strong>Key Areas:</strong></p>
                        <ul>
                            <li>Campus security and emergency response procedures</li>
                            <li>Student health and wellness programs</li>
                            <li>Mental health support and counseling services</li>
                            <li>Prevention of harassment and discrimination</li>
                            <li>Safety training and awareness programs</li>
                            <li>Incident reporting and investigation procedures</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="policies-section" id="U">
            <div class="section-header">
                <div class="letter-indicator">U</div>
                <h3>UPHSL University Policies</h3>
            </div>
            <div class="policies-grid">
                <div class="policy-item">
                    <h4>UPHSL Policy Against Hazing and All Forms of Violence</h4>
                    <div class="policy-content">
                        <p><strong>Commitment:</strong> Zero-tolerance policy against hazing, bullying, and all forms of violence, ensuring a safe and respectful environment for all community members.</p>
                        <p><strong>Prohibited Activities:</strong></p>
                        <ul>
                            <li>Any form of hazing or initiation rituals</li>
                            <li>Physical, verbal, or psychological violence</li>
                            <li>Bullying, intimidation, or harassment</li>
                            <li>Discrimination based on any protected characteristics</li>
                            <li>Retaliation against those who report violations</li>
                        </ul>
                        <p><strong>Consequences:</strong> Violations of this policy will result in immediate disciplinary action, up to and including expulsion for students and termination for employees.</p>
                    </div>
                </div>
                <div class="policy-item">
                    <h4>UPHSL Admission Policy</h4>
                    <div class="policy-content">
                        <p><strong>Objective:</strong> Comprehensive guidelines and requirements for student admission to all university programs, ensuring fair and transparent admission processes.</p>
                        <p><strong>Key Principles:</strong></p>
                        <ul>
                            <li>Merit-based admission decisions</li>
                            <li>Equal opportunity for all qualified applicants</li>
                            <li>Transparent admission criteria and processes</li>
                            <li>Comprehensive evaluation of academic and non-academic factors</li>
                            <li>Appeal process for admission decisions</li>
                            <li>Regular review and updating of admission requirements</li>
                        </ul>
                        <p><strong>Requirements:</strong> Specific admission requirements vary by program but generally include academic transcripts, entrance examinations, interviews, and other program-specific criteria.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h3>Need Help Finding a Policy?</h3>
            <p>If you cannot find the policy you're looking for or need clarification on any regulation, please contact the Office of the Registrar or the appropriate administrative department for assistance.</p>
        </div>
    </div>
</section>

<script>
// Smooth scrolling for alphabet navigation
document.querySelectorAll('.alphabet-letter').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Highlight active letter based on scroll position
window.addEventListener('scroll', function() {
    const sections = document.querySelectorAll('.policies-section');
    const navLinks = document.querySelectorAll('.alphabet-letter');
    
    let current = '';
    sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (window.pageYOffset >= sectionTop - 200) {
            current = section.getAttribute('id');
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
            link.classList.add('active');
        }
    });
});

// Add hover effects for policy items
document.querySelectorAll('.policy-item').forEach(item => {
    item.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-2px)';
    });
    
    item.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>

<?php
// Include footer
include '../app/includes/footer.php';
?>
