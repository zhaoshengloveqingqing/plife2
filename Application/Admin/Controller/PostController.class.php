<?php
namespace Admin\Controller;
use Think\Controller;
header("Content-Type:text/html; charset=utf-8");
class PostController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->postLogic=  D('Post','Logic');
        $this->categoryLogic=  D('Category','Logic');
        $this->Post=  M('Post');
        $this->Category=  M('Category');
    }

    private $postLogic;
    private $categoryLogic;
    private $Category;
    private $Post;

    private function checkPriv($priv){
        $adminid = session('adminid');
        if(empty($adminid)) $this->redirect('Adminuser/login',0);
        if(!session('issuper')){
            if(!empty($priv) && !in_array($priv,session('privs'))) $this->error('您没有此权限!.');
        }
        $this->assign('adname', session('name'));
    }
    //comment
    public function postmgr(){
        $this->checkPriv('1_3_2');
        $p = getCurPage();
        $res = $this->postLogic->getPostList(array(),$p);

        $this->total = $this->postLogic->getPostTotal();
        $show = constructAdminPage($this->total);
        $this->data = $res;
        $this->assign('page',$show);
        $this->display();
    }

    public function ajaxcomments(){
        $type=I('post.type','','int')?I('post.type','','int'):1;
        $data = $this->videoLogic->getVideoByType($type);
        $this->ajaxReturn($data);
    }

    public function addpost(){
        $this->checkPriv('1_3_2');
        $this->assign('act','add');
        $this->assign('errcode','0');
        if(I('post.act')=='add'){
            $newdata = array();
            $newdata['title'] = I('post.title');
            $newdata['type'] = I('post.type');
            $newdata['cate_id']= I('post.cate');
            $newdata['content']= I('post.content');
            $newdata['author']= session('username');
            $newdata['create_date'] = date('Y-m-d H:i:s');
            $ret = $this->Post->add($newdata);
            if($ret){
                $this->redirect('Post/postmgr');
            }else{
                $this->error('插入数据错误');
            }
        }else{
            $cates = getSortedCategory($this->categoryLogic->getCategoryList());
            $this->assign('cate',$cates);
            $this->display("Post/postedit");
        }
    }

    public function editpost(){
        $this->checkPriv('1_3_3');
        $this->assign('act','edit');
        $this->assign('errcode','0');
        if(I('post.act')=='edit'){
            $newdata = array();
            $id = I('post.id','','int');
            $newdata['title'] = I('post.title');
            $newdata['type'] = I('post.type');
            $newdata['cate_id']= I('post.cate');
            $newdata['content']= I('post.content');
            $newdata['author']= session('username');
            $newdata['create_date'] = date('Y-m-d H:i:s');
            $ret = $this->Post->where('id='.$id)->save($newdata);
            if($ret){
                $this->redirect('Post/postmgr');
            }else{
                $this->assign('errcode','1');  // 修改失败
                $this->error('编辑数据失败');
            }
        }else{
            $id = I('get.id','','int');
            $data = $this->postLogic->getPostById($id);
            $this->data = $data;
            $cates = getSortedCategory($this->categoryLogic->getCategoryList());
            $this->assign('cate',$cates);
            $this->display("Post/postedit");
        }
    }

    public function delpost(){
        $this->checkPriv('1_3_4');
        $id = I('get.id','','int');
        if($id){
            $data['isdel']= date("Y-m-d H:i:s");
            $this->Comment->where('id='.$id)->save($data);
            $from = I('server.HTTP_REFERER');
            redirect($from);
        }else{
            $this->error('该记录不存在');
        }
    }
}