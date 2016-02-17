<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
header("Content-Type:text/html; charset=utf-8");
class ResourceController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->categoryLogic=  D('Category','Logic');
        $this->commentLogic=  D('Comment','Logic');
        $this->dnsLogic=  D('Dns','Logic');
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
        $this->Category=  M('Category');
    }

    private $categoryLogic;
    private $commentLogic;
    private $dnsLogic;
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
    private $Category;

    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }

    // 视频
    public function videomgr(){
        $this->checkPriv('1_1_1');
        $p = getCurPage();
        $res = $this->videoLogic->getVideoList(array(),$p);
        $this->data = $res;
        $this->total = $this->videoLogic->getVideoTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    /* vedioplay modify by morgan 2016-01-04 */
    public function videoplay(){
        $uuid=I('get.uuid');
        $name=I('get.name');
        $type=I('get.type','','int');
        $setnum=I('get.setnum','','int');;
        if($type==2){
            if($setnum){
                $uuid=$uuid.'/'.$setnum;
            }else{
                $uuid=$uuid.'/'.'1';
            }
        }else{
            $uuid;
        }
        $this->assign('setnum',$setnum);
        $this->assign('uuid',$uuid);
        $this->assign('name',$name);
        $this->display("Resource/videoplay");
    }

    //vedio_isrecommend added by morgan 2016-01-04
    public function vedio_isrecommend(){
        $id = I('get.id','','int');
        $isrecommend = I('get.isrecommend','','int');
        $redirect_url = I('server.HTTP_REFERER');
        if($id){
            if($isrecommend==1 || $isrecommend==0) {
                $this->Video->where('id=' . $id)->save(array('isrecommend' => $isrecommend));
                redirect($redirect_url);
            }else{
                $this->error('无法设置该推荐视频');
            }
        }else{
            $this->error('该记录不存在');
        }
    }

    public function addvideo(){
        $this->checkPriv('1_1_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['type'] = I('post.type');
            $newdata['duratime'] = I('post.duratime');
            $newdata['director'] = I('post.director');
            $newdata['actors'] = I('post.actors');
            $newdata['setnum'] = I('post.setnum','','int');
            $newdata['country'] = I('post.country');
            $newdata['category'] = I('post.category');
            $newdata['years'] = I('post.years','','int');
            $newdata['intro'] = I('post.intro');
            $newdata['uuid'] = $this->videoLogic->getUuid();
            if($newdata['setnum'] == 0 || $newdata['years'] == 0){$this->error('集数或年代不能为空，请填入合理数字');}
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['cover'] = $upres['result']['coverimg']['fullpath'];
            }
            $ret = $this->Video->add($newdata);
            if($ret){
                $this->redirect('Resource/videomgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $types = $this->categoryLogic->getParentCategoryList('1');
            $types[] = array('id'=>'0','name'=>'=请选择=');
            sort($types);
            $this->assign('types',$types);
            $this->display("Resource/videoedit");
        }
    }

    public function editvideo(){
        $this->checkPriv('1_1_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['type'] = I('post.type');
            $newdata['duratime'] = I('post.duratime');
            $newdata['director'] = I('post.director');
            $newdata['actors'] = I('post.actors');
            $newdata['setnum'] = I('post.setnum','','int');
            $newdata['country'] = I('post.country');
//            $newdata['category'] = I('post.category');
            $newdata['category'] = I('post.cate');
            $newdata['years'] = I('post.years','','int');
            $newdata['intro'] = I('post.intro');
            if($newdata['setnum'] == 0 || $newdata['years'] == 0){$this->error('集数或年代不能为空，请填入合理数字');}
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['cover'] = $upres['result']['coverimg']['fullpath'];
            }
            $ret = $this->Video->where('id='.$id)->save($newdata);

            if($ret){
                $this->redirect('Resource/videomgr');
            }else{
                $this->error('编辑数据失败');
            }
        }else{
            $id = I('get.id','','int');
            $this->data = $this->videoLogic->getVideoById($id);
            $types = $this->categoryLogic->getParentCategoryList('1');
            $types[] = array('id'=>'0','name'=>'=请选择=');
            sort($types);
            $this->assign('cate',$this->data['category']);
            $this->assign('types',$types);
            $this->display("Resource/videoedit");
        }

    }

    public function delvideo(){
        $this->checkPriv('1_1_4');
        $id = I('get.id','','int');
        echo $id;
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Video->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    public function chgvideostatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Video->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Video->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    // 应用
    public function appmgr(){
        $this->checkPriv('1_3_1');
        $p = getCurPage();
        $res = $this->appsLogic->getAppList(array(),$p);
        $this->data = $res;
        $this->total = $this->appsLogic->getAppTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function addapp(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['package'] = I('post.package');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['bid'] = I('post.banner');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['tags'] = I('post.category');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $newdata['apptype'] = 2;
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $upfile = $this->upfile();
            if($upfile['error']==false){
                $newdata['filepath'] = $upfile['result']['filepath']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->add($newdata);
            if($ret){
                $this->redirect('Resource/appmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $where['end_time']=array('gt',date('Y-m-d H:i:s'));
            $banners = $this->bannerLogic->getBannerList($where);
            foreach($banners as $k=>$v){
                $banner_args[$k]=array('banner'=>$v['name'],'id'=>$v['id']);
            }
            $types = $this->categoryLogic->getParentCategoryList('2');
            $types[] = array('id'=>'0','title'=>'=请选择=');
            sort($types);
            $this->assign('types',$types);
            $this->assign('banners',$banner_args);
            $this->display("Resource/appedit");
        }
    }

    public function editapp(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['package'] = I('post.package');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['bid'] = I('post.banner');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['tags'] = I('post.category');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $upres = $this->upimgfile();

            $upfile = $this->upfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            if($upfile['error']==false){
                $newdata['filepath'] = $upfile['result']['filepath']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Resource/appmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Resource/appmgr');
            }
        }else{

            $where['end_time']=array('gt',date('Y-m-d H:i:s'));
            $banners = $this->bannerLogic->getBannerList($where);
            foreach($banners as $k=>$v){
                $banner_args[$k]=array('banner'=>$v['name'],'id'=>$v['id']);
            }
            $types = $this->categoryLogic->getParentCategoryList('2');
            $types[] = array('id'=>'0','title'=>'=请选择=');
            sort($types);
            $this->assign('types',$types);
            $this->assign('banners',$banner_args);
            $id = I('get.id','','int');
            $data = $this->appsLogic->getAppsById($id);
            $this->assign('simgs',json_decode($data['imgs']));

            $this->assign('files',array($data['filepath']));
            $this->data = $data;
            $this->display("Resource/appedit");
        }
    }

    public function delapp(){
        $this->checkPriv('1_3_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Apps->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    //dns
    public function dnsmgr(){
        $this->checkPriv('1_3_2');
        $p = getCurPage();
        $res = $this->dnsLogic->getDnsList(array(),$p);
        $this->data = $res;
        $this->total = $this->dnsLogic->getDnsTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function adddns(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['hostname'] = I('post.hostname');
            $newdata['hostip'] = I('post.hostip');
            $newdata['dns'] = I('post.dns');
            $newdata['sort'] = I('post.sort');
            $ret = $this->Dns->add($newdata);
            if($ret){
                $this->redirect('Resource/dnsmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $this->display("Resource/dnsedit");
        }
    }

    public function editdns(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['hostname'] = I('post.hostname');
            $newdata['hostip'] = I('post.hostip');
            $newdata['dns'] = I('post.dns');
            $newdata['sort'] = I('post.sort');
            $ret = $this->Dns->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Resource/dnsmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->error('编辑数据失败');
            }
        }else{
            $id = I('get.id','','int');
            $data = $this->dnsLogic->getDnsById($id);
            $this->data = $data;
            $this->display("Resource/dnsedit");
        }
    }

    public function deldns(){
        $this->checkPriv('1_3_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Dns->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }


    public function chgappstatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Apps->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Apps->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    // 游戏
    public function gamemgr(){
        $this->checkPriv('1_2_1');
        $p = getCurPage();
        $res = $this->appsLogic->getGameList(array(),$p);
        $this->data = $res;
        $this->total = $this->appsLogic->getGameTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    //game_isrecommend
    public function game_isrecommend(){
        $id = I('get.id','','int');
        $isrecommend = I('get.isrecommend','','int');
        $redirect_url = I('server.HTTP_REFERER');
        if($id){
            if(isset($isrecommend)){
                $this->Apps->where('id='.$id)->save(array('isrecommend'=>$isrecommend));
                redirect($redirect_url);
            }else{
                $this->error('无法设置该推荐游戏');
            }
        }else{
            $this->error('该记录不存在');
        }
    }

    public function addgame(){
        $this->checkPriv('1_2_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['package'] = I('post.package');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['tags'] = I('post.category');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $newdata['apptype'] = 1;
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $upfile = $this->upfile();
            if($upfile['error']==false){
                $newdata['filepath'] = $upfile['result']['filepath']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->add($newdata);
            if($ret){
                $this->redirect('Resource/gamemgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $types = $this->categoryLogic->getParentCategoryList('2');
            $types[] = array('id'=>'0','title'=>'=请选择=');
            sort($types);
            $this->assign('types',$types);
            $this->display("Resource/gameedit");
        }
    }

    public function editgame(){
        $this->checkPriv('1_2_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['name'] = I('post.name');
            $newdata['slogon'] = I('post.slogon');
            $newdata['package'] = I('post.package');
            $newdata['pubdate'] = I('post.pubdate');
            $newdata['version'] = I('post.version');
            $newdata['size'] = I('post.size');
            $newdata['tags'] = I('post.tags');
            $newdata['intro'] = I('post.intro');
            $newdata['pubuser'] = I('post.pubuser');
            $newdata['tags'] = I('post.category');
            $newdata['recommendtxt'] = I('post.recommendtxt');
            $newdata['updatetxt'] = I('post.updatetxt');
            $upres = $this->upimgfile();
            if($upres['error'] == false){
                $newdata['icon'] = $upres['result']['iconimg']['fullpath'];
            }
            $upfile = $this->upfile();
            if($upfile['error']==false){
                $newdata['filepath'] = $upfile['result']['filepath']['fullpath'];
            }
            $imgs = I('post.img');
            $newdata['imgs'] = json_encode($imgs);
            $ret = $this->Apps->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Resource/gamemgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->display('Resource/gamemgr');
            }
        }else{
            $id = I('get.id','','int');
            $data = $this->appsLogic->getAppsById($id);
            $this->assign('simgs',json_decode($data['imgs']));
            $this->data = $data;
            $types = $this->categoryLogic->getParentCategoryList('2');
            $types[] = array('id'=>'0','title'=>'=请选择=');
            sort($types);
            $this->assign('types',$types);
            $this->display("Resource/gameedit");
        }
    }

    public function delgame(){
        $this->checkPriv('1_2_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");;
            $this->Apps->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }

    public function chggamestatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Apps->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Apps->where('id='.$id)->save(array('status'=>2));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else{
                $this->error('无该状态设置');
            }
        }else{
            $this->error('该记录不存在');
        }
    }
    public function tmpupimgs(){
        $bimgs = $this->upimgfile();
        if($bimgs['error'] != true){
            $ret = array();
            foreach($bimgs['result'] as $img){
                $ret[] = $img['fullpath'];
            }
            $this->ajaxReturn($ret);
        }else{
            echo 0;
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
