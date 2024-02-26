
<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/global.css">
    <title>Social App</title>
</head>
<body>
<h1>Welcome to Social App</h1>

<?php if (isset($_SESSION['error'])): ?>
    <p style="color: red;"><?php echo $_SESSION['error']; ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
<form name="login" action="includes/login.php" method="post">
    <div id="input">
        <label for="email">Email:</label>
        <input id="email" name="email" value="" type="email"/>
    </div>
    <div id="input">
        <label for="password">Password:</label><input id="password" name="password" value="" type="password"/>
    </div>
    <button type="submit">Submit</button>
</form>

<script>
    const toggleMessage = () => {
        let messageElement = document.getElementById("message");
        messageElement.style.display = (messageElement.style.display === "none") ? "block" : "none";
    }

    const onSubmit = ({email: {value: email}, password: {value: password}}) => {
        console.log({email, password})
    }
</script>
</body>
</html>
