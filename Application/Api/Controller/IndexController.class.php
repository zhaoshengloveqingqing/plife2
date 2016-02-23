<?php
namespace Api\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $key = I('post.key');
        if (!$key) {
            $key = I('get.key');
        }
        dump((int) date("Y"));
        if ($this->validkey($key)) {
            $ret = array();
            switch ($key) {
                /* 登陆 */
                case "1001":
                    $regret = $this->login();
                    if (is_array($regret) == true) {
                        $ret['errcode'] = '0';
                        $ret['data'] = $regret;
                    } else {
                        $ret['errcode'] = $this->errcode;
                    }
                    break;
                case 'errcode':
                    echo '【错误返回信息值编码】'."\n";
                    print_r($this->errcode());
                    break;
                case 'reqkey':
                    echo '【有效请求编码】'."\n";
                    print_r($this->requestkey());
                    break;
            }
            $this->ajaxReturn($ret);
        }
    }

    private function validkey($key)
    {
        $validkeys = array('1000','1001', '1003', '1006','1012','1013','1031','1032','1033',
            '2001', '3001','3002','3003','3004','3005','3006',
            'reqkey','errcode'
            );
        if (in_array($key, $validkeys)) {
            return true;
        } else {
            return false;
        }
    }

    /* 获取接口有效请求编码值，正式环境下关闭 */
    private function requestkey()
    {
        $requestkey = array(
            '1000' => '获取APK启动图片',
            '1001' => '视频通用查询接口',
            '1003' => '修改密码',
            '1006' => '获取商户信息',
            '1012' => '忘记密码验证码',
            '1013' => '忘记密码重设置',
            //'2001' => '获取团购信息',
            '3001' => '获取商品购买类别',
            '3002' => '根据商品类别id获取商品品牌列表',
            '3003' => '搜索商品品牌',
            '3004' => '根据商品品牌获取规格列表',
            '3005' => '搜索商品规格',
            '3006' => '根据商品品牌获取规格列表',
            '5001' => '提交订单',
            '5002' => '取消订单',
            '5003' => '付款是否成功',
            '5004' => '确认收货',
            '5006' => '获取订单',
            '5007' => '获取订单分类数字',
            '5008' => '删除订单',
            '5011' => '获取服务订单',
            '6001' => '麦盟文章分类获取接口（此接口为预留接口暂不启用）',
            '6002' => '麦盟文章根据分类获取',
            '6003' => '麦盟文章详情获取',
            '6004' => '麦盟文章评论获取',
            //'6005' => '麦盟文章添加评论',
            'reqkey' => '请求编码值',
            'errcode' => '错误信息码',
            'test' => '测试'
        );
        return $requestkey;
    }

    /* 获取提示的错误信息码，正式环境下关闭 */
    private function errcode()
    {
        $errcode = array(
            // 前两位 10 系列为用户系列，登录、商户信息、密码、个人中心等
            // 后两位 1X 系列代表注册时的行为
            //'1010' => '<----------------后两位 1X 系列代表注册时的行为---------------->',
            //'1011' => '该手机号不符合规范',
            //'1012' => '该手机号已被注册',
            //'1013' => '该密码不符合规范',
            //'1014' => '手机号码或者密码不能为空',
            // 后两位 2X 系列代表的登录时的行为
            '1020' => '<----------------后两位 2X 系列代表的登录时的行为---------------->',
            '1021' => '该账号不存在',
            '1022' => '该手机号不符合规范',
            '1023' => '密码错误',
        );
        return $errcode;
    }
}