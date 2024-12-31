<?php
// Extract the report and images from the local scope
if (!isset($report)) {
    echo "Report data is not available.";
    exit;
}

$tags = explode(',', $report->getTags());
?>

<!DOCTYPE html>
<html lang="en-US" vocab="http://schema.org/" typeof="WebPage">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Fepa animal complete report">
    <meta name="keywords" content="HTML, CSS, JavaScript, personal animal page, complete report page, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei">
    <meta name="author" content="Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Report animal</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/animal.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
</head>

<body vocab="http://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>

<main typeof="WebPageElement">
    <h1>Report with ID: <?php echo htmlspecialchars($report->getId()); ?></h1>
    <div id="container">
        <div id="container__grid">
            <div id="grid__left-card">
                <div id="left-card__slideshow-container">
                    <?php foreach ($images as $image): ?>
                        <div class="mySlides fade">
                            <img src="<?php echo BASE_URL; ?>/user-image-uploads/<?php echo htmlspecialchars($image->getFileName()); ?>" alt="Image" class="slides__image-card">
                        </div>
                    <?php endforeach; ?>
                    <a class="prev" onclick="plusSlides(-1)">❮</a>
                    <a class="next" onclick="plusSlides(1)">❯</a>
                </div>
            </div>
            <div id="grid__right-card1">
                <h2><?php echo htmlspecialchars($report->getAnimalName()); ?></h2>
                <br>
                <h3><?php echo htmlspecialchars($repository->getAnimalType($report->getId())); ?></h3>
                <br>
                <p><?php echo nl2br(htmlspecialchars($report->getDescription())); ?></p>
            </div>
            <div id="grid__right-card2">
                <h3>Tags</h3>
                <ul>
                    <?php foreach ($tags as $tag): ?>
                        <li><?php echo htmlspecialchars($tag); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="grid__right-card3">
                <h3>Contact</h3>
                <p>
                    Owner: <?php echo htmlspecialchars($report->getName()); ?><br>
                    Phone Number: <?php echo htmlspecialchars($report->getPhoneNumber()); ?>
                </p>
            </div>
        </div>
    </div>
</main>

<footer typeof="WPFooter">
    <div id="footer">
        <h3>&copy; 2024 Fepa.com</h3>
        <p>All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
<script src="<?php echo BASE_URL; ?>/scripts/slideshow.js"></script>
</body>

</html>
