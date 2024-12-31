<!DOCTYPE html>
<html lang="en-US" vocab="https://schema.org/" typeof="WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fepa login page">
    <meta name="keywords" content="HTML, CSS, JavaScript, login, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei, Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/login.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
</head>

<body vocab="https://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>

<main typeof="WebPageElement">
    <div id="background">
        <div id="container">
            <h1 property="headline">Login</h1>
            <div id="container__error" style="display: none;"></div>
            <form id="container__form" action="javascript:void(0);" method="POST" enctype="multipart/form-data">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                <input type="submit" value="Login">
            </form>
            <p>
                <a href="<?php echo BASE_URL; ?>/index.php/credentials-recover">Forget username or password?</a>
            </p>
            <p>Don't have an account? <a href="<?php echo BASE_URL; ?>/index.php/register">Sign up</a></p>
        </div>
    </div>
</main>

<footer typeof="WPFooter">
    <div id="footer">
        <h3>© 2024 Fepa.com</h3>
        <p>All rights reserved.</p>
    </div>
</footer>

<script src="<?php echo BASE_URL; ?>/scripts/nav.js"></script>
<script>
    document.getElementById('container__form').addEventListener('submit', function() {

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        fetch('<?php echo BASE_URL; ?>/index.php/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, password }),
        })
            .then(response => response.json().catch(() => {
                throw new Error('Invalid JSON response');
            }))
            .then(data => {
                if (data.message === 'Login successful') {
                    window.location.href = '../index.php';
                } else {
                    document.getElementById('container__error').style.display = 'block';
                    document.getElementById('container__error').innerText = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('container__error').style.display = 'block';
                document.getElementById('container__error').innerText = 'An unexpected error occurred.';
            });
    });
</script>
</body>

</html>
