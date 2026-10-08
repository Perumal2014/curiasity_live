@extends(theme('layouts.dashboard_master'))

@section('title')
{{ Settings('site_title') ? Settings('site_title') : 'Infix LMS' }} |
{{ __('Institute Profile') }}
@endsection

@section('css')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

.institute-profile-page{

    --primary:#2563eb;
    --primary-dark:#1d4ed8;
    --secondary:#64748b;
    --success:#16a34a;
    --warning:#f59e0b;
    --danger:#ef4444;

    --bg:#f5f7fb;
    --card:#ffffff;
    --border:#e5e7eb;

    --text:#1e293b;
    --muted:#64748b;

    --radius:20px;

    --shadow-sm:0 4px 12px rgba(15,23,42,.05);
    --shadow-md:0 12px 30px rgba(15,23,42,.08);
    --shadow-lg:0 20px 60px rgba(15,23,42,.12);

    font-family:'Inter',sans-serif;
    background:var(--bg);
    color:var(--text);
    font-size:15px;
    line-height:1.7;
    padding-bottom:80px;
}

.institute-profile-page *,
.institute-profile-page *::before,
.institute-profile-page *::after{
    box-sizing:border-box;
}

.institute-profile-page a{
    text-decoration:none;
}

.institute-profile-page img{
    max-width:100%;
    display:block;
}

.institute-profile-page .container{
    max-width:1320px;
}

/* =======================================
Hero
=======================================*/

.institute-profile-page .hero{

    min-height:460px;

    background:

    linear-gradient(
    135deg,
    rgba(15,23,42,.88),
    rgba(37,99,235,.68)
    ),

    url("https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1800")
    center/cover no-repeat;

    color:#fff;

}

.institute-profile-page .hero .container{

    min-height:460px;

    display:flex;

    align-items:flex-end;

    padding-bottom:45px;

}

.institute-profile-page .logo{

    width:145px;
    height:145px;

    background:#fff;

    border-radius:24px;

    display:flex;

    align-items:center;

    justify-content:center;

    box-shadow:var(--shadow-lg);

}

.institute-profile-page .logo img{

    width:82px;

}

.institute-profile-page .hero h1{

    font-size:42px;

    font-weight:700;

    color:#fff;

    margin-bottom:10px;

}

.institute-profile-page .hero p{

    color:#dbeafe;

}

.institute-profile-page .hero span{

    color:#fff;

}

/* =======================================
Cards
=======================================*/

.institute-profile-page .cardx,
.institute-profile-page .info-card,
.institute-profile-page .profile-tabs{

    background:#fff;

    border-radius:var(--radius);

    box-shadow:var(--shadow-md);

    border:none;

}

.institute-profile-page .cardx{

    padding:22px;

    transition:.35s;

    height:100%;

}

.institute-profile-page .cardx:hover,
.institute-profile-page .info-card:hover{

    transform:translateY(-6px);

    box-shadow:var(--shadow-lg);

}

.institute-profile-page .summary{

    margin-top:-45px;

    position:relative;

    z-index:5;

}

.institute-profile-page .summary h3{

    font-size:32px;

    font-weight:700;

    color:var(--primary);

}

.institute-profile-page .profile-tabs{

    overflow:hidden;

}

.institute-profile-page .profile-tabs .nav{

    padding:0 20px;

    border-bottom:1px solid var(--border);

}

.institute-profile-page .profile-tabs .nav-link{

    border:none;

    padding:18px 24px;

    font-weight:600;

    color:var(--muted);

    background:transparent;

    position:relative;

}

.institute-profile-page .profile-tabs .nav-link:hover{

    color:var(--primary);

}

.institute-profile-page .profile-tabs .nav-link.active{

    color:var(--primary);

}

.institute-profile-page .profile-tabs .nav-link.active::after{

    content:"";

    position:absolute;

    left:18px;

    right:18px;

    bottom:0;

    height:4px;

    background:var(--primary);

    border-radius:20px;

}

.institute-profile-page .tab-content{

    padding:35px;

}

