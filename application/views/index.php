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
    <!-- bootstrap-daterangepicker -->
    <link href="<?= base_url(); ?>assets/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

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
            <div class="row top_tiles">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-edit"></i></div>
                  <div class="count"><?=$orders_count;?></div>
                  <h3>Siparişler</h3>
                  
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-tag"></i></div>
                  <div class="count"><?=$products_count;?></div>
                  <h3>Ürünler</h3>
                  
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-turkish-lira"></i></div>
                  <div class="count"><?=$earnings;?></div>
                  <h3>Toplam Kazanç</h3>
                  
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="icon"><i class="fa fa-users"></i></div>
                  <div class="count"><?=$users_count;?></div>
                  <h3>Satıcı</h3>
                  
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Sipariş Göstergesi</h2>
                    <div class="filter">
                      <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                        <span></span> <b class="caret"></b>
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                      <div class="demo-container" style="height:280px">
                        <div id="chart_plot_02" class="demo-placeholder"></div>
                      </div>
                      <div class="tiles">
                        <div class="col-md-4 tile">
                          <span>Toplam Sipariş Sayısı</span>
                          <h2 id="total_order_count"></h2>
                        </div>
                        <div class="col-md-4 tile">
                          <span>Toplam Sipariş Tutarı</span>
                          <h2 id="total_order_amount"></h2>
                        </div>
                        <div class="col-md-4 tile">
                          <span>Sipariş Edilen Ürün Sayısı</span>
                          <h2 id="total_order_products"></h2>
                          
                        </div>
                      </div>

                    </div>

                    <div class="col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <div class="x_title">
                          <h2>Top Profiles</h2>
                          
                          <div class="clearfix"></div>
                        </div>
                        <ul id="usertoplist" class="list-unstyled top_profiles scroll-view">
                          
                        </ul>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>


          <?php /* ?>
            <div class="row">
              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Profiles <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item One Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Profiles <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item One Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                    
                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Top Profiles <small>Sessions</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <article class="media event">
                      <a class="pull-left date">
                        <p class="month">April</p>
                        <p class="day">23</p>
                      </a>
                      <div class="media-body">
                        <a class="title" href="#">Item One Title</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                      </div>
                    </article>
                                     
                    
                  </div>
                </div>
              </div>
            </div>
          <?php */ ?>  
          </div>
        </div>
        <!-- /page content -->

        <?php $this->load->view("partials/_footer"); ?>
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
    <!-- Chart.js -->
    <!-- jQuery Sparklines -->
    <script src="<?= base_url(); ?>assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- Flot -->
    <script src="<?= base_url(); ?>assets/vendors/Flot/jquery.flot.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/Flot/jquery.flot.time.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="<?= base_url(); ?>assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="<?= base_url(); ?>assets/vendors/DateJS/build/date.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="<?= base_url(); ?>assets/vendors/moment/min/moment.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="<?= base_url(); ?>assets/build/js/custom.js"></script>
   
    <script>
      
      function init_flot_chart(){
        if( typeof ($.plot) === 'undefined'){ return; }
        
        var dates=$('#reportrange span').html();    
        var date=dates.split('-');
        date[0]=date[0].trim();
        date[1]=date[1].trim();
        var mt1=date[0].split('/');
        var mt2=date[1].split('/');
        var date1=new Date( mt1[1]+"/"+mt1[0]+"/"+mt1[2] ).format('m/d/Y');
        var date2=new Date( mt2[1]+"/"+mt2[0]+"/"+mt2[2] ).format('m/d/Y');
        var Difference_In_Time = new Date(date2).getTime() - new Date(date1).getTime();
        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

        var chart_plot_02_data = [];
        
        var deneme=new Date( mt1[2]+"/"+mt1[1]+"/"+mt1[0] ).format('Y-m-d H:H:H');
        var data = {
        "start": deneme,
        "daycount":Difference_In_Days
        };
        
        data['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
            type: "POST",
            url:  "<?=base_url();?>date-of-orders",
            data: data,
            success: function (response) {
              var obj=JSON.parse(response);
              for (var i = 0; i <= Difference_In_Days; i++) {
              
              chart_plot_02_data.push([new Date(new Date(obj['counts'][i].date)).getTime(), obj['counts'][i].count  ]);
              }
              var chart_plot_02_settings = {
                grid: {
                  show: true,
                  aboveData: true,
                  color: "#3f3f3f",
                  labelMargin: 10,
                  axisMargin: 0,
                  borderWidth: 0,
                  borderColor: null,
                  minBorderMargin: 5,
                  clickable: true,
                  hoverable: true,
                  autoHighlight: true,
                  mouseActiveRadius: 100
                },
                series: {
                  lines: {
                    show: true,
                    fill: true,
                    lineWidth: 2,
                    steps: false
                  },
                  points: {
                    show: true,
                    radius: 4.5,
                    symbol: "circle",
                    lineWidth: 3.0
                  }
                },
                legend: {
                  position: "ne",
                  margin: [0, -20],
                  noColumns: 10,
                  labelBoxBorderColor: null,
                  labelFormatter: function(label, series) {
                    return label + '&nbsp;&nbsp;';
                  },
                  width: 40,
                  height: 1
                },
                colors: ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'],
                shadowSize: 0,
                tooltip: false,
                tooltipOpts: {
                  content: "%s: %y.0",
                  xDateFormat: "%d/%m",
                shifts: {
                  x: -30,
                  y: -50
                },
                defaultTheme: false
                },
                yaxis: {
                  min: 0
                },
                xaxis: {
                  mode: "time",
                  minTickSize: [1, "day"],
                  timeformat: "%d/%m/%y",
                  min: chart_plot_02_data[0][0],
                  max: chart_plot_02_data[Difference_In_Days][0]
                }
              };	
              
              if ($("#chart_plot_02").length){
                console.log('Plot2');
                
                $.plot( $("#chart_plot_02"), 
                [{ 
                  label: " order", 
                  data: chart_plot_02_data, 
                  lines: { 
                    fillColor: "rgba(150, 202, 89, 0.12)" 
                  }, 
                  points: { 
                    fillColor: "#fff" } 
                }], chart_plot_02_settings);
                
              }
              document.getElementById("total_order_count").innerHTML = obj['total'].total_count;
              document.getElementById("total_order_amount").innerHTML = obj['total'].total_sum+" <i class='fa fa-turkish-lira'></i>";
              document.getElementById("total_order_products").innerHTML = obj['total'].total_product;
              document.getElementById("usertoplist").innerHTML = obj['users'];
              }
        });
        
        
        
        
      } 
    </script>
  </body>
</html>