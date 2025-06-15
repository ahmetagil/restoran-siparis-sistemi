<?php // C:\xampp\htdocs\restoran\includes\header.php
session_start();
define('BASE_URL', '/restoran/');
require_once __DIR__ . '/db.php';
?>
<!doctype html>
<html lang="tr" data-bs-theme="dark">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lezzet Durağı Elit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-dark text-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-black shadow-lg">
    <div class="container">
        <a class="navbar-brand logo" href="<?php echo BASE_URL; ?>index.php"><i class="fas fa-utensils"></i> LEZZET DURAĞI</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>index.php"><i class="fas fa-th-large me-1"></i> Masalar</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>mutfak.php"><i class="fas fa-fire-burner me-1"></i> Mutfak</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>yonetim/index.php"><i class="fas fa-tachometer-alt me-1"></i> Yönetim Paneli</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="container flex-grow-1 my-5">
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>