.institute-profile-page .info-card{

    padding:28px;

    margin-bottom:22px;

}

.institute-profile-page .info-card h3,
.institute-profile-page .info-card h4{

    font-weight:700;

    margin-bottom:18px;

}

/* =======================================
Badges
=======================================*/

.institute-profile-page .badge-soft{

    background:#eff6ff;

    color:var(--primary);

    padding:8px 16px;

    border-radius:30px;

    font-weight:600;

}

/* =======================================
Progress
=======================================*/

.institute-profile-page .progress{

    height:9px;

    background:#e2e8f0;

    border-radius:50px;

    overflow:hidden;

}

.institute-profile-page .progress-bar{

    background:linear-gradient(
    90deg,
    #2563eb,
    #60a5fa);

}

/* =======================================
Buttons
=======================================*/

.institute-profile-page .btn{

    border-radius:14px;

    padding:12px 18px;

    font-weight:600;

}

.institute-profile-page .btn-primary{

    background:var(--primary);

    border:none;

}

.institute-profile-page .btn-primary:hover{

    background:var(--primary-dark);

}

.institute-profile-page .btn-outline-primary{

    border-width:2px;

}

/* =======================================
Sidebar
=======================================*/

.institute-profile-page .sticky-side{

    position:sticky;

    top:30px;

}

.institute-profile-page .sticky-side .info-card{

    border-top:5px solid var(--primary);

}

/* =======================================
Typography
=======================================*/

.institute-profile-page h1,
.institute-profile-page h2,
.institute-profile-page h3,
.institute-profile-page h4,
.institute-profile-page h5{

    color:var(--text);

}

.institute-profile-page ul{

    padding-left:20px;

}

.institute-profile-page li{

    margin-bottom:8px;

    color:var(--secondary);

}

.logo-slider{
    width:100%;
    overflow:hidden;
    position:relative;
    padding:10px 0;
}

.logo-track{
    display:flex;
    align-items:center;
    gap:30px;
    width:max-content;
    animation:slideRight 25s linear infinite;
}

.logo-track img{
    width:120px;
    height:60px;
    object-fit:contain;
    background:#fff;
    border:1px solid #eee;
    border-radius:12px;
    padding:12px;
    flex-shrink:0;
    transition:.3s;
}

.logo-track img:hover{
    transform:scale(1.08);
}

@keyframes slideRight{
    0%{
        transform:translateX(-50%);
    }
    100%{
        transform:translateX(0);
    }
}

.logo-track.reverse{
    animation:slideLeft 25s linear infinite;
}

@keyframes slideLeft{
    0%{
        transform:translateX(0);
    }
    100%{
        transform:translateX(-50%);
    }
}

/* =======================================
Responsive
=======================================*/

@media(max-width:991px){

.institute-profile-page .hero{

min-height:560px;

}

.institute-profile-page .hero .container{

min-height:560px;

align-items:center;

padding:60px 15px;

}

.institute-profile-page .summary{

margin-top:20px;

}

.institute-profile-page .logo{

width:110px;
height:110px;

}

.institute-profile-page .hero h1{

font-size:30px;

}

}



@media(max-width:767px){

.institute-profile-page .profile-tabs .nav{

overflow:auto;
flex-wrap:nowrap;

}

.institute-profile-page .profile-tabs .nav-link{

white-space:nowrap;

padding:16px;

}

.institute-profile-page .cardx,
.institute-profile-page .info-card{

padding:20px;

}

}

#pageLoader{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:#fff;
    z-index:99999;
    display:flex;
    justify-content:center;
    align-items:center;
}

.loader-spinner{
    width:70px;
    height:70px;
    border:6px solid #e5e7eb;
    border-top:6px solid #2563eb;
    border-radius:50%;
    animation:spin .8s linear infinite;
}

@keyframes spin{
    100%{
        transform:rotate(360deg);
    }
}

</style>

@endsection

@section('mainContent')

<div class="institute-profile-page">
<div id="pageLoader">
    <div class="loader-spinner"></div>
</div>
    <!-- =========================
