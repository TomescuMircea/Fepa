<!DOCTYPE html>
<html lang="en" vocab="http://schema.org/" typeof="WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fepa sign up page">
    <meta name="keywords" content="HTML, CSS, JavaScript, signup, sign up, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei, Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title property="name">Sign up</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/signup.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
</head>

<body vocab="http://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>

<main typeof="WebPageElement">
    <div id="background">
        <div id="container" typeof="ItemList">
            <h1 property="headline">Sign up</h1>
            <div id="container__duplicate-error">
                Email or username already exists!
            </div>
            <div id="container__password-error">
                Passwords do not match!
            </div>
            <form id="container__form" action="javascript:void(0);" method="POST" enctype="multipart/form-data">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <label for="passwordConfirm">Confirm password:</label>
                <input type="password" id="passwordConfirm" name="passwordConfirm" required><br><br>

                <input type="submit" value="Sign up">
            </form>
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
    document.getElementById('container__form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        const email = document.getElementById('email').value;
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('passwordConfirm').value;

        if (password !== passwordConfirm) {
            document.getElementById('container__password-error').style.display = 'block';
            return;
        }

        fetch('<?php echo BASE_URL; ?>/index.php/auth/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, username, password }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    if (data.message === 'Username or email already exists') {
                        document.getElementById('container__duplicate-error').style.display = 'block';
                    } else {
                        console.error('Error:', data.message);
                    }
                } else {
                    window.location.href = '../index.php/confirmationNotice';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>
</body>

</html>
