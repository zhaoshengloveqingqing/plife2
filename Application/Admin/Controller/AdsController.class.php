<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class AdsController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->positionLogic =  D('Position','Logic');
        $this->bannerLogic =  D('Banner','Logic');
        $this->videoLogic =  D('Video','Logic');
        $this->appsLogic =  D('Apps','Logic');
        $this->Video=  M('Video');
        $this->Apps=  M('App');
        $this->Banner=  M('Banner');
        $this->Position=  M('Position');
        $this->Dns=  M('Dns');
        $this->Comment=  M('Comment');
    }

    private $positionLogic ;
    private $bannerLogic ;
    private $Position ;
    private $Banner ;
    private $videoLogic ;
    private $Video ;
    private $appsLogic ;
    private $Apps ;
    private $Dns;
    private $Comment;

    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }
    //banner
    public function bannermgr(){
        $this->checkPriv('1_3_1');
        $p = getCurPage();
        $where['end_time']=array('gt',date('Y-m-d H:i:s'));
        $res = $this->bannerLogic->getBannerList($where,$p);
        $type=array('图片','Flash','代码','文字');
        foreach($res as $k=>$v){
            $res[$k]['type']=$type[$res[$k]['type']];
        }
        $this->data = $res;
        $this->total = $this->bannerLogic->getBannerTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function addbanner(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['url'] = I('post.url');
            $newdata['type'] = I('post.type');
            $newdata['content'] = I('post.content');
            $newdata['sort'] = I('post.sort');
            $newdata['start_time'] = I('post.start_time');
            $newdata['end_time'] = I('post.end_time');
            $newdata['status']='1';
            $upres = $this->upimgfile();

            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $newdata['img'] = I('post.img');
            $ret = $this->Banner->add($newdata);
            if($ret){
                $this->redirect('Ads/bannermgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $type=array(
                array('id'=>'0','value'=>'图片'),
                array('id'=>'1','value'=>'Flash'),
                array('id'=>'2','value'=>'代码'),
                array('id'=>'3','value'=>'文字')
            );
            $this->assign('types',$type);
            $this->display("Ads/banneredit");
        }
    }

    public function editbanner(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['url'] = I('post.url');
            $newdata['type'] = I('post.type');
            $newdata['content'] = I('post.content');
            $newdata['sort'] = I('post.sort');
            $newdata['start_time'] = I('post.start_time');
            $newdata['end_time'] = I('post.end_time');
            $newdata['status']='1';

            $upres = $this->upimgfile();

            if($upres['error'] == false){
                $newdata['img'] = $upres['result']['img']['fullpath'];
            }
            $ret = $this->Banner->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Ads/bannermgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->error('编辑数据错误');
            }
        }else{

            $id = I('get.id','','int');
            $data = $this->bannerLogic->getBannerById($id);
//            $data['html_content']=htmlspecialchars($data['content']);
            $this->data = $data;
            $type=array(
                array('id'=>'0','value'=>'图片'),
                array('id'=>'1','value'=>'Flash'),
                array('id'=>'2','value'=>'代码'),
                array('id'=>'3','value'=>'文字')
            );

            $this->assign('simgs',json_decode($data['imgs']));
            $this->assign('type',$data['type']);
            $this->assign('types',$type);
            $this->display("Ads/banneredit");
        }
    }

    public function delbanner(){
        $this->checkPriv('1_3_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Banner->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    public function chgbannerstatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Banner->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Banner->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    //position
    public function positionmgr(){
        $this->checkPriv('1_3_1');
        $p = getCurPage();
        $res = $this->positionLogic->getPositionList(array(),$p);
        $this->data = $res;
        $this->total = $this->positionLogic->getPositionTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function addposition(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['width'] = I('post.width');
            $newdata['height'] = I('post.height');
            $newdata['description'] = I('post.description');
            $ret = $this->Position->add($newdata);
            if($ret){
                $this->redirect('Ads/positionmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("Ads/positionedit");
        }
    }

    public function editposition(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['width'] = I('post.width');
            $newdata['height'] = I('post.height');
            $newdata['description'] = I('post.description');
            $ret = $this->Position->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Ads/positionmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->error('编辑数据失败');
            }
        }else{
            $id = I('get.id','','int');
            $data = $this->positionLogic->getPositionById($id);
            $this->data = $data;
            $this->display("Ads/positionedit");
        }
    }

    public function delposition(){
        $this->checkPriv('1_3_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Position->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    private function upimgfile(){
        $ret = array();
        $upload =  new \Think\Upload();
        $upload->maxSize       = C('ITEM_IMG_MAXSIZE');;
        $upload->exts          = array('jpg', 'jpeg', 'png', 'gif', 'bmp');
        $upload->rootPath      = C('ITEM_IMG_PATH');
        $upload->subName       = array('date', 'Ym');
        $upfinfo = $upload->upload();
        if(!$upfinfo) {// 上传错误提示错误信息
            $ret['error'] = true;
            $ret['result'] = $upload->getError();
            //$this->error($upload->getError());
        }else{// 上传成功
            foreach($upfinfo as $k=>&$file){
                $file['fullpath'] = $upload->rootPath.$file['savepath'].$file['savename'];
            }
            $ret['error'] = false;
            $ret['result'] = $upfinfo;
        }
        return $ret;
    }



    private function upfile(){
        $ret = array();
        $upload =  new \Think\Upload();
        $upload->maxSize       = C('ITEM_FILE_SIZE');;
        $upload->exts          = array('zip','jar','apk','doc','xls');
        $upload->rootPath      = C('ITEM_FILE_PATH');
        $upload->subName       = array('date', 'Ym');
        $upfinfo = $upload->upload();
        if($upfinfo) {
            foreach($upfinfo as $k=>&$file){
                $file['fullpath'] = $upload->rootPath.$file['savepath'].$file['savename'];
            }
            $ret['error'] = false;
            $ret['result'] = $upfinfo;
        }else{
            $ret['error'] = true;
            $ret['result'] = $upload->getError();
            $this->error($upload->getError());
        }
        return $ret;
    }
}