<section class="categories-section">

    <div class="container">

        <!-- TITLE -->
        <h2 class="cat-title">
            Top Categories
            <span></span>
        </h2>

        <!-- GRID -->
        <div class="row g-4">

            @foreach($categories as $category)
                <div class="col-lg-3 col-md-6">

                    <a href="{{ url('courses') }}?category_id[]={{ $category->id }}" class="cat-card">

                        <div class="cat-inner">

                            <!-- ICON -->
                            <div class="cat-icon">
                                <img src="{{ $category->image ? asset(str_replace('/public/', '', $category->image)) : asset('frontend/infixlmstheme/images/default.png') }}"
                                    alt="{{ $category->name }}"
                                >
                            </div>

                            <!-- NAME -->
                            <div class="cat-name">
                                {{ $category->name }}
                            </div>

                        </div>

                        <!-- ARROW -->
                        <span class="cat-arrow">›</span>

                    </a>

                </div>
            @endforeach

        </div>

    </div>

</section>


<!-- 🔥 STYLE (PUT HERE OR MOVE TO CSS FILE) -->
<style>

.categories-section {
    padding: 80px 0;
    background: #f8fafc;
}

/* TITLE */
.cat-title {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 40px;
    color: #1f2937;
}

.cat-title span {
    display: block;
    width: 60px;
    height: 4px;
    background: #f59e0b;
    margin-top: 8px;
    border-radius: 2px;
}

/* CARD */
.cat-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #eef2f7;
    padding: 20px 24px;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s ease;
}

/* INNER */
.cat-inner {
    display: flex;
    align-items: center;
}

/* ICON */
.cat-icon {
    width: 42px;
    height: 42px;
    margin-right: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cat-icon img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

/* TEXT */
.cat-name {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
}

/* ARROW */
.cat-arrow {
    font-size: 20px;
    color: #9ca3af;
    transition: 0.3s;
}

/* HOVER */
.cat-card:hover {
    background: #1e73be;
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.cat-card:hover .cat-name {
    color: #fff;
}

.cat-card:hover .cat-arrow {
    color: #fff;
    transform: translateX(4px);
}

/* ICON WHITE ON HOVER */
.cat-card:hover .cat-icon img {
    filter: brightness(0) invert(1);
}

</style>