<?php
/**
 * WPoets-assignment – Home Page
 * Sections and slides fetched live from MySQL via MySQLi.
 */
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/models/SectionModel.php';
require_once __DIR__ . '/models/SlideModel.php';

$sectionModel = new SectionModel($db);
$slideModel   = new SlideModel($db);

$sections = $sectionModel->getAll();   // active only, ordered by sort_order
$slides   = $slideModel->getAll();     // active only, ordered by sort_order

// Map section_id → first slide (used by mobile accordion inline panels)
$slideMap = [];
foreach ($slides as $slide) {
    if (!isset($slideMap[$slide['section_id']])) {
        $slideMap[$slide['section_id']] = $slide;
    }
}

$page_title = 'Home';
require_once __DIR__ . '/templates/head.php';
?>

<!-- ══ TOP NAV ═══════════════════════════════════════════════════════════ -->
<nav class="dl-topnav">
    <div class="container">
        <a href="admin/index.php" class="dl-topnav__admin">
            &#9881; Admin Panel
        </a>
    </div>
</nav>

<!-- ══ HERO SECTION ══════════════════════════════════════════════════════ -->
<section class="dl-hero">
    <div class="container">

        <!-- Header text -->
        <div class="dl-hero__header text-center">
            <h2><?= e(SITE_TAGLINE) ?></h2>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo</p>
        </div>

        <!-- Interactive panel: accordion (col 1) + carousel (col 2+3) -->
        <div class="dl-panel" id="dlPanel">

            <!-- ── COL 1: Section accordion ───────────────────────────── -->
            <aside class="dl-panel__accordion" role="navigation" aria-label="Category navigation">
                <?php foreach ($sections as $i => $sec):
                    $slide = $slideMap[$sec['id']] ?? null;
                ?>
                <div class="dl-accordion-item<?= $i === 0 ? ' is-active' : '' ?>"
                     data-slide="<?= $i ?>"
                     data-section-id="<?= (int) $sec['id'] ?>"
                     role="button"
                     tabindex="0"
                     aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>">

                    <!-- Header row (always visible) -->
                    <div class="dl-accordion-item__header">
                        <span class="dl-accordion-item__icon" aria-hidden="true">
                            <?= inline_svg(__DIR__ . '/' . $sec['icon_path'], 'dl-icon') ?>
                        </span>
                        <span class="dl-accordion-item__label"><?= e($sec['label']) ?></span>
                        <span class="dl-accordion-item__toggle" aria-hidden="true">
                            <?= inline_svg(__DIR__ . '/assets/svg/minus-01.svg', 'icon-minus' . ($i === 0 ? '' : ' d-none')) ?>
                            <?= inline_svg(__DIR__ . '/assets/svg/plus-01.svg',  'icon-plus'  . ($i === 0 ? ' d-none' : '')) ?>
                        </span>
                    </div>

                    <?php if ($slide): ?>
                    <!-- Mobile inline panel (hidden on desktop, shown when is-open on mobile) -->
                    <div class="dl-accordion-item__panel<?= $i === 0 ? ' is-open' : '' ?>">
                        <div class="dl-mobile-slide"
                             style="background-image:url('<?= e($slide['image_path']) ?>');">
                            <div class="dl-mobile-slide__overlay" aria-hidden="true"></div>
                            <div class="dl-mobile-slide__content">
                                <span class="dl-slide__eyebrow"><?= e($slide['category']) ?></span>
                                <h3 class="dl-slide__title"><?= e($slide['title']) ?></h3>
                                <a href="<?= e($slide['link_url']) ?>" class="dl-slide__cta">
                                    Learn More
                                    <span class="dl-slide__arrow" aria-hidden="true">
                                        <?= inline_svg(__DIR__ . '/assets/svg/arrow-right.svg') ?>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>
            </aside>

            <!-- ── COL 2+3: Bootstrap carousel ───────────────────────── -->
            <div class="dl-panel__carousel">
                <div id="dlCarousel" class="carousel slide h-100"
                     data-bs-ride="carousel" data-bs-interval="5000">

                    <!-- Slides -->
                    <div class="carousel-inner h-100">
                        <?php foreach ($slides as $i => $slide): ?>
                        <div class="carousel-item h-100<?= $i === 0 ? ' active' : '' ?>"
                             data-section-id="<?= (int) $slide['section_id'] ?>">

                            <div class="dl-slide">
                                <!-- Col 2: text content -->
                                <div class="dl-slide__text">
                                    <span class="dl-slide__eyebrow"><?= e($slide['category']) ?></span>
                                    <h3 class="dl-slide__title"><?= e($slide['title']) ?></h3>
                                    <a href="<?= e($slide['link_url']) ?>" class="dl-slide__cta">
                                        Learn More
                                        <span class="dl-slide__arrow" aria-hidden="true">
                                            <?= inline_svg(__DIR__ . '/assets/svg/arrow-right.svg') ?>
                                        </span>
                                    </a>
                                </div>

                                <!-- Col 3: 1:1 image -->
                                <div class="dl-slide__image"
                                     style="background-image:url('<?= e($slide['image_path']) ?>');"
                                     role="img"
                                     aria-label="<?= e($slide['title']) ?>"></div>
                            </div>

                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Dot indicators -->
                    <div class="carousel-indicators dl-carousel-dots">
                        <?php foreach ($slides as $i => $slide): ?>
                        <button type="button"
                                data-bs-target="#dlCarousel"
                                data-bs-slide-to="<?= $i ?>"
                                class="dl-dot<?= $i === 0 ? ' active' : '' ?>"
                                aria-label="Slide <?= $i + 1 ?>"
                                <?= $i === 0 ? 'aria-current="true"' : '' ?>></button>
                        <?php endforeach; ?>
                    </div>

                </div><!-- /#dlCarousel -->
            </div><!-- /.dl-panel__carousel -->

        </div><!-- /.dl-panel -->

    </div><!-- /.container -->
</section>

<?php require_once __DIR__ . '/templates/foot.php'; ?>
