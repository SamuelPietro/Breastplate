<?php $this->layout('templates/base', ['title' => 'Login']) ?>
<form action="/login" method="post">
    <?php if (isset($csrf)) :
        echo $csrf['generate'];
    endif; ?>
    <?php if (isset($error)) :
        echo $error;
    endif; ?>

    <label for="email">Email:</label>
    <input type="text" name="email" id="email">

    <label for="password">Password:</label>
    <input type="password" name="password" id="password">

    <input type="submit" value="Login">
</form>