HERO SECTION
========================== -->

<section class="hero">

    <div class="container">

        <div class="row w-100 align-items-end">

            <div class="col-lg-8">

                <div class="d-flex align-items-center flex-wrap flex-md-nowrap">

                    <div class="logo">

                        <img src="{{ asset('images/provider-logo.png') }}"
                             onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">

                    </div>

                    <div class="ms-md-4 mt-3 mt-md-0">

                        <h1 id="institute_name" class="mb-2">
                            Global Training Institute
                        </h1>

                        <div class="mb-3">

                            <span id="verified_provider" class="badge bg-primary">
                                Verified Provider
                            </span>

                        </div>

                        <p id="tenant_slogan" class="mb-0">

                            Corporate training,
                            certifications,
                            compliance learning
                            and workforce upskilling.

                        </p>

                        <div class="d-flex flex-wrap gap-4 mt-3">

                            <span>
                                ⭐ 4.8 Rating
                            </span>

                            <span>
                                500+ Corporate Clients
                            </span>

                            <span id="tenant_experience">
                                12 Years Experience
                            </span>

                            <span id="address">
                                London, UK
                            </span>

                        </div>

                    </div>

                </div>

            </div>

           

        </div>

    </div>

</section>

<!-- =========================
SUMMARY
========================== -->

<section class="summary container">

    <div class="row g-3">

        

        <div class="col-6 col-md-4 col-lg">

            <div class="cardx text-center">

                <h3 id="course_count">450</h3>

                <small>Courses</small>

            </div>

        </div>

        <div class="col-6 col-md-4 col-lg">

            <div class="cardx text-center">

                <h3 id="trainer_count">85</h3>

                <small>Expert Trainers</small>

            </div>

        </div>

        <div class="col-6 col-md-4 col-lg">

            <div class="cardx text-center">

                <h3>25K+</h3>

                <small>Learners Trained</small>

            </div>

        </div>

        <div class="col-6 col-md-4 col-lg">

            <div class="cardx text-center">

                <h3>18</h3>

                <small>Locations</small>

            </div>

        </div>

        

    </div>

</section>

<!-- =========================
PROFILE TABS
========================== -->

<div class="container my-5">

    <div class="profile-tabs">

        <ul class="nav nav-tabs px-3 pt-3">

            <li class="nav-item">

                <button
                    class="nav-link active"
                    data-bs-toggle="tab"
                    data-bs-target="#overview">

                    Overview

                </button>

            </li>

            <li class="nav-item">

                <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#courses">

                    Courses

                </button>

            </li>

            <li class="nav-item">

                <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#trainers">

                    Trainers

                </button>

            </li>

            <li class="nav-item">

                <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#capabilities">

                    Capabilities

                </button>

            </li>

            <li class="nav-item">

                <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#reviews">

                    Reviews

                </button>

            </li>

            <li class="nav-item">

                <button
                    class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#gallery">

                    Gallery

                </button>

            </li>

        </ul>

        <div class="tab-content">

            <div
                class="tab-pane fade show active"
                id="overview">

                <div class="row">

                    <div class="col-lg-8">

                    <div class="info-card fade-up">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0">About the Institute</h3>

        <span class="badge-soft">
            Verified Provider
        </span>

    </div>

    <p>
        Global Training Institute provides enterprise learning,
        compliance programs, leadership development and technical
        certification training for organizations worldwide.
        The institute delivers instructor-led classroom programs,
        virtual learning and blended learning solutions tailored
        to corporate requirements.
    </p>

    <p class="mb-0">
        With over a decade of experience, the organization has
        successfully trained thousands of professionals across
        manufacturing, oil & gas, healthcare, IT and
        infrastructure sectors.
    </p>

</div>


