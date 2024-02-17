<?php $this->layout('templates/base', ['title' => 'Reset Password']) ?>
<form method="post">
    <?php if (isset($error)) :
        echo $error;
    endif; ?>

    <label for="password">New Password:</label>
    <input type="password" name="password" id="password">

    <input type="submit" value="Set New Password">
</form>
