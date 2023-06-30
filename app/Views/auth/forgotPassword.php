<?php $this->layout('templates/base', ['title' => 'Forgot Password']) ?>
<form action="/forgot-password" method="post">
    <?php if (isset($error)) :
        echo $error;
    endif; ?>

    <label for="email">Email:</label>
    <input type="text" name="email" id="email">

    <input type="submit" value="Reset Password">
</form>