<div class="info-card fade-up">

    <h4 class="mb-4">
        Training Specializations
    </h4>

    <div class="mb-4">

        <div class="d-flex justify-content-between mb-2">
            <span>Health &amp; Safety</span>
            <strong>96%</strong>
        </div>

        <div class="progress">
            <div class="progress-bar" style="width:96%"></div>
        </div>

    </div>

    <div class="mb-4">

        <div class="d-flex justify-content-between mb-2">
            <span>Compliance Training</span>
            <strong>92%</strong>
        </div>

        <div class="progress">
            <div class="progress-bar bg-success" style="width:92%"></div>
        </div>

    </div>

    <div class="mb-4">

        <div class="d-flex justify-content-between mb-2">
            <span>Leadership Development</span>
            <strong>90%</strong>
        </div>

        <div class="progress">
            <div class="progress-bar bg-warning" style="width:90%"></div>
        </div>

    </div>

    <div>

        <div class="d-flex justify-content-between mb-2">
            <span>Digital Skills</span>
            <strong>88%</strong>
        </div>

        <div class="progress">
            <div class="progress-bar bg-info" style="width:88%"></div>
        </div>

    </div>

</div>


<div class="info-card fade-up">

    <h4 class="mb-3">
        Accreditations & Certifications
    </h4>

    <div class="d-flex flex-wrap gap-2">

        <span class="badge text-bg-light px-3 py-2">
            NEBOSH
        </span>

        <span class="badge text-bg-light px-3 py-2">
            IOSH
        </span>

        <span class="badge text-bg-light px-3 py-2">
            ISO 9001
        </span>

        <span class="badge text-bg-light px-3 py-2">
            ISO 27001
        </span>

        <span class="badge text-bg-light px-3 py-2">
            OSHA
        </span>

        <span class="badge text-bg-light px-3 py-2">
            CPD Certified
        </span>

    </div>

</div>


<div class="info-card fade-up">

    <h4 class="mb-3">
        Industries Served
    </h4>

    <div class="row">

        <div class="col-md-6">

            <ul>

                <li>Construction</li>
                <li>Oil &amp; Gas</li>
                <li>Manufacturing</li>
                <li>Infrastructure</li>

            </ul>

        </div>

        <div class="col-md-6">

            <ul>

                <li>Healthcare</li>
                <li>Information Technology</li>
                <li>Logistics</li>
                <li>Energy & Utilities</li>

            </ul>

        </div>

    </div>

</div>

</div>

<div class="col-lg-4">

    <div class="sticky-side">

    <!-- Provider Score -->
<div class="info-card fade-up">

    <h2 class="text-primary fw-bold mb-1">
        96%
    </h2>

    <p class="text-muted mb-4">
        Overall Provider Score
    </p>

    <button class="btn btn-primary w-100 mb-3">
        <i class="bi bi-send me-2"></i>
        Request Proposal
    </button>

    <button class="btn btn-outline-primary w-100">
        <i class="bi bi-download me-2"></i>
        Download Brochure
    </button>

</div>


<!-- Trusted Organizations -->
<div class="info-card fade-up">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <small class="text-uppercase text-primary fw-semibold">
                Trusted By
            </small>

            <h5 class="mb-0">
                Leading Organizations
            </h5>

        </div>

    </div>



    <div class="logo-slider mb-3">
    <div class="logo-track">

        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/microsoft.svg" alt="Microsoft">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/google.svg" alt="Google">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/amazon.svg" alt="Amazon">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/oracle.svg" alt="Oracle">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/ibm.svg" alt="IBM">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/cisco.svg" alt="Cisco">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/sap.svg" alt="SAP">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/intel.svg" alt="Intel">

        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/microsoft.svg" alt="Microsoft">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/google.svg" alt="Google">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/amazon.svg" alt="Amazon">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/oracle.svg" alt="Oracle">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/ibm.svg" alt="IBM">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/cisco.svg" alt="Cisco">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/sap.svg" alt="SAP">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/intel.svg" alt="Intel">

    </div>
</div>

