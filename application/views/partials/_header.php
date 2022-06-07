<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= xss_clean($title); ?> </title>
    <meta name="description" content="<?= xss_clean($description); ?>"/>
    <meta name="keywords" content="<?= xss_clean($keywords); ?>"/>

    <meta name="author" content="AYL Home"/>
    <link rel="shortcut icon" type="image/png" href="<?= base_url(); ?>uploads/icon.png"/>
    <meta property="og:site_name" content="AYL HOME"/>
    <link rel="manifest" href="<?=base_url();?>manifest.json">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="AYL HOME">
    <meta name="apple-mobile-web-app-title" content="AYL HOME">
    <meta name="msapplication-starturl" content="https://aylhome.com/">
