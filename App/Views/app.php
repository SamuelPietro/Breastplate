<?php $this->layout('templates/base', ['title' => $_ENV['APP_NAME']]) ?>
<h1>Welcome, <?= htmlspecialchars($_SESSION['usr_name']) ?></h1>