<div class="logo-slider">
    <div class="logo-track reverse">

        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/accenture.svg" alt="Accenture">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/deloitte.svg" alt="Deloitte">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/hp.svg" alt="HP">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/lenovo.svg" alt="Lenovo">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/apple.svg" alt="Apple">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/meta.svg" alt="Meta">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/adobe.svg" alt="Adobe">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/nvidia.svg" alt="NVIDIA">

        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/accenture.svg" alt="Accenture">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/deloitte.svg" alt="Deloitte">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/hp.svg" alt="HP">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/lenovo.svg" alt="Lenovo">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/apple.svg" alt="Apple">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/meta.svg" alt="Meta">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/adobe.svg" alt="Adobe">
        <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/nvidia.svg" alt="NVIDIA">

    </div>
</div>

</div>


<!-- Client Reviews -->
<div class="info-card testimonial-card fade-up">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>

            <small class="text-uppercase text-primary fw-semibold">
                Client Reviews
            </small>

            <h5 class="mb-0 mt-1">
                What Our Clients Say
            </h5>

        </div>

        <div class="rating-badge">

            <i class="bi bi-star-fill"></i>

            4.8

        </div>

    </div>

    <div id="testimonialCarousel"
         class="carousel slide"
         data-bs-ride="carousel"
         data-bs-interval="3500">

        <div class="carousel-inner">

            <!-- Review 1 -->

            <div class="carousel-item active">

                <div class="quote-icon">

                    <i class="bi bi-quote"></i>

                </div>

                <p class="testimonial-text">

                    Excellent trainers with outstanding domain expertise.
                    The program significantly improved our team's
                    compliance and technical capabilities.

                </p>

                <hr>

                <div class="d-flex align-items-center">

                    <img src="https://i.pravatar.cc/100?img=12"
                         class="testimonial-avatar">

                    <div class="ms-3">

                        <h6 class="mb-1 fw-bold">
                            Rahul Sharma
                        </h6>

                        <small class="text-muted d-block">

                            Learning & Development Manager

                        </small>

                        <span class="company-badge">

                            ABC Manufacturing

                        </span>

                    </div>

                </div>

            </div>

            <!-- Review 2 -->

            <div class="carousel-item">

                <div class="quote-icon">

                    <i class="bi bi-quote"></i>

                </div>

                <p class="testimonial-text">

                    One of the best corporate learning partners we've
                    worked with. Professional delivery and excellent
                    learning outcomes.

                </p>

                <hr>

                <div class="d-flex align-items-center">

                    <img src="https://i.pravatar.cc/100?img=33"
                         class="testimonial-avatar">

                    <div class="ms-3">

                        <h6 class="mb-1 fw-bold">

                            Sarah Johnson

                        </h6>

                        <small class="text-muted d-block">

                            HR Director

                        </small>

                        <span class="company-badge">

                            Siemens

                        </span>

                    </div>

                </div>

            </div>

            <!-- Review 3 -->

            <div class="carousel-item">

                <div class="quote-icon">
                    <i class="bi bi-quote"></i>
                </div>

                <p class="testimonial-text">

                    Highly recommended. Their customized learning
                    solutions helped improve productivity across
                    multiple departments and locations.

                </p>

                <hr>

                <div class="d-flex align-items-center">

                    <img src="https://i.pravatar.cc/100?img=25"
                         class="testimonial-avatar">

                    <div class="ms-3">

                        <h6 class="mb-1 fw-bold">
                            David Wilson
                        </h6>

                        <small class="text-muted d-block">
                            Operations Manager
                        </small>

                        <span class="company-badge">
                            Shell
                        </span>

                    </div>

                </div>

            </div>

        </div>

        <!-- Indicators -->

        <div class="carousel-indicators position-static mt-4 mb-0">

            <button type="button"
                    data-bs-target="#testimonialCarousel"
                    data-bs-slide-to="0"
                    class="active"></button>

            <button type="button"
                    data-bs-target="#testimonialCarousel"
                    data-bs-slide-to="1"></button>

            <button type="button"
                    data-bs-target="#testimonialCarousel"
                    data-bs-slide-to="2"></button>

        </div>

    </div>

</div>

</div> <!-- /.sticky-side -->

</div> <!-- /.col-lg-4 -->

</div> <!-- /.row -->

</div> <!-- /#overview -->


<!-- =========================
Courses Tab
========================= -->

