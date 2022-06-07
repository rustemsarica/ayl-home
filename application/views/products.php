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
                    <h2>Ürünler</h2>
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
                          <th>Resim</th>
                          <th>Başlık</th>
                          <th>Birim Fiyat</th>
                          <th>İşlemler</th>
                          <?php if(is_admin()): ?><th>Yönetici</th> <?php endif; ?>
                        </tr>
                      </thead>
                      <tbody>
                          <?php foreach($products as $item): ?>
                        <tr>
                          <td style="text-align:center;"><img style="width:100px;" src="<?=$item->img;?>"></td>
                          <td><?=$item->title;?></td>
                          <td><?=price_formatted($item->price);?> <i class="fa fa-turkish-lira"></i></td>
                          <td>
                          
                            <?php echo form_open(get_product_form_data($item)->add_to_cart_url, ['id' => 'form_add_cart', 'style'=>'max-width:250px;']); ?>
                              <input type="hidden" name="product_id" value="<?php echo $item->id; ?>">
                              
                              <div class="product-add-to-cart-container">
                                <div class="row number-spinner">
                                  <label style="width:30%;" class="col-md-2" for="quantity">Adet</label>
                                  <div style="width:70%;" class="col-md-4 input-group">
                                    <span class="input-group-btn">
                                        <button <?php if($item->stock==0){echo "disabled";} ?> type="button" class="btn btn-default btn-spinner-minus" data-dir="dwn"><i class="fa fa-minus"></i></button>
                                    </span>
                                    <input <?php if($item->stock==0){echo "disabled";} ?> id="quantity" type="text" class="form-control text-center" name="product_quantity" value="1" max="<?php echo $item->stock; ?>">
                                    <span class="input-group-btn">
                                        <button <?php if($item->stock==0){echo "disabled";} ?> type="button" class="btn btn-default btn-spinner-plus" data-dir="up"><i class="fa fa-plus"></i></button>
                                    </span>
                                  </div>
                                </div>
                              </div>          
                            
                              <div class="row">
                                <label style="width:30%;" class="col-md-2" for="options">Açıklama</label>
                                <div style="width:70%;" class="col-md-4 input-group">
                                <textarea <?php if($item->stock==0){echo "disabled";} ?> id="options" name="options" class="form-control" rows="2" placeholder="Beden,renk ve açıklama"></textarea>
                                </div>
                              </div>
                            
                            
                              <div class="">
                                <button <?php if($item->stock==0){echo "disabled";} ?> type="submit" class="btn btn-primary"><?php if($item->stock==0){echo "Stokta Kalmadı";}else{echo "Sepete Ekle";} ?></button>
                              </div>
                            
                            <?php echo form_close();  ?>
                          </td>
                          <?php if(is_admin()): ?>
                          <td>
                            <a class="btn btn-default" href="<?=generate_url('products/edit-product/'.$item->id);?>">Düzenle</a>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target=".<?=$item->id;?>">Sil</button>
                           
                           
                          </td>
                          <?php endif; ?>
                        </tr>
                        <?php if(is_admin()): ?>
                        <div class="modal fade <?=$item->id;?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel2">Ürün Sil</h4>
                                </div>
                                <div class="modal-body">
                                  
                                  <h4>Ürünü silmek istediğinize emin misiniz?</h4>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <button type="button" class="btn btn-danger"  onclick="remove_product('<?=$item->id;?>')">Sil</button>
                                </div>

                              </div>
                            </div>
                          </div>
                          <?php endif; ?>
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

    <!-- Custom Theme Scripts -->
    <script src="<?= base_url(); ?>assets/build/js/custom.min.js"></script>
    <script>
      $(document).on('click', '.product-add-to-cart-container .number-spinner button', function () {
          update_number_spinner($(this));
      });

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


      $(document).on("input keyup paste change", ".number-spinner input", function () {
          var val = $(this).val();
          val = val.replace(",", "");
          val = val.replace(".", "");
          if (!$.isNumeric(val)) {
              val = 1;
          }
          if (isNaN(val)) {
              val = 1;
          }
          $(this).val(val);
      });

      $(document).on("input paste change", ".cart-item-quantity .number-spinner input", function () {
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
  </script>
  <script>
    $(document).on('click', '.btn-item-add-to-cart', function () {
    var product_id = $(this).attr("data-product-id");
    var button_id = $(this).attr("data-id");
    document.getElementById("btn_add_cart_" + button_id).innerHTML = '<div class="spinner-border spinner-border-add-cart-list"></div>';
    var data = {
        "product_id": product_id,
        "is_ajax": true
    };
    
    data[mds_config.csfr_token_name] = $.cookie(mds_config.csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: mds_config.base_url + "cart_controller/add_to_cart",
        data: data,
        success: function (response) {
            var obj = JSON.parse(response);
            if (obj.result == 1) {
                setTimeout(function () {
                    $('#btn_add_cart_' + button_id).css('background-color', 'rgb(40, 167, 69, .7)');
                    document.getElementById("btn_add_cart_" + button_id).innerHTML =
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16">\n' +
                        '<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>\n' +
                        '</svg>';
                    $('.span_cart_product_count').html(obj.product_count);
                    $('.span_cart_product_count').removeClass('visibility-hidden');
                    $('.span_cart_product_count').addClass('visibility-visible');
                }, 400);
                setTimeout(function () {
                    $('#btn_add_cart_' + button_id).css('background-color', 'rgba(255, 255, 255, .7)');
                    document.getElementById("btn_add_cart_" + button_id).innerHTML = '<i class="icon-cart"></i>';
                }, 2000);
            }
        }
    });
});
function remove_product(product_id) {
    var data = {
        "product_id": product_id
    };
    data['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
    $.ajax({
        type: "POST",
        url: "<?=base_url();?>delete-product-post",
        data: data,
        success: function (response) {
            location.reload();
        }
    });
};
  </script>
  </body>
</html>