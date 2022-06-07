<?php defined('BASEPATH') or exit('No direct script access allowed');$user=user(); ?>
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

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Siparişler</h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                  <?php $this->load->view("partials/_messages"); ?>
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                      <thead>
                        <tr>
                          <th>S. No</th>
                          <th>Satıcı</th>
                          <th>Ürünler</th>
                          <th>Alıcı</th>
                          <th>K.Miktarı</th>
                          <th>Tutar</th>
                          <th>Tarih</th>
                          <th>İşlemler</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php foreach($orders as $item): ?>
                        <tr>
                          <td><?=$item->order_number;?></td>
                          <td><?=$item->user_name;?></td>
                          <td><?php foreach(get_order_products($item->order_number) as $product){
                            echo $product->title." ".$product->options."<br><b>".$product->quantity."</b><i class='fa fa-times'></i>".price_formatted($product->unit_price)." <i class='fa fa-turkish-lira'></i><br>";
                          } ?></td>
                          <td><?=$item->buyer_name?><br><?=$item->buyer_phone?><br><?=$item->address?><br><?=$item->town?>/<?=$item->city?></td>
                          <td><?=price_formatted($item->commission_amount)?> <i class="fa fa-turkish-lira"></i></td>
                          <td><?=price_formatted($item->total)?> <i class="fa fa-turkish-lira"></i></td>
                          <td><?=$item->created_at?></td>
                          <td><?php if(is_admin() && $item->is_approved==0): ?>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".<?=$item->id;?>">Takip Numarası</button><br>
                            <?php endif;
                            
                            if($item->user_id==$user->id && $item->is_approved==0 && $item->shipping_key!=0): ?>
                              <?=$item->shipping_comp.":<br>".$item->shipping_key?> <br>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".approve<?=$item->id;?>">Siparişi Onayla</button>
                            <?php endif;?>
                            <?php if($item->is_approved!=1 && ($item->shipping_key==0 || $item->shipping_key==null)){  ?>
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target=".delete<?=$item->id;?>">Sil</button>
                            <?php } ?>
                          </td>
                        </tr>
                        <?php if(is_admin() && $item->is_approved==0): ?>
                        <div class="modal fade <?=$item->id;?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel2">Kargo Takip Numarası</h4>
                                </div>
                                <div class="modal-body">
                                  <?php echo form_open('order-shipping-key-post'); ?>
                                  <p><b><?=$item->order_number?></b> numaralı siparişin kargo takip numarasını girmektesiniz.</p>
                                  <div class="form-group">
                                      <select name="shipping_comp" class="form-control">
                                        <option>Kargo Firması</option>
                                        <option <?php if($item->shipping_comp=="Yurtiçi Kargo"){echo "selected";} ?> value="Yurtiçi Kargo">Yurtiçi Kargo</option>
                                        <option <?php if($item->shipping_comp=="Mng Kargo"){echo "selected";} ?> value="Mng Kargo">Mng Kargo</option>
                                        <option <?php if($item->shipping_comp=="Aras Kargo"){echo "selected";} ?> value="Aras Kargo">Aras Kargo</option>
                                        <option <?php if($item->shipping_comp=="Sürat Kargo"){echo "selected";} ?> value="Sürat Kargo">Sürat Kargo</option>
                                        <option <?php if($item->shipping_comp=="PTT Kargo"){echo "selected";} ?> value="PTT Kargo">PTT Kargo</option>
                                      </select>                                    
                                  </div>
                                  <div class="form-group has-feedback">
                                    <input style="padding-left:50px;" type="text" class="form-control has-feedback-left" id="inputSuccess2" name="shipping_key" placeholder="Kargo Takip Numarası" value="<?=$item->shipping_key?>">
                                    <span class="fa fa-truck form-control-feedback left" aria-hidden="true"></span>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                                  <input type="hidden" name="id" value="<?=$item->id;?>">
                                  <button type="submit" class="btn btn-primary">Kaydet</button>
                                  <?php echo form_close(); ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php endif;
                          if($item->user_id==$user->id && $item->is_approved==0 && $item->shipping_key!=0): ?>
                        <div class="modal fade approve<?=$item->id;?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel2">Sipariş Onayı</h4>
                                </div>
                                <div class="modal-body">
                                  <?php echo form_open('order-approve-post'); ?>
                                  <p><b><?=$item->order_number?></b> numaralı siparişi onaylamak istediğinize emin misiniz? Sipariş alıcıya teslim edilmeden onaylamayınız.</p>
                                  
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                                  <input type="hidden" name="id" value="<?=$item->id;?>">
                                  <button type="submit" class="btn btn-primary">Onayla</button>
                                  <?php echo form_close(); ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php endif;
                          if($item->is_approved!=1 && ($item->shipping_key==0 || $item->shipping_key==null)): ?>
                        <div class="modal fade delete<?=$item->id;?>" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog modal-sm">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
                                  </button>
                                  <h4 class="modal-title" id="myModalLabel2">Siparişi Sil</h4>
                                </div>
                                <div class="modal-body">
                                  <?php echo form_open('delete-order'); ?>
                                  <p><b><?=$item->order_number?></b> numaralı siparişi silmek istediğinize emin misiniz? </p>
                                  
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
                                  <input type="hidden" name="order_number" value="<?=$item->order_number; ?>">
                                  <button type="submit" class="btn btn-danger">Sil</button>
                                  <?php echo form_close(); ?>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php endif;?>

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
  </body>
</html>