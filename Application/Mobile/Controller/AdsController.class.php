<?php
namespace Mobile\Controller;
use Think\Controller;
class AdsController extends Controller {
    public function __construct()
    {

        $this->appLogic = D('App', 'Logic');
        $this->videoLogic = D('Video', 'Logic');
        $this->bannerLogic = D('Banner', 'Logic');
    }

    private $appLogic;
    private $videoLogic;
    private $bannerLogic;
    /**
     * 获取splash广告
     * @return json : data
     */
    public function splash() {
        $ret = array(
            "img" => "http://api.pinet.co/application/static/img/splash.png",
            "title" => "美菱冰箱"
        );
        $this->ajaxReturn($ret);
    }
    /**
     * 获取banner广告
     * @return json : data
     */
    public function banner() {
        $p = getCurPage();
        $where['end_time']=array('gt',date('Y-m-d H:i:s'));
        $res = $this->bannerLogic->getBannerList($where,$p);
        $this->ajaxReturn($res);
    }
    /**
     * 游戏幻灯片信息获取接口
     * @param int t : （可选）类型：1:游戏（默认）  2:应用
     * @return json : data
     */
    public function getappimgs()
    {
        $t = I('post.t', '', 'int') ? I('post.t', '', 'int') : 1;
        $mycond['apptype'] = $t;
        $data = $this->appLogic->getAppList($mycond);
        $this->ajaxReturn($data);
    }

    /**
     * 视频图片信息获取接口
     * @param int id : 必填
     * @return json : data
     */
    public function getimg(){
        $uuid = I('post.uuid');
        $data = $this->videoLogic->getimgById($uuid);
        foreach($data as $v){
            $cover =  C('MMS_SERVER').'/'.$uuid.'/'.$v['cover'];
        }
        $this->ajaxReturn($cover);
    }
}