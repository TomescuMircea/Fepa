<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/change-password.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <div id="background">
        <div id="container">
            <h1>Change Password</h1>
            <div id="password-error" style="display:none;">Error: Unable to change password.</div>
            <div id="password-success" style="display:none;">Success: Password changed successfully.</div>
            <form id="container__password-form" action="javascript:void(0);" method="POST">
                <input type="hidden" id="token" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password" required>
                <label for="confirm-password">Confirm New Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
                <input type="submit" value="Change Password">
            </form>
        </div>
    </div>
</main>
<footer>
    <div id="footer">
        <h3>&copy; 2024 Fepa.com</h3>
        <p>All rights reserved.</p>
    </div>
</footer>
<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
<script>
    document.getElementById('container__password-form').addEventListener('submit', function() {
        const token = document.getElementById('token').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        fetch('<?php echo BASE_URL; ?>/index.php/auth/changePassword', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ 'token': token, 'new-password': newPassword, 'confirm-password': confirmPassword }),
        })
            .then(response => response.json().catch(() => {
                throw new Error('Invalid JSON response');
            }))
            .then(data => {
                if (data.message === 'Password changed successfully') {
                    document.getElementById('password-success').style.display = 'block';
                    document.getElementById('password-error').style.display = 'none';
                    window.location.href = '<?php echo BASE_URL; ?>/index.php/login';
                } else {
                    document.getElementById('password-error').style.display = 'block';
                    document.getElementById('password-error').innerText = data.message;
                    document.getElementById('password-success').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('password-error').style.display = 'block';
                document.getElementById('password-error').innerText = 'An unexpected error occurred.';
                document.getElementById('password-success').style.display = 'none';
            });
    });
</script>
</body>
</html>
