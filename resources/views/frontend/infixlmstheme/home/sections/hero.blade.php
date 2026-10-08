<section class="hero-edumall position-relative"
    style="
        background: url('{{ asset('public/frontend/infixlmstheme/img/banner/banner-custom.jpg') }}') no-repeat right center;
        background-size: cover;
        height: 700px;
    ">

    <!-- WHITE GRADIENT OVERLAY -->
    <div class="hero-overlay"></div>

    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center" style="height:700px;">

            <div class="col-lg-8">

                <!-- SMALL LABEL -->
                <span class="hero-label">START TO SUCCESS</span>

                <!-- HEADING -->
                <h1 class="hero-title mt-3">
                    Access To 
                    <span class="highlight">{{ $course_count }}+</span> Courses <br>
                    from 
                    <span class="highlight">80+</span> Instructors & Institutions
                </h1>

                <!-- SUBTEXT -->
                <p class="hero-subtitle mt-3">
                    Take your learning organisation to the next level.
                </p>

                <!-- SEARCH -->
                <div class="hero-search mt-4">
                    <input type="text" placeholder="What do you want to learn?">
                    <button>
                        <i class="ti-search"></i>
                    </button>
                </div>

            </div>

        </div>
    </div>

</section>


<style>

    .hero-overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to right,
        rgba(255,255,255,0.95) 30%,
        rgba(255,255,255,0.6) 55%,
        rgba(255,255,255,0) 75%
    );
    top: 0;
    left: 0;
    z-index: 1;
}

/* LABEL */
.hero-label {
    font-size: 14px;
    font-weight: 700;
    color: #2563eb;
    letter-spacing: 2px;
}

/* TITLE */
.hero-title {
    font-size: 56px;
    font-weight: 800;
    line-height: 1.2;
    color: #1f2937;
}

/* BLUE NUMBER */
.highlight {
    color: #2563eb;
    position: relative;
    font-weight: 800;
}

/* YELLOW UNDERLINE EFFECT */
.highlight::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 100%;
    height: 6px;
    background: #fbbf24;
    border-radius: 4px;
    transform: rotate(-2deg);
}

/* SUBTEXT */
.hero-subtitle {
    font-size: 18px;
    color: #6b7280;
    font-weight: 500;
}

/* SEARCH BOX */
.hero-search {
    display: flex;
    max-width: 520px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
}

.hero-search input {
    flex: 1;
    height: 60px;
    border: none;
    padding: 0 20px;
    font-size: 16px;
    outline: none;
}

.hero-search button {
    width: 70px;
    background: #2563eb;
    border: none;
    color: #fff;
    font-size: 18px;
}

/* RESPONSIVE */
@media (max-width: 991px) {
    .hero-title {
        font-size: 38px;
    }

    .hero-edumall {
        height: auto !important;
        padding: 80px 0;
    }
}
</style>