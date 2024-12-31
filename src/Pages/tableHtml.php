<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics Table</title>
    <link rel="stylesheet" href="../../styles/table-stats.css" type="text/css">
</head>
<body>
<h1>Statistics Overview</h1>
<table>
    <thead>
    <tbody>
        <th>Category</th>
        <th>Value</th>
        <th>Percentage</th>
        <?php

        use Src\Repositories\TagRepository;
        use Src\Repositories\ReportRepository;
        use Src\Repositories\AnimalTypeRepository;

        $tagRepository = new TagRepository();

        $tags = $tagRepository->getTags();
        $tagsArray = [];
        foreach ($tags as $tag) {
            $tagsArray[] = $tag->getText();
        }

        foreach ($tagsArray as $tag) {
            echo "<th>$tag</th>";
        }

        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $reportRepository = new ReportRepository();
        $animalTypeRepository = new AnimalTypeRepository();
        $types = $animalTypeRepository->getAnimalTypes();
        $totalNoOfReports = count($reportRepository->getReports());

        foreach ($types as $type) {
            $noOfReports = count($reportRepository->getReportsOfType($type->getId()));
            $typeText = $type->getType();
            echo '<tr>';
            echo "<td>$typeText</td>";
            echo "<td>$noOfReports</td>";
            if ($totalNoOfReports == 0) {
                $percentage = 0;
            } else {
                $percentage = number_format(($noOfReports / $totalNoOfReports) * 100.00, 2);
            }
            $noOfReportsOfEachType = [];
            foreach ($tags as $tag) {
                $noOfReportsOfEachType[] = $this->reportRepository->getNumberOfTypeAnimalWithTag($type->getId(), $tag->getId());
            }
            foreach ($noOfReportsOfEachType as $noOfReports) {
                echo "<td> $noOfReports</td>";
            }
            echo '</tr>';
        }
        ?>
        </tbody>
</table>
</body>
</html>
