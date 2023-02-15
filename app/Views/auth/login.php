<h1>Login</h1>
<form action="/login" method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    
    <?= $error ?>

    <label for="email">Email:</label>
    <input type="text" name="email" id="email">

    <label for="password">Password:</label>
    <input type="password" name="password" id="password">

    <input type="submit" value="Login">
</form>
