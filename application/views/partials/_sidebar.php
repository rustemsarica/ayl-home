<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="<?=base_url() ?>" class="site_title"><i class="fa fa-plus"></i> <span><?=get_general_settings()->company_name ?></span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              
              <div style="padding-left:50px;width:auto;" class="profile_info">
                <span>Hoşgeldin,</span>
                <?php $user=user();echo "<h2>".$user->first_name." ".$user->last_name."<h2>";  ?>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />
    <?php $counts=json_decode(counts()); ?>
            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                
                <ul class="nav side-menu">
                  <li><a href="<?= base_url();?>"><i class="fa fa-home"></i> Anasayfa</a></li>
                  
                  <li><a href="<?= generate_url("cart")?>"><i class="fa fa-shopping-cart"></i> Sepet<?php if(get_cart_product_count()>0){echo'<span class="label label-success pull-right">'.get_cart_product_count().'</span>';}?></a></li>
                  
                  <?php if(is_admin()): ?>
                  <li><a><i class="fa fa-tag"></i> Ürünler <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= generate_url("products")?>">Ürünler</a></li>
                      <li><a href="<?= generate_url("products/add-product")?>">Ürün Ekle</a></li>
                    </ul>
                  </li>
                  <?php else: ?>
                    <li><a href="<?= generate_url("products")?>"><i class="fa fa-tag"></i>Ürünler</a></li>
                  <?php endif; ?>

                  <li><a><i class="fa fa-edit"></i> Siparişler <?php if($counts->orders>0):?><span class="label label-success pull-right"><?=$counts->orders?></span><?php endif; ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= generate_url("orders")?>">Bekleyen Siparişler</a></li>
                      <li><a href="<?= generate_url("orders/shipped")?>">Kargolanan Siparişler</a></li>
                      <li><a href="<?= generate_url("orders/completed")?>">Tamamlanan Siparişler</a></li>
                    </ul>
                  </li>

                  <li><a href="<?= generate_url("earnings")?>"><i class="fa fa-credit-card"></i> Kazançlar</a></li>
                  
                  <li><a href="<?= generate_url("payouts")?>"><i class="fa fa-money"></i>Ödemeler<?php if($counts->payouts>0):?><span class="label label-success pull-right"><?=$counts->payouts?></span><?php endif; ?></a></li>
                  
                  
                  <?php if(is_admin()): ?>
                  <li><a><i class="fa fa-users"></i> Üyeler <?php if($counts->users>0):?><span class="label label-success pull-right"><?=$counts->users?></span><?php endif; ?><span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="<?= generate_url("users/approve")?>">Onay Bekleyenler</a></li>
                      <li><a href="<?= generate_url("users")?>">Üyeler</a></li>
                    </ul>
                  </li>
                  <li><a href="<?= generate_url("settings")?>"><i class="fa fa-cog"></i> Ayarlar</a></li>
                  <?php else: ?>
                    <li><a href="<?= generate_url("profile")?>"><i class="fa fa-user"></i> Profil</a></li>
                  <?php endif; ?>
                  <li><a href="<?= generate_url("change-password")?>"><i class="fa fa-exchange"></i> Şifremi Değiştir</a></li>
                  <li><a href="<?= generate_url("logout")?>"><i class="fa fa-sign-out"></i> Çıkış Yap</a></li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->

          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
              
              <ul style="visibility:hidden;" class="nav navbar-nav navbar-right">
                
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <img src="" alt="">John Doe
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>

                <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope-o"></i>
                    <span class="badge bg-green">6</span>
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                    <li>
                      <a>
                        <span class="image"><img src="" alt="Profile Image" /></span>
                        <span>
                          <span>John Smith</span>
                          <span class="time">3 mins ago</span>
                        </span>
                        <span class="message">
                          Film festivals used to be do-or-die moments for movie makers. They were where...
                        </span>
                      </a>
                    </li>
                    <li>
                      <div class="text-center">
                        <a>
                          <strong>See All Alerts</strong>
                          <i class="fa fa-angle-right"></i>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
              </ul>
              
            </nav>
          </div>
        </div>
        <!-- /top navigation -->