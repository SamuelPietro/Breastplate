<!DOCTYPE html>
<html lang="<?= $_ENV['LANG'] ?>" dir="ltr" id="root">

<head>
    <?php $this->section('head'); ?>
    <meta charset="utf-8">
    <meta name='viewport' content='width=device-width, initial-scale=1'/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv='X-UA-Compatible' content='IE=edge'/>
    <meta name="title" content="<?= $_ENV['APP_NAME'] ?>">
    <meta name="description" content="<?= $_ENV['APP_DESC'] ?>">
    <meta name="keywords" content="<?= $_ENV['APP_KEYS'] ?>">
    <meta name="robots" content="index, follow">
    <meta name="language" content="<?= $_ENV['APP_LANGUAGE'] ?>">
    <meta name="revisit-after" content="7 days">
    <meta name="author" content="<?= $_ENV['APP_AUTHOR'] ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico"/>
    <title><?= $_ENV['APP_NAME'] ?> | <?= $title ?? '' ?></title>
    <!-- Styles -->
    <!-- Scripts -->

</head>
<body>
<h1><?= $title ?? '' ?></h1>
<?= $this->section('content') ?>
</body>
</html>
