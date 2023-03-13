<h1>Login</h1>
<form action="/login" method="post">
    <?= $csrfToken ?>

    <?php if ($error !== '') {
        echo $error;
    } ?>

    <label for="email">Email:</label>
    <input type="text" name="email" id="email">

    <label for="password">Password:</label>
    <input type="password" name="password" id="password">

    <input type="submit" value="Login">
</form>
