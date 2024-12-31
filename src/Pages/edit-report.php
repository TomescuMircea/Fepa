<!DOCTYPE html>
<html lang="en-US" vocab="http://schema.org/" typeof="WebPage">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Fepa edit report">
    <meta name="keywords" content="HTML, CSS, JavaScript, edit report page, edit report, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei">
    <meta name="author" content="Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Edit Report</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/report-ma.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
</head>

<body vocab="http://schema.org/" typeof="WebPage">

<script>
    let selectedTags = []
    function toggleTag(tagId, tagName) {
        const index = selectedTags.indexOf(tagName);
        var button = document.getElementById(tagId);
        button.classList.toggle("active");
        if (index === -1) {
            selectedTags.push(tagName);
            document.getElementById(tagId).classList.add('selected');
        } else {
            selectedTags.splice(index, 1);
            document.getElementById(tagId).classList.remove('selected');
        }
        document.getElementById('tagsInput').value = selectedTags.join(', ');
        console.log(selectedTags);
    }
</script>

<?php
include 'header.php';
?>

<main typeof="WebPageElement">
    <div id="background">
        <div id="container">
            <section id="container__section">
                <h1 property="headline">Edit Report</h1>
                <form class="form" id="report-form" action="javascript:void(0)" method="POST" enctype="multipart/form-data">
                    <label for="name" class="form__label">Your name:</label><br>
                    <input type="text" name="name" id="name" class="form__input-name" value="<?php echo htmlspecialchars($report->getName()); ?>" required autocomplete="additional-name"><br><br>

                    <label for="animal_name" class="form__label">Animal's name:</label><br>
                    <input type="text" name="animal_name" id="animal_name" class="form__input-name" value="<?php echo htmlspecialchars($report->getAnimalName()); ?>" required autocomplete="additional-name"><br><br>

                    <label for="location" class="form__label">Location:</label><br>
                    <input type="text" name="location" id="location" class="form__input-location" value="<?php echo htmlspecialchars($report->getLocation()); ?>" required><br><br>

                    <label for="animal_type" class="form__label">Animal type:</label><br>
                    <select name="animal_type" id="animal_type" class="form__select-animal_type" required>
                        <option value="">Select a type of animal</option>
                        <?php foreach ($animalTypes as $animalType): ?>
                            <option value="<?php echo htmlspecialchars($animalType->getType()); ?>" <?php echo ($animalType->getType() == $reportRepository->getAnimalType($report->getId())) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($animalType->getType()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br><br>

                    <label for="description" class="form__label">Description - be as detailed as you can:</label><br>
                    <textarea rows="5" cols="60" name="description" id="description" class="form__textarea-description" required><?php echo htmlspecialchars($report->getDescription()); ?></textarea><br><br>

                    <label for="phone_number" class="form__label">Phone number (in format 0xxx-xxx-xxx):</label><br>
                    <input type="tel" name="phone_number" id="phone_number" class="form__input-phone_number" pattern="[0]{1}[0-9]{3}-[0-9]{3}-[0-9]{3}" value="<?php echo htmlspecialchars($report->getPhoneNumber()); ?>" required><br><br>

                    <label for="upload-images" class="form__label">Upload new images if necessary:</label><br>
                    <input type="file" id="upload-images" name="upload-images[]" accept=".jpg,.png,.jpeg" class="form__upload-image" multiple><br><br>

                    <div id="form__tags">
                        <h2>Select Tags:</h2>
                        <div id="tags__container">
                            <?php foreach ($tags as $tag): ?>
                                <button type="button" id="tag<?php echo htmlspecialchars($tag->getId()); ?>" class="tag-button <?php echo (bool)str_contains($report->getTags(), $tag->getText()) ? 'active' : ''; ?>" onclick="toggleTag('tag<?php echo htmlspecialchars($tag->getId()); ?>', '<?php echo htmlspecialchars($tag->getText()); ?>');"><?php echo htmlspecialchars($tag->getText()); ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <input type="hidden" name="tags" id="tagsInput" value="<?php echo htmlspecialchars($report->getTags()); ?>">

                    <h2>Location of last sighting</h2>
                    <div id="map"></div>
                    <br>
                    <input type="hidden" id="latitude" name="latitude" value="<?php echo htmlspecialchars($coordinates->getLatitude()); ?>">
                    <input type="hidden" id="longitude" name="longitude" value="<?php echo htmlspecialchars($coordinates->getLongitude()); ?>">

                    <div id="form__submit-button-container">
                        <input type="submit" value="Update Report" class="form__submit-button" onclick="setTagsInput()">
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>

<footer typeof="WPFooter">
    <div id="footer">
        <h3>&copy; 2024 Fepa.com</h3>
        <p>All rights reserved.</p>
    </div>
</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<!--<script src="--><?php //echo BASE_URL; ?><!--/scripts/report-form-map.js"></script>-->
<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
<script>
    function setTagsInput(){
        document.getElementById('tagsInput').value = selectedTags.join(', ');
    }

    // Initialize the map with existing coordinates
    var map = L.map('map').setView([<?php echo htmlspecialchars($coordinates->getLatitude()); ?>, <?php echo htmlspecialchars($coordinates->getLongitude()); ?>], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([<?php echo htmlspecialchars($coordinates->getLatitude()); ?>, <?php echo htmlspecialchars($coordinates->getLongitude()); ?>]).addTo(map);

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });
</script>
<script>
    document.getElementById('report-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        setTagsInput(); // Update the hidden tags input

        const name = document.getElementById('name').value;
        const animalName = document.getElementById('animal_name').value;
        const location = document.getElementById('location').value;
        const animalType = document.getElementById('animal_type').value;
        const description = document.getElementById('description').value;
        const phoneNumber = document.getElementById('phone_number').value;
        const tags = document.getElementById('tagsInput').value;
        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;

        fetch('<?php echo BASE_URL; ?>/index.php/reports/<?php echo htmlspecialchars($report->getId()); ?>', {
            method: 'PUT',
            header: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, animalName, location, animalType, description, phoneNumber, tags, latitude, longitude }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.message);
                } else {
                    window.location.href = '<?php echo BASE_URL; ?>/index.php/reports/<?php echo htmlspecialchars($report->getId()); ?>';
                }
            })

    });

</script>
</body>
</html>
