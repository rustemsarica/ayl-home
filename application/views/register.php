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
        
      <div id="" class="">
          <section class="login_content">
          <?php echo form_open('register-post'); ?>
              <h1>Hesap Oluştur</h1>
              <?php $this->load->view('partials/_messages'); ?>
              <div>
                <input type="text" name="first_name" class="form-control" placeholder="Ad" required="" />
              </div>
              <div>
                <input type="text" name="last_name" class="form-control" placeholder="Soyad" required="" />
              </div>
              <div>
                <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required="" />
              </div>
              <div>
                <input type="email" name="email" class="form-control" placeholder="E-mail" required="" />
              </div>
              <div>         
                <input type="text" name="phone_number" class="form-control" placeholder="Telefon" data-inputmask="'mask' : '(999) 999-9999'">
              </div>
              <div>
                <input type="password" name="password" class="form-control" placeholder="Şifre" required="" />
              </div>
              <div>
                <input type="password" name="confirm_password" class="form-control" placeholder="Şifre" required="" />
              </div>
              <div>
                <button type="submit" class="btn btn-default submit">Kayıt Ol</button>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <p class="change_link">Zaten Hesabınız Var Mı?
                  <a href="<?=generate_url('login')?>" class="to_register"> Üye Girişi </a>
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
  <script src="<?= base_url(); ?>assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
</html>
