<?php
/**
 * Created by PhpStorm.
 * User: 金钟
 * Date: 2016/4/18
 * Time: 10:27
 */

include_once Yii::app()->basePath . '/../pspmc/util/ExcelUtil.php';

class ReportController extends Controller {

    /**
     * 毛利预测任务表（毛利率改价历史列表初始页面）
     */
    public function actionProfitDetail() {
        $this->render('profit_detail');
    }

    /**
     * 毛利预测明细表（毛利率改价操作页面初始页面）
     */
    public function actionAdjustDetail() {
        $this->render('adjust_detail');
    }

    /**
     * 销量报表(按日期+区域)
     */
    public function actionSalesDateArea(){
        $this->render('salesDateArea');
    }

    /**
     * 销量报表(按日期+大类)
     */
    public function actionSalesDateClass(){
        $this->render('salesDateClass');
    }


    public function actionGetSsuProfitInfo(){
        $params = $_POST;
        $params['city_id'] = $this->user['current_city_id'];
        $result = Yii::app()->api->pspmc->getSsuProfitInfo($params);
        $this->renderJsonData($result['data']);
    }

    public function actionCalcClass1ProfitInfo(){
        $params = $_POST;
        $params['city_id'] = $this->user['current_city_id'];
        $params['user'] = $this->user['id'];
        $params['user_name'] = $this->user['name'];
        $result = Yii::app()->api->pspmc->calcClass1ProfitInfo($params);
        $this->renderJsonData($result['data']);
    }

    public function actionSaveAdjustInfo(){
        $params = $_POST;
        $params['city_id'] = $this->user['current_city_id'];
        $params['user'] = $this->user['id'];
        $params['user_name'] = $this->user['name'];
        $params['user_role'] = $this->user['role'];
        $result = Yii::app()->api->pspmc->saveAdjustInfo($params);
        LogUtil::info($params, $result, __CLASS__, __FUNCTION__, __LINE__, "保存调整结果!");
        if($result['ret'] == 1){
            $result = array('success'=>true, 'message'=>$result['data']['message']);
        }else{
            $result = array('success'=>false, 'message'=>'调用保存调整信息时出错!');
        }
        $this->renderJsonData($result);
    }

    /**
     * 毛利预测改价历史查询接口
     */
    public function actionQueryProfitTaskModel() {

        $params = $this->getRawData();
        $log_params = [];
        $log_params['city_id'] = intval($this->user['current_city_id']);

        if (isset($params['start_date']) && !empty(trim($params['start_date']))) {
            $log_params['start_date'] = strtotime($params['start_date']);
        }

        if (isset($params['end_date']) && !empty(trim($params['end_date']))) {
            $log_params['end_date'] = strtotime($params['end_date']) + (24 * 60 * 60 - 1);
        }

        //分页相关
        $log_params['offset'] = intval($params['offset']);
        $log_params['limit'] = intval($params['limit']);
        $result = Yii::app()->api->pspmc->queryProfitTaskModel($log_params);
        LogUtil::info($log_params, $result, __CLASS__, __FUNCTION__, __LINE__, "毛利预测改价历史查询");

        if (!isset($result['ret']) || empty($result['data'])) {
            $this->renderJsonData(["success" => false, "message" => "没有对应的毛利预测改价历史！"]);
        }

        //构造数据
        $result_profit_items['rows'] = $result['data']['rows'];
        $result_profit_items['total'] = $result['data']['total'];
        $this->renderJsonData($result_profit_items);
    }

    /**
     * 导出毛利预测改价历史
     */
    public function actionExportProfitTaskModel() {
        $params = $_GET;
        $log_params = [];
        $log_params['city_id'] = intval($this->user['current_city_id']);

        if (isset($params['start_date']) && !empty(trim($params['start_date']))) {
            $log_params['start_date'] = strtotime($params['start_date']);
        }
        if (isset($params['end_date']) && !empty(trim($params['end_date']))) {
            $log_params['end_date'] = strtotime($params['end_date']) + (24 * 60 * 60 - 1);
        }

        //分页相关
        $log_params['offset'] = 0;
        $log_params['limit'] = 50000;
        $result = Yii::app()->api->pspmc->exportProfitTaskModel($log_params);
        if (!isset($result['ret']) || empty($result['data']['rows'])) {
            $excel_data = [];
        }
        $excel_data = $result['data']['rows'];
        $column = [
            "calc_date" => "运算结果日期",
            "creator_name" => "任务创建人",
            "cost" => "昨日成本",
            "sales_price" => "昨日销售额",
            "adjust_sales_price" => "调整后销售额",
            "profit" => "昨日毛利率",
            "adjust_profit" => "调整后毛利率"
        ];
        return ExcelUtil::getInstance()->export($excel_data, $column, '毛利预测改价历史' . date('Y-m-d', time()) . '.xls');
    }

    /**
     * 毛利预测改价历史第一分类明细查询接口
     */
    public function actionQueryProfitClass1Model() {

        $params = $_POST;
        if (!isset($params['f_task_id'])) {
            $this->renderJsonData(["success" => false, "message" => "没有对应的毛利预测改价历史第一分类明细！"]);
        }

        $log_params = ['f_task_id' => intval($params['f_task_id'])];
        $result = Yii::app()->api->pspmc->queryProfitClass1Model($log_params);
        LogUtil::info($log_params, $result, __CLASS__, __FUNCTION__, __LINE__, "毛利预测改价历史第一分类明细查询");

        if (!isset($result['ret']) || empty($result['data'])) {
            $this->renderJsonData(["success" => false, "message" => "没有对应的毛利预测改价历史第一分类明细！"]);
        }

        //构造数据
        $result_profit_items['rows'] = $result['data']['rows'];
        $result_profit_items['total'] = $result['data']['total'];
        $this->renderJsonData($result_profit_items);
    }

    /**
     * 毛利预测改价历史Ssu明细查询接口
     */
    public function actionQueryProfitSsuModel() {

        $params = $_POST;
        if (!isset($params['f_task_id'])) {
            $this->renderJsonData(["success" => false, "message" => "没有对应的毛利预测改价历史第一分类明细！"]);
        }

        $log_params = ['f_task_id' => intval($params['f_task_id'])];
        $result = Yii::app()->api->pspmc->queryProfitSsuModel($log_params);
        LogUtil::info($log_params, $result, __CLASS__, __FUNCTION__, __LINE__, "毛利预测改价历史第一分类明细查询");

        if (!isset($result['ret']) || empty($result['data'])) {
            $this->renderJsonData(["success" => false, "message" => "没有对应的毛利预测改价历史第一分类明细！"]);
        }

        //构造数据
        $result_profit_items['rows'] = $result['data']['rows'];
        $result_profit_items['total'] = $result['data']['total'];
        $this->renderJsonData($result_profit_items);
    }

}
