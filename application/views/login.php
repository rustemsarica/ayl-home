<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="tr">
  <head>
  <?php $this->load->view("partials/_header"); ?>

    <!-- Bootstrap -->
    <link href="<?= base_url(); ?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->

    <!-- NProgress -->
    <link href="<?= base_url(); ?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?= base_url(); ?>assets/vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?= base_url(); ?>assets/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>
      
      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
          
          <?php echo form_open('login-post'); ?>
              <h1>Üye Girişi</h1>
              <?php $this->load->view('partials/_messages'); ?>
              <div>
                <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required="" />
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Şifre" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit" >Giriş Yap</button>
                <a class="reset_pass" href="#">Şifremi unuttum!</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">
                  <a href="<?=generate_url('register')?>" class="to_register">Hesap Oluştur </a>
                </p>

                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-paw"></i> <?=get_general_settings()->company_name ?></h1>
                  <p>©<?= date("Y");?> Tüm hakları saklıdır. <a rel="noreferrer" target="_blank" href="https://instagram.com/rustemsarica">RS</a></p>
                </div>
              </div>
            <?php echo form_close(); ?>
          </section>
        </div>

      </div>
    </div>
  </body>
</html>
