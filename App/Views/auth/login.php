<?php $this->layout('templates/base', ['title' => 'Login']) ?>
<form method="post" class="form-container">
    <?php if (isset($csrf)) :
        echo $csrf['generate'];
    endif; ?>

    <h1>Access my account</h1>

    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
    </div>

    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required minlength="8">
    </div>

    <div class="form-group form-check">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember me</label>
    </div>
    <?php if (isset($error)) :
        echo '<div class="error">' . $error . '</div>';
    endif; ?>
    <div class="form-group">
        <input type="submit" value="Access" class="btn btn-primary">
    </div>

    <div class="form-group form-links">
        <a href="/auth/forgot-password">Forgot your password?</a>
        <a href="/auth/register">Create an account</a>
    </div>
</form>