<!DOCTYPE html>
<html lang="en-US" vocab="http://schema.org/" typeof="WebPage">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Fepa statistics">
    <meta name="keywords" content="HTML, CSS, JavaScript, statistics, statistics page, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei">
    <meta name="author" content="Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Statistics</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/statistics.css" type="text/css">
</head>
<body vocab="http://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>
<main typeof="WebPageElement">
    <h1 property="headline">Statistics</h1>
    <div id="container">
        <div id="container__grid">
            <div class="grid__element">
                <canvas id="myPieChart"></canvas>
            </div>

            <div class="grid__element">
                <canvas id="myBarChart"></canvas>
            </div>

            <div class="grid__element">
                <canvas id="myPolarAreaChart"></canvas>
            </div>

            <div class="grid__element">
                <canvas id="myBarChartHorizontal"></canvas>
            </div>

        </div>
        <div id="container__download">
            <h2>Download statistics as:</h2>
            <p>
                <button id="downloadBtnHTML" type="submit">Download HTML</button>
                <button id="downloadBtnPDF" type="submit">Download PDF</button>
                <button id="downloadBtnCSV" type="submit">Download CSV</button>
            </p>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?php echo BASE_URL; ?>/scripts/charts/charts.js"></script>
<script src="<?php echo BASE_URL; ?>/scripts/fileData.js"></script>
</body>
</html>
