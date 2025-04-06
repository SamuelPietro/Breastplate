<?php $this->layout('templates/base', ['title' => 'Home']) ?>
<h1>Welcome, <?= htmlspecialchars($_SESSION['usr_name']) ?></h1>
