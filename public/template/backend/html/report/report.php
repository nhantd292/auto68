<?php
$this->_userInfo = new \ZendX\System\UserInfo();
$curent_user = $this->_userInfo->getUserInfo();
$permission_ids = explode(',', $curent_user['permission_ids']);

$is_system = true;
$is_admin = true;
$is_marketing = true;
$is_sales = true;
$is_product = true;
$is_check_oder = true;
$is_accounting = true;

//    $is_system = false;
//    $is_admin = false;
//    $is_marketing = false;
//    $is_sales = false;
//    $is_product = false;
//    $is_check_oder = false;
//    $is_accounting = false;
//
//    if(in_array('system', $permission_ids)){
//        $is_system = true;
//    }
//    if(in_array('admin', $permission_ids)){
//        $is_admin = true;
//    }
//    if(in_array('marketing', $permission_ids)){
//        $is_marketing = true;
//    }
//    if(in_array('sales', $permission_ids)){
//        $is_sales = true;
//    }
//    if(in_array('product', $permission_ids)){
//        $is_product = true;
//    }
//    if(in_array('check_oder', $permission_ids)){
//        $is_check_oder = true;
//    }
//    if(in_array('accounting', $permission_ids)){
//        $is_accounting = true;
//    }
?>


<div class="page-sidebar navbar-collapse collapse">
    <ul class="page-sidebar-menu">
        <li class="sidebar-toggler-wrapper">
            <div class="sidebar-toggler hidden-phone"></div>
        </li>

        <?php if($is_system || $is_admin){?>
            <li class="start">
                <a href="<?php echo $this->url('routeReport/default', array('controller' => 'revenue', 'action' => 'index'));?>">
                    <i class="fa fa-home"></i>
                    <span class="title">Tổng quan</span><span class="selected"></span>
                </a>
            </li>
        <?php }?>


        <?php if($is_system || $is_admin || $is_marketing){?>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Báo cáo Marketing</span><span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--				<li>-->
                    <!--					<a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'marketing', 'code' => 'overview'));?><!--">-->
                    <!--                        <i class="fa fa-dot-circle-o"></i>-->
                    <!--                        Báo cáo Marketing-->
                    <!--                    </a>-->
                    <!--				</li>-->
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'marketing', 'code' => 'overview12'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo Marketing
                        </a>
                    </li>
                    <?php if($is_system || $is_admin){?>
                        <!--				<li>-->
                        <!--					<a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'marketing', 'code' => 'overview2'));?><!--">-->
                        <!--                        <i class="fa fa-dot-circle-o"></i>-->
                        <!--                        Báo cáo Marketing 2-->
                        <!--                    </a>-->
                        <!--				</li>-->
                        <!--				<li>-->
                        <!--					<a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'marketing', 'code' => 'overview22'));?><!--">-->
                        <!--                        <i class="fa fa-dot-circle-o"></i>-->
                        <!--                        Báo cáo Marketing 2 mới-->
                        <!--                    </a>-->
                        <!--				</li>-->
                    <?php }?>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'marketing', 'code' => 'sources'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo kênh nguồn
                        </a>
                    </li>
                </ul>
            </li>
        <?php }?>

        <?php if($is_system || $is_admin || $is_sales){?>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Báo cáo Sales</span><span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'sale', 'code' => 'share'));?>"><i class="fa fa-dot-circle-o"></i> Báo cáo chia số</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'sale', 'code' => 'sale1'));?>"><i class="fa fa-dot-circle-o"></i> Báo cáo doanh thu sale</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'sale', 'code' => 'sale-store'));?>"><i class="fa fa-dot-circle-o"></i> Báo cáo doanh thu cửa hàng</a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'sale', 'code' => 'sale2'));?>"><i class="fa fa-dot-circle-o"></i> Báo cáo sale chi tiết</a>
                    </li>
                </ul>
            </li>
        <?php }?>

        <?php if($is_system || $is_admin || $is_product){?>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Báo cáo sản xuất</span><span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'production', 'code' => 'overview'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo sản xuất
                        </a>
                    </li>
                    <!--				<li>-->
                    <!--					<a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'production', 'code' => 'debt'));?><!--">-->
                    <!--                        <i class="fa fa-dot-circle-o"></i>-->
                    <!--                        Báo cáo công nợ-->
                    <!--                    </a>-->
                    <!--				</li>-->
                </ul>
            </li>
        <?php }?>

        <?php if($is_system || $is_admin || $is_check_oder){?>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Báo cáo giục đơn</span><span class="arrow"></span>
                </a>
                <ul class="sub-menu">
<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'check', 'code' => 'external'));?><!--">-->
<!--                            <i class="fa fa-dot-circle-o"></i>-->
<!--                            Báo cáo giục đơn tỉnh-->
<!--                        </a>-->
<!--                    </li>-->
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'check', 'code' => 'external2'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo giục đơn tỉnh mới
                        </a>
                    </li>
