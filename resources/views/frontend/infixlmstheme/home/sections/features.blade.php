<!-- FONT AWESOME (ADD IN MASTER LAYOUT HEAD IF NOT ADDED) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<section class="features-strip">

    <div class="container">

        <div class="features-wrapper">

            <!-- ITEM 1 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="feature-text">
                    Learn The <br> Essential Skills
                </div>
            </div>

            <!-- ITEM 2 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="feature-text">
                    Earn Certificates <br> And Degrees
                </div>
            </div>

            <!-- ITEM 3 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="feature-text">
                    Get Ready for The <br> Next Career
                </div>
            </div>

            <!-- ITEM 4 -->
            <div class="feature-item">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="feature-text">
                    Master at Different <br> Areas
                </div>
            </div>

        </div>

    </div>

</section>


<style>

/* 🔵 STRIP BACKGROUND */
.features-strip {
    background: linear-gradient(90deg, #2563eb, #1e40af);
    padding: 30px 0;
    margin-bottom: 30px; /* reduce gap with categories */
}

/* FLEX WRAPPER */
.features-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

/* EACH ITEM */
.feature-item {
    display: flex;
    align-items: center;
    color: #fff;
    width: 24%;
    transition: 0.3s;
}

/* ICON */
.feature-icon {
    font-size: 32px;
    margin-right: 14px;
    color: #fff;
}

/* TEXT */
.feature-text {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.5;
}

/* HOVER EFFECT */
.feature-item:hover {
    transform: translateY(-4px);
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .feature-item {
        width: 50%;
        margin-bottom: 15px;
    }
}

@media (max-width: 576px) {
    .feature-item {
        width: 100%;
    }
}

</style>