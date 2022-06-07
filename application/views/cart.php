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
.skin-2 .num-in {
	
	height: 40px;
  float: left;
}

.skin-2 .num-in	button {
  width: 40px;
  display: block;
  height: 40px;
  float: left;
  position: relative;
}

.skin-2 .num-in button:before, .skin-2 .num-in button:after {
  content: '';
  position: absolute;
  background-color: #667780;
  height: 2px;
  width: 10px;
  top: 50%;
  left: 50%;
  margin-top: -1px;
  margin-left: -5px;
}

.skin-2 .num-in button.plus:after {
  transform: rotate(90deg);
}

.skin-2 .num-in input {
		float: left;
		width: 20%;
		height: 40px;
		border: none;
		text-align: center;
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
                    <h2>Sepet</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    
                  <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                            <th>#</th>
                          <th>Resim</th>
                          <th>Başlık</th>
                          <th>Opsiyonlar</th>
                          <th>Birim Fiyat</th>
                          <th>Adet</th>
                          <th>Toplam</th>
                          <th>İşlem</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php $i=1; foreach($cart_items as $item): ?>
                        <tr>
                            <td><?=$i?></td>
                          <td style="text-align:center;"><img style="width:60px;" src="<?=$item->product_image;?>"></td>
                          <td><?=$item->title;?></td>
                          <td><?=$item->options;?></td>
                          <td><?=price_formatted($item->unit_price)?> <i class="fa fa-turkish-lira"></i></td>
                          <td>
                          <div style="max-width:120px;" class="cart-item-quantity">
                            <div class="number-spinner">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-spinner-minus" data-cart-item-id="<?php echo $item->cart_item_id; ?>" data-dir="dwn"><i class="fa fa-minus"></i></button>
                                    </span>
                                    <input type="text" id="q-<?php echo $item->cart_item_id; ?>" class="form-control text-center" value="<?php echo $item->quantity; ?>" data-product-id="<?php echo $item->product_id; ?>" data-cart-item-id="<?php echo $item->cart_item_id; ?>">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default btn-spinner-plus" data-cart-item-id="<?php echo $item->cart_item_id; ?>" data-dir="up"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            </div>
                            </td>
                          <td><?=price_formatted($item->total_price)?> <i class="fa fa-turkish-lira"></i></td>
                          <td>
                          <div class="cart-item-details">
                            <?php echo form_open('remove-from-cart');?>
                              <input type="hidden" name="cart_item_id" value="<?php echo $item->cart_item_id; ?>">
                            <button type="submit" class="btn btn-md btn-default btn-outline-gray btn-cart-remove btn-item-remove-to-cart" ><i class="fa fa-close"></i> Kaldır</button>
                          <?php echo form_close(); ?>
                          </div>
                          </td>
                        </tr>
                        
                        <?php $i++; endforeach; ?>
                      </tbody>
                    </table>
					<hr>
					          <?php echo form_open_multipart('create-order-post','id="demo-form2" class="form-horizontal form-label-left"');?>
                    
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Alıcı Ad-Soyad</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" name="buyer_name" id="first-name" required="required" class="form-control col-md-7 col-xs-12"><span class="fa fa-user form-control-feedback right" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alıcı Telefon</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" name="buyer_phone" class="form-control" data-inputmask="'mask' : '(999) 999-9999'">
                            <span class="fa fa-phone form-control-feedback right" aria-hidden="true"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Alıcı Adres</label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                            <input type="text" class="form-control" name="address">
                            <span class="fa fa-map-marker form-control-feedback right" aria-hidden="true"></span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                            <div class="col-md-4 col-sm-4 col-xs-12">
                              <div class="col-md-6 col-sm-6 col-xs-6">
                                <select  name="city" class="form-control" onchange="get_towns(this.value);">
                                  <option value="">İl</option>
                                  <?php foreach($cities as $city): ?>
                                  <option value="<?=$city->il;?>"><?=$city->il;?></option>
                                  <?php endforeach; ?>
                                </select>
                              </div>
                              <div id="get_towns_container" class="col-md-6 col-sm-6 col-xs-6">
                                <select id="select_towns" name="town" class="form-control">
                                  <option value="">İlçe</option>
                                  
                                </select>
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
						              <button class="btn btn-primary" type="reset">Reset</button>
                          <button type="submit" class="btn btn-success">Sipariş Oluştur</button>
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
    <!-- jquery.inputmask -->
    <script src="<?= base_url(); ?>assets/vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/jszip/dist/jszip.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/jquery.priceformat.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="<?= base_url(); ?>assets/build/js/custom.min.js"></script>
    <script>
        


function update_number_spinner(btn) {
    var btn = btn,
        oldValue = btn.closest('.number-spinner').find('input').val().trim(),
        newVal = 0;
    if (btn.attr('data-dir') == 'up') {
        newVal = parseInt(oldValue) + 1;
    } else {
        if (oldValue > 1) {
            newVal = parseInt(oldValue) - 1;
        } else {
            newVal = 1;
        }
    }
    btn.closest('.number-spinner').find('input').val(newVal);
}



$(document).on("input paste change", ".cart-item-quantity .number-spinner .input-group input", function () {
    var data = {
        'product_id': $(this).attr("data-product-id"),
        'cart_item_id': $(this).attr("data-cart-item-id"),
        'quantity': $(this).val()
    };
    data['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
    $.ajax({
        type: "POST",
        url: "<?=base_url();?>update-cart-product-quantity",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
});
$(document).on("click", ".cart-item-quantity .btn-spinner-minus", function () {
    update_number_spinner($(this));
    var cart_id = $(this).attr("data-cart-item-id");
    if ($("#q-" + cart_id).val() != 0) {
        $("#q-" + cart_id).change();
    }
});
$(document).on("click", ".cart-item-quantity .btn-spinner-plus", function () {
    update_number_spinner($(this));
    var cart_id = $(this).attr("data-cart-item-id");
    $("#q-" + cart_id).change();
});

function get_towns(val) {
  $('#select_towns' ).children('option').remove();
    $('#get_towns_container' ).hide();
    if ($('#select_towns' ).length) {
        $('#select_towns' ).children('option').remove();
        $('#get_towns_container' ).hide();
    }
    var data = {
        "il": val
    };
    data['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
    $.ajax({
        type: "POST",
        url:  "<?=base_url();?>get-towns",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                document.getElementById("select_towns").innerHTML = obj.content;
                $('#get_towns_container').show();
            } else {
                document.getElementById("select_towns").innerHTML = "";
                $('#get_towns_container').hide();
            }
            
        }
    });
}

    </script>
  </body>
</html>