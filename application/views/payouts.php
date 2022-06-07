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
    <style>
      .dataTables_filter{
        width:auto !important;
      }
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
      
        <?php $this->load->view("partials/_sidebar"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Ödemeler</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php if(!is_admin()):
                    if($user->balance>0): ?>
                        <?php if($user->bank!=null && $user->iban!=null && $user->iban_name!=null ): ?>
                        <?php echo form_open('payouts-request-post', 'id="demo-form2" data-parsley-validate class="form-horizontal form-label-left"'); ?>
                          <input type="hidden" name="user_id" value="<?=$user->id;?>">
                          <input type="hidden" name="bank" value="<?=$user->bank;?>">
                          <input type="hidden" name="iban" value="<?=$user->iban;?>">
                          <input type="hidden" name="iban_name" value="<?=$user->iban_name;?>">
                          <input type="hidden" name="user_name" value="<?=$user->first_name." ".$user->last_name;?>">
                          <div class="form-group">
                            <label class="col-sm-3 control-label">Miktar</label>
                            <div class="col-sm-6">
                              <div class="input-group">
                                <input id="amount" name="amount" type="text" class="form-control">
                                <span class="input-group-btn">
                                  <button type="submit" class="btn btn-primary">Ödeme Talebi Oluştur</button>
                                </span>
                              </div>
                            </div>
                          </div>
                        <?php echo form_close(); ?>
                      <?php else: ?>
                        <div class="alert alert-info alert-dismissible fade in" role="alert">
                            
                            <strong>Lütfen!</strong> Kişisel bilgilerinizi güncelleyin.
                        </div>
                      <?php endif; ?>
                      <?php endif; ?>
                      <?php endif; ?>
                      

                    
                    <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>Alıcı</th>
                            <th>Miktar</th>
                            <th>İstek Tarihi</th>
                            <th>Ödeme Tarihi</th>
                            <th>Durum</th>
                            <th>Detaylar</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php foreach($payouts as $item): ?>
                        <tr>
                            <td><?=$item->user_name?></td>
                            <td><?=price_formatted($item->amount)?> <i class="fa fa-turkish-lira"></i></td>
                            <td><?=$item->created_at?></td>
                            <td><?php if($item->updated_at==NULL){echo "Bekleniyor.."; }else{ echo $item->updated_at;}?></td>
                            
                            <td><?php if($item->status==0){
                              if(is_admin()){
                                echo form_open('payout-approve');
                                ?>
                                <input type="hidden" name="id" value="<?=$item->id;?>">
                                <input type="hidden" name="amount" value="<?=$item->amount;?>">
                                <input type="hidden" name="user_id" value="<?=$item->user_id;?>">
                                <button type="submit" class="btn btn-primary">Onayla</button>
                                <?php
                                echo form_close();
                              }else{ echo "Bekleniyor..";
                              echo form_open('payout-cancel');?>
                                <input type="hidden" name="id" value="<?=$item->id;?>">
                                <button type="submit" class="btn btn-danger">Sil</button>
                            <?php echo form_close();  }
                            }else{echo "Tamamlandı.";}?></td>
                            <td><?php echo $item->bank.'<br>'.$item->iban.'<br>'.$item->iban_name;?></td>
                        </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>
					
					
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
    <script>
      $('#amount').priceFormat({
        prefix: '',
    thousandsSeparator: '',
    clearOnEmpty: true
});
window.onload = function () {
    var textbox = document.getElementById("amount");
    var maxVal = <?=price_formatted($max);?>
    
    addEvent(textbox, "keyup", function () {
        var thisVal = +this.value;
        
        if (thisVal > maxVal) {
            this.value=maxVal;
        }
    });
};
function addEvent(element, event, callback) {
    if (element.addEventListener) {
        element.addEventListener(event, callback, false);
    } else if (element.attachEvent) {
        element.attachEvent("on" + event, callback);
    } else {
        element["on" + event] = callback;
    }
}
    </script>
    <!-- Custom Theme Scripts -->
    <script src="<?= base_url(); ?>assets/build/js/custom.min.js"></script>
  </body>
</html>