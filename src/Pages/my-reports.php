<?php
use Src\Repositories\ReportRepository;
use Src\Repositories\ImageRepository;

// Instantiate repositories
$reportRepository = new ReportRepository();
$imageRepository = new ImageRepository();

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
    <title property="name">My reports</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/my-reports.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
</head>
<body vocab="http://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>

<main typeof="WebPageElement">
    <h1 property="headline">Your reports</h1>
    <h2>Here you can edit, delete or close them.</h2>

    <div id="container">
        <div id="container__grid">
            <?php foreach ($reports as $report): ?>
                <?php
                $images = $imageRepository->getImagesByReportId($report->getId());
                $imageSrc = !empty($images) ? BASE_URL . '/user-image-uploads/' . $images[0]->getFileName() : '../../images/default.jpg';
                ?>
                <div class="grid__card">
                    <div>
                        <h4>Status: <?php echo htmlspecialchars($report->getStatus()); ?></h4>
                    </div>
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
                    <div class="card__buttons">
                        <button class="edit-btn" onclick="editReport(<?php echo $report->getId(); ?>)">Edit</button>
                        <button class="delete-btn" onclick="deleteReport(<?php echo $report->getId(); ?>)">Delete</button>
                        <button class="close-btn" onclick="closeReport(<?php echo $report->getId(); ?>)">Close</button>
                    </div>
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
<script src="<?php echo BASE_URL; ?>/scripts/my-reports.js"></script>
<script>
    function editReport(reportId) {
        // Redirect to the edit report page
        window.location.href = `<?php echo BASE_URL; ?>/index.php/reports/editReport/${reportId}`;
    }

    function deleteReport(reportId) {
        if (confirm('Are you sure you want to delete this report?')) {
            // Send delete request to the server
            fetch(`<?php echo BASE_URL; ?>/index.php/reports/${reportId}`, {
                method: 'DELETE'
            }).then(response => {
                if (response.ok) {
                    alert('Report deleted successfully');
                    location.reload();
                } else {
                    alert('Failed to delete report');
                }
            });
        }
    }

    function closeReport(reportId) {
        if (confirm('Are you sure you want to close this report?')) {
            // Send close request to the server
            fetch(`<?php echo BASE_URL; ?>/index.php/reports/${reportId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status: 'closed' })
            }).then(response => {
                if (response.ok) {
                    alert('Report closed successfully');
                    location.reload();
                } else {
                    alert('Failed to close report');
                }
            });
        }
    }
</script>
<script>
    // Fetch the markers
    fetch('http://localhost/tw-project/index.php/coordinates/<?php echo $userId ?>')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(markers => {
            // Initialize the map with the fetched markers
            initializeMap(markers);
        })
        .catch(error => console.error('Error fetching marker data:', error));
</script>
</body>
</html>
