<?php
use Src\Repositories\ReportRepository;
use Src\Repositories\ImageRepository;

// Instantiate repositories
$reportRepository = new ReportRepository();
$imageRepository = new ImageRepository();

$order = $_GET['orderBy'] ?? 'newest';
?>

<!DOCTYPE html>
<html lang="en-US" vocab="http://schema.org/" typeof="WebPage">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Fepa missing animals list">
    <meta name="keywords" content="HTML, CSS, JavaScript, reports page, report missing animals list,list, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei">
    <meta name="author" content="Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Reports</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/reports.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
</head>
<body vocab="http://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>

<main typeof="WebPageElement">
    <h1 property="headline">Active reports</h1>
    <h2>These are animals currently missing or in danger.</h2>
    <h2><em>Click on a report to find out more!</em></h2>
    <p>
        You can also check out <em>your</em> reports
        <a href="<?php echo BASE_URL; ?>/index.php/reports/myReports">here</a>.
    </p>

    <!-- Add sorting menu -->
    <div id="filter-menu">
        <label for="orderBy">Sort by:</label>
        <select id="orderBy">
            <option value="newest" <?php echo $order === 'newest' ? 'selected' : ''; ?>>Newest</option>
            <option value="oldest" <?php echo $order === 'oldest' ? 'selected' : ''; ?>>Oldest</option>
        </select>
    </div>

    <div id="container">
        <div id="container__grid">
            <?php foreach ($reports as $report): ?>
                <?php
                $images = $imageRepository->getImagesByReportId($report->getId());
                $imageSrc = !empty($images) ? BASE_URL . '/user-image-uploads/' . $images[0]->getFileName() : '../../images/default.jpg';
                ?>
                <div class="grid__card">
                    <div class="card__img">
                        <a href=<?php echo BASE_URL; ?>/index.php/reports/<?php echo $report->getId(); ?>>
                            <img src="<?php echo $imageSrc; ?>" alt="Image of <?php
                            $animalType = $reportRepository->getAnimalType($report->getId());
                            echo htmlspecialchars($animalType); ?>">
                        </a>
                    </div>
                    <h2><?php echo htmlspecialchars($report->getAnimalName()); ?></h2>
                    <h4>Tags</h4>
                    <p><?php echo htmlspecialchars($report->getTags()); ?></p>
                    <h4>Description</h4>
                    <p><?php echo htmlspecialchars($report->getDescription()); ?></p>
                    <h4>Contact</h4>
                    <p>
                        Phone Number: <?php echo htmlspecialchars($report->getPhoneNumber()); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <h2>Map of current reports</h2>
    <div id="map"></div>
</main>

<footer typeof="WPFooter">
    <div id="footer">
        <h3>&copy; 2024 Fepa.com</h3>
        <p>All rights reserved.</p>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
<script src="<?php echo BASE_URL; ?>/scripts/reports.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sortOrder = document.getElementById('orderBy');

        sortOrder.addEventListener('change', function () {
            window.location.href = '<?php echo BASE_URL; ?>/index.php/reports?orderBy=' + sortOrder.value;
        });
    });
</script>
</body>
</html>