<div class="tab-pane fade" id="courses">

    <div class="info-card">

        <h3 class="mb-3">
            Available Courses
        </h3>

        <p class="text-muted mb-4">
            Browse the complete list of instructor-led, virtual and
            blended learning programs offered by the institute.
        </p>

        <div class="row g-4">

            <div class="col-md-6">

                <div class="cardx">

                    <h5>NEBOSH IGC</h5>

                    <p class="text-muted mb-3">
                        International General Certificate in Occupational Health & Safety.
                    </p>

                    <span class="badge bg-primary">
                        Safety
                    </span>

                </div>

            </div>

            <div class="col-md-6">

                <div class="cardx">

                    <h5>IOSH Managing Safely</h5>

                    <p class="text-muted mb-3">
                        Practical health & safety management for supervisors.
                    </p>

                    <span class="badge bg-success">
                        Compliance
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================
Trainers
========================= -->

<div class="tab-pane fade" id="trainers">

    <div class="info-card">

        <h3>Expert Trainers</h3>

        <p class="mb-0">
            Certified trainers with extensive industry experience
            across safety, compliance, leadership and technical
            disciplines.
        </p>

    </div>

</div>


<!-- =========================
Capabilities
========================= -->

<div class="tab-pane fade" id="capabilities">

    <div class="info-card">

        <h3>Capabilities</h3>

        <ul>

            <li>Instructor-Led Classroom Training</li>

            <li>Virtual Instructor-Led Training</li>

            <li>Blended Learning</li>

            <li>Learning Management System Integration</li>

            <li>Corporate Assessments & Certifications</li>

        </ul>

    </div>

</div>


<!-- =========================
Reviews
========================= -->

<div class="tab-pane fade" id="reviews">

    <div class="info-card">

        <h3>Corporate Reviews</h3>

        <p class="mb-0">
            Overall client satisfaction rating of
            <strong>4.9 / 5</strong>
            based on enterprise training engagements.
        </p>

    </div>

</div>


<!-- =========================
Gallery
========================= -->

<div class="tab-pane fade" id="gallery">

    <div class="info-card">

        <h3>Gallery</h3>

        <p class="mb-4">
            Corporate training sessions and workshops.
        </p>

        <div class="row g-3">

            <div class="col-md-4">
                <img class="img-fluid rounded"
                     src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600">
            </div>

            <div class="col-md-4">
                <img class="img-fluid rounded"
                     src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=600">
            </div>

            <div class="col-md-4">
                <img class="img-fluid rounded"
                     src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=600">
            </div>

        </div>

    </div>

</div>

</div> <!-- /.tab-content -->

</div> <!-- /.profile-tabs -->

</div> <!-- /.container -->

</div> <!-- /.institute-profile-page -->

@endsection


@section('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let url = "{{ route('getAllTenantData') }}";
    let tenant_id = "{{ session('tenant_id') }}";

    $(document).ready(function () {
        loadTenantData();
    });

    function loadTenantData() {
        $('#pageLoader').show();

        $.ajax({
            url: url,
            type: "GET",
            data: {
                tenant_id: tenant_id
            },
            success: function(response) {

                let tenant = response.tenant;

                

                $('#institute_name').text(response.tenant_name);
                $('#tenant_slogan').text(response.tenant_slogan);
                $('#tenant_experience').text(response.tenant_experience);
                $('#about').html(response.about);
                $('#phone').text(response.phone);
                $('#email').text(response.email);
                $('#address').text(response.address);
                $('#verified_provider').text(
                    response.verified_provider ? 'Verified Provider' : 'Unverified Provider'
                );
                $('#is_certified').text(response.is_certified);

                // Course count
                $('#course_count').text(response.tenant_courses_count);

                // Review count
                $('#review_count').text(response.tenant_reviews_count);

                if (response.tenant_logo) {
                    $('#logo').attr('src', response.tenant_logo);
                }

                $('#pageLoader').fadeOut(300);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                $('#pageLoader').fadeOut(300);
            }
        });
    }
</script>
@endsection