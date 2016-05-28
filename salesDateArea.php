<div class="wrap">
    <div class="main">
        <div id="general_alert"></div>
        <section id="ui-search-area">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">查询条件</h3>
                </div>
                <div class="panel-body">
                    <form role="form" id="search_form" class="form form-inline">
                        <div class="row" style="margin-left:20px;margin-bottom:20px;">
                            <div class="form-group">
                                <label class="control-label wlabel" style=""></label>
                                <input id="begin_date" type="text" class="form-control hasDatepicker" name="start_time" 
                                       autocomplete="off" placeholder="开始日期" style="width:104px" readonly>
                                <label class="control-label" style="text-align: center; width: 30px;margin-right: 0;">至</label>
                                <input id="end_date" type="text" class="form-control hasDatepicker" name="end_time"
                                       autocomplete="off" placeholder="结束日期" style="width:104px" readonly>
                                <button id="search_btn" type="button" class="btn btn-primary">查询</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- table -->
        <section id="table">
            <table id="profitDetail" style="font-size:12px"></table>
            <div class="" id="pagination_div"></div>
        </section>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var dateOpts = {
            elem: '#begin_date',
            format: 'YYYY-MM-DD',
            isclear: true, //是否显示清空
            istoday: false, //是否显示今天
            choose: function (dates) {
                var dateStr = $(this.elem).val();
                var date = (new Date(dateStr)).getTime();
                if(this.elem == '#begin_date'){
                    var endDate = $('#end_date').val()?(new Date($('#end_date').val())).getTime():0;
                    if(endDate && date>endDate){
                        alert('开始时间不能晚于结束时间!');
                        $('#begin_date').val('');
                    }

                }else {
                    var beginDate = $('#begin_date').val()?(new Date($('#begin_date').val())).getTime():0;
                    if(beginDate && date<beginDate){
                        alert('结束时间不能早于开始时间!');
                        $('#end_date').val('');
                    }
                }
            }
        };
        laydate(dateOpts);
        laydate($.extend({},dateOpts,{elem:'#end_date'}));

        var $tableIndex = $("#profitDetail");
        var column = [
            {
                field: 'date',
                title: '下单日期/售卖区',
                align: 'center'
            }, {
                field: '',
                title: '分拣金额',
                align: 'center'
            }, {
                field: '',
                title: '实际金额',
                align: 'center'
            }, {
                field: '',
                title: '大订单数(订单数)',
                align: 'center'
            }, {
                field: '',
                title: '优惠前单均',
                align: 'center'
            }, {
                field: '',
                title: '实际单均',
                align: 'center'
            }, {
                field: '',
                title: '门店数(商户数)',
                align: 'center'
            }, {
                field: '',
                title: '优惠前商户均',
                align: 'center'
            }, {
                field: '',
                title: '实际商户均',
                align: 'center'
            }, {
                field: '',
                title: '下单成本金额',
                align: 'center'
            }, {
                field: '',
                title: '参考成本金额(分拣成本金额)',
                align: 'center'
            }, {
                field: '',
                title: '实际成本金额',
                align: 'center'
            }, {
                field: '',
                title: '优惠前毛利率',
                align: 'center'
            }, {
                field: '',
                title: '实际毛利率',
                align: 'center'
            }, {
                field: '',
                title: '缺货金额',
                align: 'center'
            }, {
                field: '',
                title: '客退金额',
                align: 'center'
            }, {
                field: '',
                title: '损益金额',
                align: 'center'
            }, {
                field: '',
                title: '损益率',
                align: 'center'
            }
        ];

        $tableIndex.bootstrapTable({
            columns: column,
            detailView: true,
            url:'/spm/report/getSsuProfitInfo',//getSalesDateInfo
            queryParams:queryParams,
            onExpandRow: function (index, row, $detail) {
                $detail.addClass('child');
                if (typeof(row.detail) !== "undefined" && row.detail.length != 0) {
                    expandTable($detail, row.detail);
                }
            },
            responseHandler:function(res){
                return [{'date':'2016-05-27','detail':[{'date':'1区'},{'date':'2区'}]},
                    {'date':'2016-05-28', 'detail':[{'date':'1区'},{'date':'2区'}]}];
            }
        });

        $('#search_btn').click(function () {
                $tableIndex.bootstrapTable('refresh');
        });
        function queryParams(params) {

            return params;
        }


        function expandTable($detail, rows) {
            columns = column;
            $el = $detail.html('<table></table>').find('table');
            $el.bootstrapTable({
                columns: columns,
                data: rows
            });
            $('.child').find('thead').remove();
        }
    });
</script>