
<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$page_css = "/University-Internship-and-Career-Portal-System/assets/css/member4.css";

$keyword = trim($_GET['search'] ?? '');

$internships = m4_getInternships($conn, $keyword);

include __DIR__ . '/../includes/header.php';

?>

<section class="m4-hero">

    <div class="m4-hero-content">

        <span class="m4-tag">INTERNSHIP OPPORTUNITIES</span>

        <h1>Discover Your Next Opportunity</h1>

        <p>
            Explore internship opportunities from verified companies
            and take the next step in your career journey.
        </p>

        <a href="#internship-list" class="m4-btn">
            Explore Internships
        </a>

    </div>

</section>


<section class="m4-search-section">

    <h2>Find Your Internship</h2>

    <p>Search by internship title, company or required skills.</p>

    <form method="GET" action="index.php" class="m4-search-form">

        <input
            type="text"
            name="search"
            placeholder="Search internships..."
            value="<?php echo m4_escape($keyword); ?>"
        >

        <button type="submit" class="m4-btn">
            Search
        </button>

        <?php if ($keyword !== ''): ?>
            <a href="index.php" class="m4-clear-btn">Clear</a>
        <?php endif; ?>

    </form>

</section>


<section class="m4-internship-section" id="internship-list">

    <div class="m4-section-heading">

        <div>
            <h2>Available Internships</h2>
            <p>Explore opportunities posted by verified companies.</p>
        </div>

        <span class="m4-count">
            <?php echo count($internships); ?> Opportunities
        </span>

    </div>


    <?php if (empty($internships)): ?>

        <div class="m4-empty">

            <h3>No Internships Found</h3>

            <p>
                <?php if ($keyword !== ''): ?>
                    No internships match your search.
                    Try another keyword.
                <?php else: ?>
                    There are currently no available internships.
                    Please check again later.
                <?php endif; ?>
            </p>

        </div>

    <?php else: ?>

        <div class="m4-card-container">

            <?php foreach ($internships as $internship): ?>

                <div class="m4-card">

                    <div class="m4-card-top">

                        <span class="m4-label">
                            Internship
                        </span>

                        <span class="m4-deadline">
                            Deadline:
                            <?php echo m4_escape($internship['deadline']); ?>
                        </span>

                    </div>

                    <h3>
                        <?php echo m4_escape($internship['title']); ?>
                    </h3>

                    <h4>
                        <?php echo m4_escape($internship['company_name']); ?>
                    </h4>

                    <p class="m4-description">
                        <?php
                        $description = $internship['description'];

                        if (strlen($description) > 160) {
                            $description = substr($description, 0, 160) . '...';
                        }

                        echo m4_escape($description);
                        ?>
                    </p>

                    <a
                        href="details.php?id=<?php echo (int)$internship['internship_id']; ?>"
                        class="m4-view-btn"
                    >
                        View Details
                    </a>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>


<section class="m4-bottom-section">

    <h2>Take the Next Step in Your Career</h2>

    <p>
        Explore opportunities, improve your skills and gain
        valuable professional experience.
    </p>

</section>


<?php include __DIR__ . '/../includes/footer.php'; ?>
