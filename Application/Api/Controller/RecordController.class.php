<?php
namespace Mobile\Controller;
use Think\Controller;
class RecordController extends Controller {
    public function __construct(){
        $this->Res = D('Resource','Logic');
        $this->Video=  M('Video');
        $this->App=  M('App');
    }
    private $Res;
    private $App;
    private $Video;
    /**
     * 下载计数统计接口
     * 参数：
     * @param int id : （必填）应用id
     */
    public function download(){
        $id = I('post.id','','int');
        if($id) {
            $data = $this->Res->getAppById($id);
            $arr['downtimes'] = $data['downtimes']+1;
            $this->App->where('id='.$id)->save($arr);
        }
        echo  $arr['downtimes']?$arr['downtimes']:0;
    }

    /**
     * 播放计数统计接口
     * 参数：
     * @param int id : （必填）视频id
     */
    public function play(){
        $id = I('post.id','','int');
        if($id) {
            $data = $this->Res->getVideoById($id);
            $arr['viewtimes'] = $data['viewtimes']+1;
            $this->Video->where('id='.$id)->save($arr);
        }
        echo  $arr['viewtimes']?$arr['viewtimes']:0;
    }

}