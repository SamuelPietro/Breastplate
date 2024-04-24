<?php $this->layout('templates/base', ['title' => 'Login']) ?>
<form method="post">
    <?php if (isset($csrf)) :
        echo $csrf['generate'];
    endif; ?>
    <?php if (isset($error)) :
        echo $error;
    endif; ?>

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required minlength="8">

    <input type="checkbox" name="remember" id="remember">
    <label for="remember">Remember:</label>

    <input type="submit" value="Login">
</form>
