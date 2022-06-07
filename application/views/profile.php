<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="tr">
  <head>
  <?php $this->load->view("partials/_header"); ?>
    <!-- Bootstrap -->
    <link href="<?= base_url(); ?>assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?= base_url(); ?>assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?= base_url(); ?>assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?= base_url(); ?>assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="<?= base_url(); ?>assets/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url(); ?>assets/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?= base_url(); ?>assets/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
      
        <?php $this->load->view("partials/_sidebar"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>

            <?php $this->load->view("partials/_messages"); ?>
            <div class="row">
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Bilgilerim</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    
                    <?php echo form_open_multipart('profile-post', 'id="demo-form2" data-parsley-validate class="form-horizontal form-label-left"'); ?>
                        <input type="hidden" name="id" value="<?=$user->id?>">
                        <?php if($user->img!=""):?>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image1">Yüklü Görsel</label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <img id="image1" style="width:100px;" src="<?=base_url().$user->img?>" alt="">
                            </div>
                        </div>
                        <?php endif; ?>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="image">Resim 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="file" name="img" id="image" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first_name">Ad
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input value="<?=$user->first_name?>" type="text" id="first_name" name="first_name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last_name">Soyad
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input value="<?=$user->last_name?>" type="text" id="last_name" name="last_name" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Kullanıcı Adı
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input value="<?=$user->username?>" type="text" id="username" name="username" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">E-mail
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input value="<?=$user->email?>" type="email" id="email" name="email" required="required" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="phone_number">Telefon
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input value="<?=$user->phone_number?>" type="text" id="phone_number" name="phone_number" required="required" data-inputmask="'mask' : '(999) 999-9999'" class="form-control col-md-7 col-xs-12">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="bank" class="control-label col-md-3 col-sm-3 col-xs-12">Banka </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <select required="required" class="form-control col-md-7 col-xs-12" name="bank" id="bank">
                                <option value="">Banka</option>
                                <option <?php if($user->bank=="AKBANK T.A.Ş."){ echo 'selected';} ?> value="AKBANK T.A.Ş.">AKBANK T.A.Ş.</option>
                                <option <?php if($user->bank=="DENİZBANK A.Ş."){ echo 'selected';} ?> value="DENİZBANK A.Ş.">DENİZBANK A.Ş.</option>
                                <option <?php if($user->bank=="HSBC BANK A.Ş."){ echo 'selected';} ?> value="HSBC BANK A.Ş.">HSBC BANK A.Ş.</option>
                                <option <?php if($user->bank=="ING BANK A.Ş."){ echo 'selected';} ?> value="ING BANK A.Ş.">ING BANK A.Ş.</option>
                                <option <?php if($user->bank=="QNB FİNANSBANK A.Ş."){ echo 'selected';} ?> value="QNB FİNANSBANK A.Ş.">QNB FİNANSBANK A.Ş.</option>
                                <option <?php if($user->bank=="ZİRAAT BANKASI A.Ş."){ echo 'selected';} ?> value="ZİRAAT BANKASI A.Ş.">ZİRAAT BANKASI A.Ş.</option>
                                <option <?php if($user->bank=="GARANTİ BANKASI A.Ş."){ echo 'selected';} ?> value="GARANTİ BANKASI A.Ş.">GARANTİ BANKASI A.Ş.</option>
                                <option <?php if($user->bank=="HALK BANKASI A.Ş."){ echo 'selected';} ?> value="HALK BANKASI A.Ş.">HALK BANKASI A.Ş.</option>
                                <option <?php if($user->bank=="İŞ BANKASI A.Ş."){ echo 'selected';} ?> value="İŞ BANKASI A.Ş.">İŞ BANKASI A.Ş.</option>
                                <option <?php if($user->bank=="VAKIFLAR BANKASI T.A.O."){ echo 'selected';} ?> value="VAKIFLAR BANKASI T.A.O.">VAKIFLAR BANKASI T.A.O.</option>
                                <option <?php if($user->bank=="YAPI VE KREDİ BANKASI A.Ş."){ echo 'selected';} ?> value="YAPI VE KREDİ BANKASI A.Ş.">YAPI VE KREDİ BANKASI A.Ş.</option>
                            </select>
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="iban" class="control-label col-md-3 col-sm-3 col-xs-12">İban </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input value="<?=$user->iban?>" type="text" name="iban" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : 'TR99-9999-9999-9999-9999-99'">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="iban_name" class="control-label col-md-3 col-sm-3 col-xs-12">İban İsim</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input required="required" value="<?=$user->iban_name?>" id="iban_name" class="form-control col-md-7 col-xs-12" type="text" name="iban_name">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">Şifre</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input required="required" id="password" class="form-control col-md-7 col-xs-12" type="password" name="password">
                        </div>
                      </div>
                      
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                          
						              <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Kaydet</button>
                        </div>
                      </div>

                    <?php echo form_close(); ?>
                  </div>
                </div>
              </div>

              


              
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <?php $this->load->view("partials/_footer"); ?>
        <!-- /footer content -->
      </div>
    </div>
    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?= base_url(); ?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?= base_url(); ?>assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?= base_url(); ?>assets/vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="<?= base_url(); ?>assets/vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="<?= base_url(); ?>assets/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/jszip/dist/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/jquery.priceformat.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script>
      $('#price').priceFormat({
          prefix: '',
          centsSeparator: '.',
          thousandsSeparator: ','
      });
    </script>
    <!-- Custom Theme Scripts -->
    <script src="<?= base_url(); ?>assets/build/js/custom.min.js"></script>
  </body>
</html>