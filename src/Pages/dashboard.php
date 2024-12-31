<!DOCTYPE html>
<html lang="en" vocab="http://schema.org/" typeof="WebPage">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fepa admin page">
    <meta name="keywords" content="HTML, CSS, JavaScript, admin, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei">
    <meta name="author" content="Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Administrator dashboard</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/admin.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
</head>
<body vocab="http://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>
<main typeof="WebPageElement">
    <div id="container">
        <h1>Admin Dashboard</h1>

        <section typeof="Table">
            <h2>Current Users</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user->getId()); ?></td>
                        <td><?php echo htmlspecialchars($user->getUsername()); ?></td>
                        <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                        <td>
                            <button class="remove-button" onclick="deleteUser(<?php echo htmlspecialchars($user->getId()); ?>)">
                                Remove User
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>

        <section typeof="Table">
            <h2>List of Reports by ID</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report->getId()); ?></td>
                        <td>
                            <button class="default-button" onclick="seeDetails(<?php echo htmlspecialchars($report->getId()); ?>)">See details</button>
                            <button class="default-button" onclick="editReport(<?php echo htmlspecialchars($report->getId()); ?>)">Edit</button>
                            <button class="remove-button" onclick="deleteReport(<?php echo htmlspecialchars($report->getId()); ?>)">Remove report</button>
                            <button class="close-button" onclick="closeReport(<?php echo htmlspecialchars($report->getId()); ?>)">Close report</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </section>
    </div>
</main>

<footer id="footer" typeof="WPFooter">
    <h3>© 2024 Fepa.com</h3>
    <p>All rights reserved.</p>
</footer>

<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
<script>
    function deleteUser(userId) {
        if (confirm('Are you sure you want to remove this user?')) {
            fetch(`<?php echo BASE_URL; ?>/index.php/users/${userId}`, {
                method: 'DELETE'
            }).then(response => {
                if (response.ok) {
                    alert('User removed successfully');
                    location.reload();
                } else {
                    alert('Failed to remove user');
                }
            });
        }
    }

    function editReport(reportId) {
        window.location.href = `<?php echo BASE_URL; ?>/index.php/reports/editReport/${reportId}`;
    }

    function deleteReport(reportId) {
        if (confirm('Are you sure you want to remove this report?')) {
            fetch(`<?php echo BASE_URL; ?>/index.php/reports/${reportId}`, {
                method: 'DELETE'
            }).then(response => {
                if (response.ok) {
                    alert('Report removed successfully');
                    location.reload();
                } else {
                    alert('Failed to remove report');
                }
            });
        }
    }

    function closeReport(reportId) {
        if (confirm('Are you sure you want to close this report?')) {
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

    function seeDetails(reportId) {
        window.location.href = `<?php echo BASE_URL; ?>/index.php/reports/${reportId}`;
    }
</script>
</body>
</html>
