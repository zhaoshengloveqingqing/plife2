<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class BookController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->bookLogic =  D('Book','Logic');
        $this->readrecordLogic =  D('Readrecord','Logic');
        $this->categoryLogic =  D('Category','Logic');
        $this->Book=  M('Book');
        $this->Readrecord=  M('Readrecord');
        $this->Bookparam=  M('Bookparam');
    }

    private $bookLogic ;
    private $readrecordLogic ;
    private $Book ;
    private $Readrecord ;
    private $Bookparam;

    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }

    public function bookmgr(){
        $this->checkPriv('1_3_1');
        $p = getCurPage();
        $where = array();
        $res = $this->bookLogic->getBookList($where,$p);
        $this->data = $res;
        $this->total = $this->bookLogic->getBookTotal();
        $show = constructAdminPage($this->total);
        $this->assign('page',$show);
        $this->display();
    }

    public function addbook(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['name'] = I('post.name');
            $newdata['bno'] = I('post.bno');
            $newdata['authorno'] = I('post.authorno');
            $newdata['author'] = I('post.author');
            $newdata['sort'] = I('post.sort');
            $newdata['press'] = I('post.press');
            $newdata['cid'] = I('post.cid');
            $upres = $this->upimgfile();

            if($upres['error'] == false){
                $newdata['img'] = $upres['result']['img']['fullpath'];
            }
            $ret = $this->Book->add(array('status'=>'1'));
            $newdata['bookid'] = $ret;
            $newdata['createdate'] = date('Y-m-d H:i:s');
            $data = $this->Bookparam->add($newdata);
            if($data){
                $this->redirect('Book/bookmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $cates = getSortedCategory($this->categoryLogic->getCategoryList());
            $this->assign('cate',$cates);
            $this->display("book/bookedit");
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

    public function chgbookstatus(){
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        if($id){
            if($status == 1){
                $this->Book->where('id='.$id)->save(array('status'=>1));
                $from = I('server.HTTP_REFERER');
                redirect($from);
            }else if($status == 2){
                $this->Book->where('id='.$id)->save(array('status'=>2));
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
    public function chaptermgr(){
        $this->checkPriv('1_3_1');
        $p = getCurPage();
        $where = array();
        $res = $this->bookLogic->getChapterList($where,$p);
        $this->data = $res;
        $this->total = $this->bookLogic->getChapterTotal();
        $show = constructAdminPage($this->total);
        $this->assign('id',I('get.id'));
        $this->assign('page',$show);
        $this->display();
    }

    public function addchapter(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');

        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['chapter'] = I('post.chapter');
            $newdata['chapteraddress'] = I('post.chapteraddress');
            $newdata['chaptercontent'] = I('post.chaptercontent');
            $newdata['sort'] = I('post.sort');
            $newdata['name'] = I('post.name');
            $newdata['pid'] = I('post.id');
            $data = $this->bookLogic->getBookParamById(I('post.id'));
            $newdata['bookid'] = $data['bookid'];
            $newdata['createdate'] = date('Y-m-d H:i:s');
            $data = $this->Bookparam->add($newdata);
            if($data){
                $this->redirect('Book/chaptermgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $id = I('get.id');
            $data = $this->bookLogic->getBookParamById($id);
            $this->data = $data;
            $cate = $this->categoryLogic->getCategoryById($data['cid']);
            $this->assign('cate',$cate);
            $this->display("Book/chapteredit");
        }
    }

    public function editchapter(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.bid');
            $newdata['chapter'] = I('post.chapter');
            $newdata['chapteraddress'] = I('post.chapteraddress');
            $newdata['chaptercontent'] = I('post.chaptercontent');
            $newdata['sort'] = I('post.sort');
            $newdata['name'] = I('post.name');
            $newdata['pid'] = I('post.id');
            $data = $this->bookLogic->getBookParamById(I('post.id'));
            $newdata['bookid'] = $data['bookid'];
            $newdata['createdate'] = date('Y-m-d H:i:s');
            $ret = $this->Bookparam->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Book/chaptermgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->error('编辑数据失败');
            }
        }else{
            $id = I('get.id');
            $data = $this->bookLogic->getChapterParamById($id);
            $this->data = $data;
            $res = $this->bookLogic->getBookParamById($id);
            $cate = $this->categoryLogic->getCategoryById($res['cid']);
            $this->assign('cate',$cate);
            $this->redirect('Book/chaptermgr',array('id'=>$id));
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