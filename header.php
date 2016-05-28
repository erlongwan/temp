<header class="navbar navbar-static-top">
    <?php
    $city = $this->user['city'];
    $current_city_id = $this->user['current_city_id'];
    $own_city = $this->user['own_city'] ? $this->user['own_city'] : array();
    $city_name = '';
    if ($current_city_id && !empty($city[$current_city_id]))
        $city_name = $city[$current_city_id];

    $menu = array(
//        'config'=>array(
//            'name'=>'配置管理',
//            '/purchaseFinance/supplierBillItem'=>'毛利润配置',
//            '/confirmsup/confirmSupplierConfig'=>'价格配置',
//            '/supplySorting/index' => '市场调查配置',
//        ),
//        'sale_price'=>array(
//            'name'=>'商品改价',
//            '/pspmc/price/sale' => '单品改价',
//            '/pspmc/batchprice/batchPrice'=>'批量改价',
//            '/pspmc/task/task'=>'改价任务管理',
//            '/pspmc/log/log'=>'改价日志查看',
//            '/pspmc/log/log/summarize' => '改价汇总查看',
//        ),
//        'procurement_price'=>array(
//            'name'=>'进价管理',
//            '/price/sale/index' => '单商品改价',
//            '/confirmsup/confirmSupplierConfig'=>'批量商品改价',
//        ),
//        'market'=>array(
//            'name'=>'市场调查',
//           '/supply/index'=>'人员管理',
//            '/purchaseFinance/audit'=>'对象管理',
//            '/marketresearch/sale/index'=>'市场调查表',
//            '/marketresearch/info/index'=>'市场调查详细信息',
//        ) ,
        'ssu_maintenance'=>array(
            'name'=>'SSU维护',
            '/maint/ssu/guide'=>'商品属性设置向导',
            '/maint/ssu/index'=>'SSU维护',
            '/maint/city/index'=>'城市分配',
            '/maint/product/attribute'=>'采购及营运属性维护',
            '/maint/product/search'=>'产品信息查询',
            '/spm/shelve/ssushelve'=>'SSU上架',
            '/spm/unshelve/ssuunshelve'=>'SSU下架',
        )  ,
//        'task_manager'=>array(
//            'name'=>'商品改价审批',
//            '/pspmc/approval/approval'=>'审批任务管理',
////            '/confirmsup/confirmSupplierConfig'=>'待审批市调任务',
//        ) ,
//        'operator_log'=>array(
//        'name'=>'日志查询',
//        '/pspmc/log/sale/index'=>'改价日志查询',
//        '/purchaseFinance/audit'=>'审批流查询',
//        )
		'market_res'=>array(
            'name'=>'市调任务',
            '/pspmc/marketresearch/task'=>'市调任务',
            '/pspmc/marketresearch/price'=>'市调价格',
         //'/pspmc/approval/approval/index'=>'市调任务',
          //  '/maint/city/index'=>'市调价格',
        ),
        'dictionary'=>array(
            'name'=>'税率维护',
            '/maint/dictionary/general' => '税率维护'
        ),
        'system'=>array(
            'name'=>'系统中心',
            '/spm/system/index'=>'数据监控',
        ),
        'sp_modification'=>array(
            'name'=>'营运统筹工具',
            '/spm/report/profitDetail'=>'毛利预测明细表',
            '/spm/report/adjustDetail'=>'毛利预测任务表',
            '/spm/task/modifyPrice'=>'单品售价改价',
            '/spm/approval/taskApproval'=>'单品改价审批',
            '/spm/task/modifyPriceInfo'=>'单品改价记录',
            '/spm/mallLog/index'=>'改价商城调用日志',
            '/spm/promotion/promotionList'=>'促销验证-列表',
            '/spm/promotion/promotionVerify'=>'满赠预测页面',
            '/spm/report/salesDateArea'=>'销量报表(日期+售卖区)',
            '/spm/report/salesDateClass'=>'销量报表(日期+一级分类)'
        )
    );
    //获取当前url
    $controller_name = Yii::app()->controller->id;
    $action_name = $this->getAction()->getId();
    $module = $this->getModule();
    if(empty($module)){
        $current_url = '/' . $controller_name . '/' . $action_name;
    }else{
        $module_name = $this->getModule()->id;
        $current_url = '/' .$module_name.'/' . $controller_name . '/' . $action_name;
    }

    $menuStr = '';
    $tabStr = array();
    $breadStr='';
    foreach($menu as $menuName => $tabArr){
        $tabStr[$menuName] ='';
        $menuStr_li = '';
        foreach($tabArr as $url => $url_name){
            if($url == 'name')
                continue;
            if(in_array(strtolower($url), $this->user['nav'])){
                //menu
                $menuStr_li .= "<li><a href=" . $url . ">" . $url_name . "</a></li>\n";
                //tab
                if($url == $current_url){
                    $breadStr .= "<li>". $tabArr['name'] ."</li>\n";
                    $breadStr .= "<li class='active'><a href=" . $current_url . ">". $tabArr[$current_url] . "</a></li>\n";
                    $tabStr[$menuName] .= "<li role='presentation' class='active'><a href=" . $url . ">" . $url_name . "</a></li>\n";
                    $showTab = $menuName;
                }else{
                    $tabStr[$menuName] .= "<li role='presentation'><a href=" . $url . ">" . $url_name . "</a></li>\n";
                }
            }
        }
        if(!empty($menuStr_li)){
            $menuStr .= "<li class='dropdown'>\n";
            $menuStr .= "<a href='javascript:void(0)' class='dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>". $tabArr['name'] ."<b class='caret'></b></a>\n";
            $menuStr .= "<ul class='dropdown-menu'>\n";
            $menuStr .= $menuStr_li;
            $menuStr .= "</ul>\n";
            $menuStr .= "</li>\n";
        }
    }
    ?>
    <div class="container-fluid">
        <div class="navbar-header">
		<a href="/" title="营运系统"><img src="/assets/image/common/logo.png" class="logo-img">营运系统</a>
	</div>
        <nav class="collapse navbar-collapse in" aria-expanded="false">
            <ul class="nav navbar-nav navbar-left">
                <?php echo $menuStr ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?php echo $this->user ? '您好，'.$this->user['name'].'（'.$this->user['role_name'].'）' : '未登录用户' ?>
                    </a>
                </li>
                <li><a href="/site/logout">退出</a></li>
                <li class="dropdown">
                    <input type="hidden" id="header_city_id" value=<?php echo $current_city_id;?>>
                    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <?php echo $city_name ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        if(!empty($this->user)){
                            $own_city = explode(',', $own_city);
                            foreach($own_city as $city_id) {
                        ?>
                        <li>
                            <a href="/site/switchCity?city_id=<?php echo $city_id ?>">
                                <?php echo $city[$city_id] ?>
                            </a>
                        </li>
                        <?php }
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>
<?php if(!empty($breadStr)){ ?>
<div>
    <div class="col-lg-12" role="main" style="margin-bottom:20px">
        <!-- 面包屑导航 -->
        <ol class="breadcrumb">
            <li><a href="/">首页</a></li>
            <?php echo $breadStr ?>
        </ol>
        <!-- TAB区域 -->
        <section id="ui-tab-fix" class="fw-mb20">
            <div role="tabpanel">
                <ul class="nav nav-tabs" role="tablist">
                    <?php echo $tabStr[$showTab] ?>
                </ul>
            </div>
        </section>
    </div>
</div>
<?php } ?>