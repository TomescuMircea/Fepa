<!DOCTYPE html>
<html lang="en" vocab="https://schema.org/" typeof="WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fepa forget username/password page">
    <meta name="keywords" content="HTML, CSS, JavaScript, forget, username, password, fepa">
    <meta name="author" content="Tomescu Mircea-Andrei, Burcă Flavian">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Username/Password</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/base.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/forget-up.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/nav.css" type="text/css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles/footer.css" type="text/css">
</head>

<body vocab="https://schema.org/" typeof="WebPage">
<?php include 'header.php'; ?>

<main typeof="WebPageElement">
    <div id="background">
        <div id="container">
            <h1 property="headline">Forget Username</h1>
            <div id="username-error" style="display:none;">Unable to recover username.</div>
            <div id="username-success" style="display:none;">Username sent to your email.</div>
            <form id="container__username-form" action="javascript:void(0);" method="POST">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <input type="submit" value="Send">
            </form>
            <br><br>
            <h1 property="headline">Forget Password</h1>
            <div id="password-error" style="display:none;">Unable to recover password.</div>
            <div id="password-success" style="display:none;">Password reset link sent to your email.</div>
            <form id="container__password-form" action="javascript:void(0);" method="POST">
                <label for="pass-email">Email:</label>
                <input type="email" id="pass-email" name="pass-email" required>
                <input type="submit" value="Send">
            </form>
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
<script>
    document.getElementById('container__username-form').addEventListener('submit', function() {
        const email = document.getElementById('email').value;

        fetch('<?php echo BASE_URL; ?>/index.php/auth/recoverUsername', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email }),
        })
            .then(response => response.json().catch(() => {
                throw new Error('Invalid JSON response');
            }))
            .then(data => {
                if (data.message === 'Username sent to your email') {
                    document.getElementById('username-success').style.display = 'block';
                    document.getElementById('username-error').style.display = 'none';
                } else {
                    document.getElementById('username-error').style.display = 'block';
                    document.getElementById('username-error').innerText = data.message;
                    document.getElementById('username-success').style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('username-error').style.display = 'block';
                document.getElementById('username-error').innerText = 'An unexpected error occurred.';
                document.getElementById('username-success').style.display = 'none';
            });
    });

    document.getElementById('container__password-form').addEventListener('submit', function() {
        const email = document.getElementById('pass-email').value;

        fetch('<?php echo BASE_URL; ?>/index.php/auth/recoverPassword', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email }),
        })
            .then(response => response.json().catch(() => {
                throw new Error('Invalid JSON response');
            }))
            .then(data => {
                if (data.message === 'Password reset link sent to your email') {
                    document.getElementById('password-success').style.display = 'block';
                    document.getElementById('password-error').style.display = 'none';
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
