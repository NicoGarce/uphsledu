<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta
      name="author"
      content="Clark Leovison C. Rey, Jerome Evangelista, Rojeth Justine Javaluyas"
    />
    <meta
      name="description"
      content="University of Perpetual Help System - Laguna, UPHSL, Perpetual Laguna"
    />
    <title>Archive News | University of Perpetual Help System - Laguna</title>
    <link rel="icon" type="x-icon" href="/img/SSS.png" />
    <link rel="stylesheet" href="/styles/main.css" />
    <link rel="stylesheet" href="/styles/archives.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Barlow+Semi+Condensed:wght@600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
      integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>

  <header>
    <!--Start of Navigation Bar-->
    <nav class="navbar">
      <!--Start of Navigation Logo-->
      <div class="navlogo">
        <a href="/index.html">
          <img
            src="/img/Logos/white.png"
            alt="Perpetual Logo"
            class="perpslogo"
        /></a>
      </div>
      <!--End of Navigation Logo-->
      <div class="hitbox"></div>

      <ul class="navlist" tabindex="0">
        <li class="nav-item"><a href="/index.html" class="nav-link">Home</a></li>

        <!--Start of Program SubMenu-->
        <li class="nav-item">
          <input type="checkbox" id="programs-checkbox" />
          <label for="programs-checkbox">Programs</label>
          <i class="fa-solid fa-caret-down arrow prog-arrow"></i>
          <!--Start of Basic Education-->
          <div class="progsub">
            <ul>
              <li class="nav-link noHide">
                <a href="#"
                  >Basic Education
                  <i class="fa-solid fa-caret-down arrow bsed-arrow"></i
                ></a>
                <div class="progsub-bsed">
                  <ul>
                    <li class="bsced">
                      <a href="https://uphsl.edu.ph/shs/index.htm"
                        >Senior High School</a
                      >
                    </li>
                    <li class="bsced">
                      <a href="https://uphsl.edu.ph/jhs/index.htm"
                        >Junior High School</a
                      >
                    </li>
                    <li class="bsced">
                      <a href="https://uphsl.edu.ph/gradeschool/index.htm"
                        >Grade School</a
                      >
                    </li>
                  </ul>
                </div>
              </li>

              <li>
                <a href="/programs/aviation/aviation.html" class="nav-link"
                  >Aviation</a
                >
              </li>
              <li>
                <a href="/programs/cas/cas.html" class="nav-link"
                  >Arts &amp; Sciences</a
                >
              </li>
              <li>
                <a
                  href="/programs/business-accountancy/ba.html"
                  class="nav-link"
                  >Business&nbsp;&amp;&nbsp;Accountancy</a
                >
              </li>
              <li>
                <a href="/programs/ccs/ccs.html" class="nav-link"
                  >Computer Studies</a
                >
              </li>
              <li>
                <a href="/programs/criminology/crim.html" class="nav-link"
                  >Criminology</a
                >
              </li>
              <li>
                <a href="/programs/education/education.html" class="nav-link"
                  >Education</a
                >
              </li>
              <li>
                <a
                  href="/programs/engineering/engineering.html"
                  class="nav-link"
                  >Engineering &amp; Architecture</a
                >
              </li>
              <li>
                <a href="/programs/hospitality/cihm.html" class="nav-link"
                  >Int'l Hospitality Management</a
                >
              </li>
              <li>
                <a href="https://uphsl.edu.ph/cmt/" class="nav-link"
                  >Maritime</a
                >
              </li>
              <li>
                <a href="https://uphsl.edu.ph/law/" class="nav-link"
                  >Law/Juris Doctor</a
                >
              </li>
              <li>
                <a href="https://uphsl.edu.ph/gs/" class="nav-link"
                  >Graduate School</a
                >
              </li>
              <!--End of Master/College Programs-->
            </ul>
          </div>
        </li>
        <!--End of Program SubMenu-->

        <!--Start of Online Services SubMenu-->
        <li class="nav-item">
          <input type="checkbox" id="onlineServices-checkbox" />
          <label for="onlineServices-checkbox">Online Services</label>
          <i class="fa-solid fa-caret-down arrow ols-arrow"></i>
          <div class="olssub">
            <ul>
              <li>
                <a href="/olservices/gti.html" class="nav-link"
                  >GTI&nbsp;Online Grades</a
                >
              </li>
              <li>
                <a href="/olservices/gti.html" class="nav-link">Moodle</a>
              </li>
              <li>
                <a href="/olservices/gti.html" class="nav-link"
                  >Google Account</a
                >
              </li>
              <li>
                <a href="/olservices/payment.html" class="nav-link"
                  >Online Payment</a
                >
              </li>
              <li>
                <a href="https://www.saliksikuphsl.org/" class="nav-link"
                  ><span style="text-align: center">Saliksik</span>
                  (Research)</a
                >
              </li>
            </ul>
          </div>
        </li>
        <!--End of Online Services SubMenu-->

        <!--Start of Support Services SubMenu-->
        <li class="nav-item">
          <input type="checkbox" id="supportServices-checkbox" />
          <label for="supportServices-checkbox">Support Services</label>
          <i class="fa-solid fa-caret-down arrow supser-arrow"></i>
          <div class="supsersub">
            <ul>
              <li>
                <a
                  href="https://docs.google.com/forms/d/e/1FAIpQLSea8-O2OuuKWgZ17XgKkyLQ7dDOawW31a8vq1nTWDRREODVMQ/viewform"
                  class="nav-link"
                  >Alumni</a
                >
              </li>
              <li>
                <a
                  href="/support-services/careers/careers.html"
                  class="nav-link"
                  >Careers</a
                >
              </li>
              <li>
                <a href="#" class="nav-link">Clinic</a>
              </li>
              <li>
                <a href="/support-services/cod/cod.html" class="nav-link"
                  >Community Outreach Department</a
                >
              </li>
              <li>
                <a
                  href="/support-services/internal-external/internalexternal.html"
                  class="nav-link"
                  >International &amp; External Affairs</a
                >
              </li>

              <li>
                <a href="/support-services/sps/sps.html" class="nav-link"
                  >Guidance and Admission</a
                >
              </li>
              <li>
                <a
                  href="/support-services/college-library/college-library.html"
                  class="nav-link"
                  >Library</a
                >
              </li>
              <li><a href="#" class="nav-link">Quality Assurance</a></li>
              <li>
                <a
                  href="/support-services/research/research.html"
                  class="nav-link"
                  >Research</a
                >
              </li>
            </ul>
          </div>
        </li>
        <!--End of Support Services SubMenu-->

        <li class="nav-item">
          <a href="#Campuses" class="nav-link">Campuses</a>
        </li>

        <!--Start of About SubMenu-->
        <li class="nav-item">
          <input type="checkbox" id="about-checkbox" />
          <label for="about-checkbox">About</label>
          <i class="fa-solid fa-caret-down arrow abt-arrow"></i>
          <div class="abtsub">
            <ul>
              <li>
                <a href="/about/about.html" class="nav-link">About Us</a>
              </li>
              <li>
                <a href="/about/contact-us.html" class="nav-link"
                  >Contact Us</a
                >
              </li>
              <li>
                <a
                  href="/about/environmental-policy/environmental-policy.html"
                  class="nav-link"
                  >Environmental Policy</a
                >
              </li>
              <li>
                <a href="/about/misvision.html" class="nav-link"
                  >Mission &amp; Vission</a
                >
              </li>
              <li><a href="/about/policy-pages/PolicyHome.html" class="nav-link">University Policy</a></li>

              <li><a href="/about/map.html" class="nav-link">Map</a></li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <input type="checkbox" id="calendar-checkbox" />
          <label for="calendar-checkbox">Calendar</label>
          <i class="fa-solid fa-caret-down arrow supser-arrow"></i>
          <div class="supsersub">
            <ul>
              <li>
                <a
                  href="/academic-calendar/COLLEGE ACADEMIC CALENDAR 2024-2025.pdf"
                  target="_blank"
                  >College Academic Calendar</a
                >
              </li>
              <li>
                <a
                  href="/academic-calendar/BED & SHS ACADEMIC CALENDAR 2024-2025.pdf"
                  target="_blank"
                  >BED&nbsp;&amp;&nbsp;SHS&nbsp;Academic Calendar</a
                >
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <input type="checkbox" id="enrollment-checkbox" />
          <label for="enrollment-checkbox">Enrollment</label>
          <i class="fa-solid fa-caret-down arrow supser-arrow"></i>
          <div class="supsersub">
            <ul>
              <li>
                <a
                  href="https://docs.google.com/forms/d/e/1FAIpQLSfuxQtL77zIZ13Zqzk951FiIrSpGApccIFyp_Gr6faD1vtVng/viewform"
                  target="_blank"
                  >Enrollment for College, Graduate School &amp; Juris Doctor</a
                >
              </li>
              <li>
                <a
                  href="https://docs.google.com/forms/d/e/1FAIpQLSfh2CKtB6Nmz0CeDvWKaTETuNCbaFiZiuo2UdQ0u5t4zJtgvQ/viewform"
                  target="_blank"
                  >Enrollment for Senior High School</a
                >
              </li>
            </ul>
          </div>
        </li>
        <!--End of About SubMenu-->
        <li class="nav-item">
          <a
            href="/img/Sustainable Goals/a9f3eec3-a0cc-41ed-95b5-48b9d1731e76.jpg"
            class="enrlnw"
            target="_blank"
            ><span class="navenrl">SDG Initiatives
            </span>
          </a>
        </li>
        
      </ul>

      <!--Start of Hamburger Menu-->
      <div class="navham">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </div>
      <!--End of Hamburger Menu-->
      <span class="yellow"></span>
    </nav>
  </header>

  <body>
    <main>
      <div style="width: 100%; height: 110px"></div>

 <?php 
 if ($_SESSION["adminin"]==1) {
 ?>
      <section class="Yearly-News">
        <div class="containers">
          <div style="width: 100%; height: 20px"></div>
          <div class="yearlynews-title">
            <h1 class="yearly-title">News for the Year 2025</h1>
          </div>

          <div class="newscard-grid">
            <!--Start of Card Images-->


		   <div class="newup-card">          
              <div class="newup-readm">
			   <ul>
			    <li class="newup-readm-btn"><a href="/News-Updates/pdf/2025/hrd/Sustainable Donations and Funding Policy .pdf">Sustainable Donations and Funding Policy</a></li>
				<li class="newup-readm-btn"><a href="/News-Updates/pdf/2025/hrd/Purchasing Policy.pdf">Purchasing Policy</a></li>
				<li class="newup-readm-btn"><a href="/News-Updates/pdf/2025/hrd/Policy Modern Slavery Evidence.pdf">Policy Modern Slavery Evidence</a></li>
				<li class="newup-readm-btn"><a href="/News-Updates/pdf/2025/hrd/Health and Safety Manual.pdf">Health and Safety Manual</a></li>
				<li class="newup-readm-btn"><a href="/News-Updates/pdf/2025/hrd/Diversity and Inclusion.pdf" >Diversity and Inclusion</a></li>
				<li class="newup-readm-btn"><a href="/News-Updates/pdf/2025/hrd/Anti Bribery and Corruption Policy.pdf">Anti Bribery and Corruption Policy</a></li>
				<li class="newup-readm-btn"><a href="/News-Updates/pdf/2025/hrd/Academic Freedom Policy.pdf">Academic Freedom Policy</a></li>
			   </ul>                
              </div>
            </div>
			
			 <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2025/mancommeeting2025.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Management Committee Meeting</h3>
              <p class="newup-contents">
                &nbsp;
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2025/MANAGEMENT COMMITTEE MEETING.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
			
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2025/Untitled.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Employment Policy Pay Scale Equity</h3>
              <p class="newup-contents">
                This policy aims to promote a fair and equitable compensation
                practices within the academic institution. This policy further
                aims to ensure that all faculty, non-teaching staff and support
                personnel are compensated fairly based on their qualifications,
                experiences and contributions to the institution
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2025/Employment Policy Pay Scale Equity.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2025/2.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Employment Policy Modern Slavery</h3>
              <p class="newup-contents">
                This policy aims to promote a fair and equitable compensation
                practices within the academic insitution. This policy further
                aims to ensure that all faculty, non teaching staff and support
                personnel are compensated fairly based on their qualifications,
                experiences, and contributions to the institution
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2025/Employment Policy Modern Slavery.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
        </div>
      </section>
<?php } ?>

      <section class="Yearly-News">
        <div class="containers">
          <div style="width: 100%; height: 20px"></div>
          <div class="yearlynews-title">
            <h1 class="yearly-title">News for the Year 2024</h1>
          </div>
            
            
            
                    <!--Start of Card Images-->

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2025/Physco.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Congratulations to our new Psychometricians!</h3>
              <p class="newup-contents">
                We’re proud to announce that UPHSL achieved a 100% passing rate in the JUNE 2025 Psychometricians Special Professional Licensure Exam. The exam was held last June 6–7, 2025, in international testing centers across UAE, KSA, Qatar, Bahrain, Kuwait, Singapore, and Taiwan.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2025/Psychometricians.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
            
            
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2025/Untitled.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Employment Policy Pay Scale Equity</h3>
              <p class="newup-contents">
                This policy aims to promote a fair and equitable compensation
                practices within the academic institution. This policy further
                aims to ensure that all faculty, non-teaching staff and support
                personnel are compensated fairly based on their qualifications,
                experiences and contributions to the institution
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2025/Employment Policy Pay Scale Equity.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
            
            

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2025/2.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Employment Policy Modern Slavery</h3>
              <p class="newup-contents">
                This policy aims to promote a fair and equitable compensation
                practices within the academic insitution. This policy further
                aims to ensure that all faculty, non teaching staff and support
                personnel are compensated fairly based on their qualifications,
                experiences, and contributions to the institution
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2025/Employment Policy Modern Slavery.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="Yearly-News">
        <div class="containers">
          <div style="width: 100%; height: 20px"></div>
          <div class="yearlynews-title">
            <h1 class="yearly-title">News for the Year 2024</h1>
          </div>
          <!-- Start of December 2024 -->
          <div class="newscard-title">
            <h2>December 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/December/2.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">BINHI NG KAALAMAN</h3>
              <p class="newup-contents">
                In a vibrant celebration of learning and compassion, the
                Community Outreach Department of the University of Perpetual
                Help System Laguna, together with the Perpetualite Nation
                Builders (PNB), launched Binhi ng Kaalaman: A Book Donation
                Drive 2024—a heartfelt initiative rooted in the belief that
                knowledge, when shared, transforms lives.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/December/13. Binhi A Book Dontion Drive.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/December/1.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Lend A Hand</h3>
              <p class="newup-contents">
                Every Christmas tells a story—of hope, of love, and of giving.
                And at the University of Perpetual Help System Laguna, that
                story continues to be written through the hearts and hands of
                its volunteers. On December 20, 2024, the Community Outreach
                Department (COD), in collaboration with the Perpetualite Nation
                Builders (PNB), proudly hosted the annual Lend A Hand
                program—one of the longest-standing outreach efforts of the
                university. This year’s theme, “Embracing the True Spirit of
                Christmas,” captured the essence of what the season is all
                about: selfless service, meaningful connection, and unwavering
                generosity
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/December/13. Lend A Hand 2024.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of November 2024 -->

          <!-- Start of November 2024 -->
          <div class="newscard-title">
            <h2>November 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/November/2.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Negosyo Tayo</h3>
              <p class="newup-contents">
                The activity started with Mr. Dante Salvadora, a Hospitality
                Management student, making the filling for Chicken Empanada. He
                first sautéd the chicken breast with garlic and onion, then
                added oyster sauce, soy sauce, and chicken cubes.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/November/10. Perpetualite Training Program Negosyo tayo.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/November/1.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Reach and Read</h3>
              <p class="newup-contents">
                As part of the National Book Week celebration, the University of
                Perpetual Help System Laguna once again proved its unwavering
                commitment to service through the community annual outreach
                program, "Reach and Read: Spreading Literacy,
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/November/11. Reach and Read.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of November 2024 -->

          <!-- Start of October 2024 -->
          <div class="newscard-title">
            <h2>October 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/October/1.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Hands Hope</h3>
              <p class="newup-contents">
                In the face of nature’s fiercest trials, it is compassion that
                lights the way forward
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/October/9. Perpetualite Hands of Hope.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          
          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-3">
                <img
                  src="/img/mancom/MANAGEMENT COMMITTEE MEETING_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Management Committee Meeting</h3>
              <p class="newup-contents">
                Management Committee Meeting
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2024/mancom.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of October 2024 -->

          <!-- Start of September 2024 -->
          <div class="newscard-title">
            <h2>September 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/September/1.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Iluminacion: Pagkamulat sa Paglilingkod
              </h3>
              <p class="newup-contents">
                This activity was spearheaded by the Servers of God Ministry,
                managed by Mr. Mark Braian B. Tandoc, with Leandro Valeriano as
                his co manager. This event was sectioned into parts, depending
                on the speaker.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/September/Iluminacion 3.0.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/September/2.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Coastal: Clean Up</h3>
              <p class="newup-contents">
                This activity was spearheaded by the Servers of God Ministry,
                managed by Mr. Mark Braian B. Tandoc, with Leandro Valeriano as
                his co manager. This event was sectioned into parts, depending
                on the speaker.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/September/International Coastal Cleanup.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of September 2024 -->

          <!-- Start of August 2024 -->
          <div class="newscard-title">
            <h2>August 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/August/1.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Small Packs | Big Futures: Back to School
              </h3>
              <p class="newup-contents">
                In the quiet corners of a public school classroom, where dreams
                are often whispered rather than shouted, the simple gift of a
                backpack filled with supplies can be life changing.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/August/8. Small Packs, Big Futures.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of August 2024 -->

          <!-- Start of June 2024 -->
          <div class="newscard-title">
            <h2>June 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/June/5.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">OPERATION MANHOOD</h3>
              <p class="newup-contents">
                The tradition and somehow poverty among Filipino families, many
                lads are at risk of botched circumcisions performed by
                unqualified operators. Circumcision or “Tuli” is considered a
                rite of passage into manhood
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/June/5. Project Manhood Free Circumcision 2024.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/June/6.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">JCI Manila International</h3>
              <p class="newup-contents">
                The Community Outreach Department of the University of Perpetual
                Help System Laguna (UPHSL) - Biñan Campus proudly played a
                central role in the university's participation in the nationwide
                bloodletting event, "Juan for All: National Blood Letting
                Activity."
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/June/6. JCI Manila Bloodletting Project.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/June/7.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">ARBOR ALLIES</h3>
              <p class="newup-contents">
                The event commenced with an opening ceremony where key officials
                from the City Government of Santa Rosa and Nuvali addressed the
                participants. They emphasized the importance of collective
                efforts in environmental conservation and the specific goals of
                the day's activities.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/June/7. Arbor Allies.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of June 2024 -->

          <!-- Start of May 2024 -->
          <div class="newscard-title">
            <h2>May 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/May/4.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Electrical Safety and Solar Street Light Installation
              </h3>
              <p class="newup-contents">
                We had the honor of serving as the program chair for our schools
                annual Community Outreach Program (COP) held at the Municipality
                of Siniloan, Laguna. Community outreach programs are a vital
                aspect of our school's mission to provide a holistic education
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/May/4. Electrical Safety and Solar Street Light Installation.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of May 2024 -->

          <!-- Start of March 2024 -->
          <div class="newscard-title">
            <h2>March 2024</h2>
          </div>

          <div class="newscard-grid">
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/March/1.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Catch-Up Friday</h3>
              <p class="newup-contents">
                There is immense power when a group of people with similar
                interests gets together to work toward the same goal.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/March/1. Catch-Up Fridays!.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/March/2.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Basic Safety of Computer Trouble Shooting
              </h3>
              <p class="newup-contents">
                MicroSociety, the Computer Engineering Society, held its first
                community outreach program, “Basic Safety of Computer
                Troubleshooting: Hands-on Workshop,”
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/March/2. Basic Safety of Computer Troubleshooting.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
            
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/March/3.PNG"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">ELECTRICAL SAFETY AND FIRE PREVENTION</h3>
              <p class="newup-contents">
                The Community Outreach Program on Electrical Safety and Fire
                Prevention held at Dr. Jose G. Tamayo Memorial Elementary School
                on March 16, 2024, was a resounding success. Under the guidance
                of Ms. Clarisse Anne Lebios and Dean Dr. Flocerfida L. Amaya,
                and with the invaluable support of Engr. Rodolfo V. Mendoza
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2024/March/3. Electrical Safety and Fire Prevention Seminar.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
          <!-- End of March 2024 -->

          <div class="newscard-grid">
            <!--Start of Card Images-->

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/University Week.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">University Week 2024</h3>
              <p class="newup-contents">
                All the Scores of the Game in University Week 2024
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/autonomous.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2024/12.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Applied HE News Paper</h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has
                achieved top rankings in AppliedHE's ASEAN University Ranking.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/Applied HE News paper.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Collaborative Hands.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL Extends Collaborative Hands to Partner Schools
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has
                extended a remarkable offer of collaboration, signing
                Memorandums of Understanding (MOUs) with three distinguished
                educational institutions
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/UPHSL Extends Collaborative Hands to Partner Schools.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="Yearly-News">
        <div class="containers">
          <div style="width: 100%; height: 20px"></div>
          <div class="yearlynews-title">
            <h1 class="yearly-title">News for the Year 2023</h1>
          </div>

          <div class="newscard-grid">
            <!--Start of Card Images-->

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/A Dazzling Triumph at the CHED ASEAN Kick-Off Celebration.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                University of Perpetual Help System Laguna: A Dazzling Triumph
                at the CHED ASEAN Kick-Off Celebration
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) achieved
                an extraordinary feat in the recent CHED ASEAN Kick Off
                Celebration hosted at LPU Batangas.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/dazzling-triumph.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Applied HE News paper.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Applied HE News Paper</h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has
                achieved top rankings in AppliedHE's ASEAN University Ranking.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/applied-he.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Collaborative Hands.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL Extends Collaborative Hands to Partner Schools
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has once
                again solidified its position as a frontrunner in the field of
                education, achieving a remarkable milestone by being awarded 3
                QS Stars by the renowned QS Intelligent Unit.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/collaborative-partner.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Embarks.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                The University of Perpetual Help System Laguna (UPHSL) Embarks
                on Internationalization Efforts at the 5th NUTRICON 2023 in
                Kuala Lumpur
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has taken
                a significant stride towards internationalization as its
                official delegation touched down in Kuala Lumpur
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/embarks.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Pacucoa.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                University of Perpetual Help System Laguna (UPHSL) has achieved
                top rankings in AppliedHE's ASEAN University Ranking
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has
                extended a remarkable offer of collaboration, signing
                Memorandums of Understanding (MOUs) with three distinguished
                educational institutions
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/pacucoa.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Shengwei.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Shengwei Education Consulting Services Corporation has been
                authorized by UPHSL
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has
                extended a remarkable offer of collaboration, signing
                Memorandums of Understanding (MOUs) with three distinguished
                educational institutions
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/shengwei.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/University of Perpetual Help System Laguna Earns Prestigious 3 QS Stars from Quacquarelli Symonds Intelligent Unit.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                University of Perpetual Help System Laguna Earns Prestigious 3
                QS Stars from Quacquarelli Symonds Intelligent Unit
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has once
                again solidified its position as a frontrunner in the field of
                education, achieving a remarkable milestone by being awarded 3
                QS Stars by the renowned QS Intelligent Unit
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/qs-stars.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/UPHSL as Institution of Higher Learning and the UN Sustainable Development Goals.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL as Institution of Higher Learning and the UN Sustainable
                Development Goals
              </h3>
              <p class="newup-contents">
                United Nations adopted the Sustainable Development Goals (SDGs),
                a set of 17 interconnected objectives aimed at promoting
                economic growth, social inclusion, and environmental protection
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/higher-learning.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
            <!-- Start of Community Outreach Program 2023 -->
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Outreach/February 24, March 15 & 17, 2023/February 24, March 15 & 17, 2023_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Iluminacion: Pagkamulat sa Paglilingkod
              </h3>
              <p class="newup-contents">
                The Spiritual Formation Entitled "ILUMINACION: Pagkamulat Sa
                Paglilingkod" is a three-day seminar for Senior High School
                Student volunteers held at the Senior High School building.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/outreach/outreach.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Outreach/April 18, 2023/April 18, 2023_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Humss & Gas: Community Unleashing The Power of the Society
              </h3>
              <p class="newup-contents">
                The Community Outreach Department of the University of Perpetual
                Help System - Laguna once again serve its purpose by providing
                another community project which focused on strengthening the
                Psycho-Social Aspect of the adopted community through a seminar
                and gift-giving activity.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/outreach/outreach2.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Outreach/May 11, 2023 CME/May 11, 2023 CME_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                MariKnows (Green Land Blue Sea Campaign 2023)
              </h3>
              <p class="newup-contents">
                The College of Maritime Education (CME) in partnership with the
                Community Outreach Department (COD) of University of Perpetual
                Help System - Laguna (UPHSL) once again conducted a community
                project entitled: "MariKNOWS: Green Land, Blue Sea Campaign
                2023"
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/outreach/outreach3.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Outreach/May 11, 2023 CIHM/May 11, 2023 CIHM_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Health is Wealth (A CIHM Community Series)
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) through
                its Community Outreach Department (COD) in collaboration with
                the College of International and Hospitality Management (CIHM)
                as the major benefactor, successfully implemented an outreach
                project entitled : "Health is Wealth: A CIHM Community Outreach
                Series"
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/outreach/outreach4.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Outreach/September 16, 2023/September 16, 2023_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Coastal Cleanup</h3>
              <p class="newup-contents">
                The College of Maritime Education is dedicated to conserving and
                sustaining the use of seas and oceans and protecting life below
                water
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/outreach/outreach5.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Outreach/October 20-27, 2023/October 20-27, 2023_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Binhi ng Kaalaman</h3>

              <p class="newup-contents">
                In celebration of the 78th birthday of our esteemed CEO and
                President, of the University of Perpetual Help System Laguna
                (UPHSL), under the initiative of Dr. Ferdinand C. Somido and in
                coordination with the Community Outreach Department, organized a
                noteworthy community project.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/outreach/outreach6.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Outreach/December 20, 2023/December 20, 2023_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Lend A Hand 2023</h3>

              <p class="newup-contents">
                The famous quotation, “It is better to give than to receive,”
                epitomizes the spirit of the University of Perpetual Help System
                Laguna (UPHSL) during the Christmas season. This ethos was
                embodied in the "Lend a Hand" activity, a cherished tradition
                where our university reaches out to the adopted communities to
                share the joy and warmth of the holiday season.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/outreach/outreach7.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
            <!-- End of Community Outreach Program 2023 -->

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/AthelitcsBanner.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Special Olympics 2023</h3>

              <p class="newup-contents">
                The Commemoration of the 3rd Decade of Special Olympics, we will
                celebrate this activity by having different kinds mof acts such
                as performing arts, cooking, free play, arts and crafts, and an
                obstacle course!
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/athletics/athletics.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <!-- SPS -->
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/SPS/gender.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Safe Spaces Act Webinar</h3>

              <p class="newup-contents">
                All schools, whether public or privat, shall designate an
                officer-in-charge to receive complaints regarding violations of
                this Act, and shall,ensure that the victims are provided with a
                gender-sensitive environment that is both respectful to the
                victim's needs and conductive to truth-telling.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/sps/safespace.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/SPS/PFA Banner.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Psychological First Aid Webinar presented by Student Personnel
                Services in collaboration with Human Resource Department
              </h3>

              <p class="newup-contents">
                The University of Perpetual Help System - Laguna, conducted a
                psychological webinar on which we takle on how we can deal with
                someone who does have a psychological problem
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/sps/psychological.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/SPS/suh.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                "Suh": What's up with our Kids? How do we relate to them? A
                low-down on our Kid's Behavior
              </h3>

              <p class="newup-contents">
                In this webinar, we takle on howwe can relate to our kids
                regarding to their behavior
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/sps/kids-behavior.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/UPHSL ANNUAL REPORT University of Perpetual Help System Laguna A.Y. 2022-2023 ON SUSTAINABLE DEVELOPMENT GOALS_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL ANNUAL REPORT A.Y. 2022-2023 ON SUSTAINABLE DEVELOPMENT
                GOALS
              </h3>

              <p class="newup-contents">
                Welcome to our annual report on the Sustainable Development
                Goals (SDGs) for the Academic year 2022-2023. The United
                Nation’s SDGs serves as a blueprint of setting the
                interconnected objectives which aimed at promoting economic
                growth, social inclusion, and environmental protection.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pdf/2023/UPHSL ANNUAL REPORT University of Perpetual Help System Laguna A.Y. 2022-2023 ON SUSTAINABLE DEVELOPMENT GOALS.pdf"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/Untitled.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">Sustainable Literacy</h3>

              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) measures
                the sustainability literacy of the students through a variety of
                curricular and ck-curricular initiatives. UPHSL incorporates
                sustainability topics and the Sustainable Development Goals
                (SDGs) into courses across disciplines, emphasizing
                environmental stewardship, ethical resource management and
                social responsibility.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/sustainable/sustainable.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2023/FclNarative.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Kindergarten Supplies for Bright Beginnings: Empowering Our
                Young Learners
              </h3>

              <p class="newup-contents">
                A collaborative initiative unfolded as the College of
                Engineering and the College of Business and Accountancy united
                in a mission to make a lasting impact on the educational journey
                of students.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2023/fclnarative.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="Yearly-News">
        <div class="containers">
          <div style="width: 100%; height: 20px"></div>
          <div class="yearlynews-title">
            <h1 class="yearly-title">News for the Year 2022</h1>
          </div>

          <div class="newscard-grid">
            <!--Start of Card Images-->

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/VirtualPinning.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL College of Education Celebrates First Virtual Pinning
                Ceremony
              </h3>
              <p class="newup-contents">
                The event gathered the Dean of the College of Education Dr.
                Elena A. Salinas, heads of the different departments, faculty
                members, parents and interns.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/virtual-pinning.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Raising Student Motivation to Keep the Mind in Motion.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Focus on U: Raising Student Motivation to Keep the Mind in
                Motion
              </h3>
              <p class="newup-contents">
                The new mode of learning through virtual classes is a big
                transition in education where learners are expected to stay at
                home and connect with their teachers and classmates through the
                use of the internet
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/raising-student.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Plaque.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL Extends Collaborative Hands to Partner Schools
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) has once
                again solidified its position as a frontrunner in the field of
                education, achieving a remarkable milestone by being awarded 3
                QS Stars by the renowned QS Intelligent Unit.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/plaque.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/PWD Banner.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">A PWD Friendly University</h3>
              <p class="newup-contents">
                the University of Perpetual Help System Laguna JONELTA - (UPHSL)
                strengthened its participation by providing areas wherein the
                main function is to provide facilities that are fully accessible
                to Persons with Disabilities (PWDs)
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/pwd.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Memorandum of Understanding Silk.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Memorandum of Understanding (MOU) Signing and Unveiling
                Ceremonies with China-Philippines Silk Road Institute Foundation
              </h3>
              <p class="newup-contents">
                This cooperation aims to establish a framework for the fair and
                mutually beneficial cooperation among the parties to innovate
                the talent cultivation mode by deepening integration between
                industry and education.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/mou-signing.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <!-- Start of Community Outreach Program  -->
            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Community Outreach Department/September 26, 2022/September 26, 2022.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Nutrition Seminar About The Importance of Breastfeeding
              </h3>
              <p class="newup-contents">
                The BS in Nutrition and Dietetics Students visits the Community
                and stayed at Barangay Palma, Alaminos, Laguna starting from
                September 12 to do community outreach program
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/outreach/outreach.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Community Outreach Department/September 28, 2022/September 28, 2022_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Kay Lolo at Lola Project: Grandparents day Celebration
                Pagpapahalaga sa Kalusugan ng Taga Palma
              </h3>
              <p class="newup-contents">
                The volunteers went to each elders houses to inform them that
                they are going to conduct an activity for the following week.
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/outreach/outreach2.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Community Outreach Department/October 24, 2022/October 24, 2022_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Pangmalakasang Perpetualista (A Community Pantry)
              </h3>
              <p class="newup-contents">
                The Purpose of this Community Pantry is to share something with
                others. While there are mixed reactions about community
                pantries, it is good to focus on the advantages of this
                movements
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/outreach/outreach3.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Community Outreach Department/November 14, 2022/November 14, 2022_page-0001.jpg"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Perpetualite Unity (In Helping Our Community Project 2022)
              </h3>
              <p class="newup-contents">
                The Basic Education Department (Senior High School, Junior High
                School and Elementary) in partnership with The Frateral Order of
                Eagles initiated a Community Outreach Program with a theme:
                "Perpetualite Unity in Helping our Community Project 2022".
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/outreach/outreach4.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
            <!-- End of Community Outreach Program  -->

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Livelihood/1.jfif"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL Empowers Local Community Through Livelihood Training
                Seminar
              </h3>
              <p class="newup-contents">
                The University of Perpetual Help System Laguna (UPHSL) hosted a
                Livelihood Training Seminar on December 7, 2022, at its Basic
                Education Department. Organized by the College of International
                Hospitality Management in partnership with the Community
                Outreach Department and the International and External Affairs
                Office
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/livelihood/livelihood.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Scholarship 2022.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                UPHSL offers scholarships and special discounts for the upcoming
                Academic Year 2022-2023
              </h3>
              <p class="newup-contents">
                To take advantage of these opportunities, students must be
                officially enrolled and complete the form from the Student
                Personnel Services office
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/scholarship/scholarship.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>

            <div class="newup-card">
              <div class="newup-image-2">
                <img
                  src="/News-Updates/img/2022/Inter-Barangay.png"
                  alt="Annoucement"
                  class="newupimg2"
                />
              </div>

              <h3 class="newuptitle">
                Mayor Arman Dimaguila Jr. Inter-Barangay Basketball and
                Voleyball Tournament 2022
              </h3>

              <p class="newup-contents">
                The Commemoration of the 3rd Decade of Special Olympics, we will
                celebrate this activity by having different kinds mof acts such
                as performing arts, cooking, free play, arts and crafts, and an
                obstacle course!
              </p>
              <div class="newup-readm">
                <a
                  href="/News-Updates/pages/Archives/2022/athletics/athletics.html"
                  class="newup-readm-btn"
                  >Read More</a
                >
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- footer -->
    <footer>
      <div class="footer-top">
        <ul class="footer-top-ul">
          <li><a href="#">About UPHSL JONELTA</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">FAQ's</a></li>
          <li><a href="#">News</a></li>

          <div class="footer-icons">
            <li class="icons">
              <a href="https://www.facebook.com/uphsl.info.ph" target="_blank"
                ><i
                  class="fa-brands fa-facebook fa-lg"
                  style="color: #d3c5c5"
                ></i
              ></a>
            </li>
            <li class="icons">
              <a href="https://www.youtube.com/@uphsltv1397" target="_blank"
                ><i
                  class="fa-brands fa-youtube fa-lg"
                  style="color: #d3c5c5"
                ></i
              ></a>
            </li>
          </div>
        </ul>
      </div>

      <div class="footer-bottom">
        <img class="footer-logo" src="/img/LG6.png" alt="" />

        <div class="foot-copyright">
          <p class="footer-copyright">
            Copyright © 2023 University of Perpetual Help JONELTA - All Rights
            Reserved
          </p>
        </div>
      </div>
    </footer>
    <!-- end of footer -->
    <script src="/script/navbar.js"></script>
    <script src="/script/scrolltop.js"></script>
  </body>
</html>