<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'check', 'code' => 'internal'));?><!--">-->
<!--                            <i class="fa fa-dot-circle-o"></i>-->
<!--                            Báo cáo giục đơn Hà Nội-->
<!--                        </a>-->
<!--                    </li>-->
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'check', 'code' => 'internal2'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo giục đơn Hà Nội mới
                        </a>
                    </li>
                </ul>
            </li>
        <?php }?>

        <?php if($is_system || $is_admin || $is_accounting){?>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Báo cáo kế toán</span><span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <!--				<li>-->
                    <!--					<a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'acounting', 'code' => 'branch'));?><!--">-->
                    <!--                        <i class="fa fa-dot-circle-o"></i>-->
                    <!--                        Báo cáo nhập hàng cơ sở-->
                    <!--                    </a>-->
                    <!--				</li>-->
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'acounting', 'code' => 'branch2'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo nhập hàng
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'acounting', 'code' => 'status'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo theo trạng thái
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'acounting', 'code' => 'return'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo hoàn đơn
                        </a>
                    </li>
                </ul>
            </li>
        <?php }?>

        <?php if($is_system || $is_admin || $is_sales){?>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-bar-chart"></i>
                    <span class="title">Báo cáo chăm sóc</span><span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'care', 'code' => 'care1'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo doanh thu chăm sóc cũ
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'care', 'code' => 'care12'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo doanh thu chăm sóc mới
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'care', 'code' => 'care2'));?>">
                            <i class="fa fa-dot-circle-o"></i>
                            Báo cáo chăm sóc chi tiết
                        </a>
                    </li>
                </ul>
            </li>
        <?php }?>

        <?php if($is_system || $is_admin){?>
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="fa fa-bar-chart"></i>-->
<!--                    <span class="title">Báo cáo kho</span><span class="arrow"></span>-->
<!--                </a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'acounting', 'code' => 'stock'));?><!--">-->
<!--                            <i class="fa fa-dot-circle-o"></i>-->
<!--                            Báo cáo kho nguyên liệu-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
        <?php }?>
        <?php if($is_system || $is_admin){?>
<!--            <li>-->
<!--                <a href="javascript:;">-->
<!--                    <i class="fa fa-bar-chart"></i>-->
<!--                    <span class="title">Báo cáo cước vận chuyển</span><span class="arrow"></span>-->
<!--                </a>-->
<!--                <ul class="sub-menu">-->
<!--                    <li>-->
<!--                        <a href="--><?php //echo $this->url('routeReport/default', array('controller' => 'index', 'action' => 'index', 'id' => 'acounting', 'code' => 'cod'));?><!--">-->
<!--                            <i class="fa fa-dot-circle-o"></i>-->
<!--                            Báo cáo cước vận chuyển-->
<!--                        </a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
        <?php }?>
    </ul>
</div>