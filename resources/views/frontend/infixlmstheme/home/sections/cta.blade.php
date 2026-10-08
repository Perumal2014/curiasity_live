<section class="cta-section py-60">
    <div class="container">

        <div class="cta-box d-flex align-items-center justify-content-between">

            <!-- LEFT CONTENT -->
            <div class="d-flex align-items-center">

                <!-- ICON -->
                <div class="cta-icon">
                    ⚡
                </div>

                <!-- TEXT -->
                <div class="ms-3">
                    <span class="cta-small">Let Us Help</span>
                    <h4 class="cta-title mb-0">Finding Your Right Courses</h4>
                </div>

            </div>

            <!-- BUTTON -->
            <a href="{{ route('courses') }}" class="cta-btn">
                Get started
            </a>

        </div>

    </div>
</section>

<style>
    .cta-section {
    background: #ffffff;
}

/* MAIN BOX */
.cta-box {
    background: #eef2f7;
    padding: 30px 40px;
    border-radius: 12px;
    position: relative;
    overflow: hidden;
}

/* LIGHT CURVE DECORATION */
.cta-box::before {
    content: "";
    position: absolute;
    right: -80px;
    top: -80px;
    width: 200px;
    height: 200px;
    border: 1px solid #dbeafe;
    border-radius: 50%;
}

/* ICON */
.cta-icon {
    width: 60px;
    height: 60px;
    background: #2563eb;
    color: #fff;
    font-size: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* SMALL TEXT */
.cta-small {
    color: #2563eb;
    font-size: 14px;
    font-weight: 600;
    display: block;
}

/* TITLE */
.cta-title {
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
}

/* BUTTON */
.cta-btn {
    background: #fbbf24;
    color: #000;
    padding: 12px 28px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.3s;
}

.cta-btn:hover {
    background: #f59e0b;
    color: #000;
}
</style>