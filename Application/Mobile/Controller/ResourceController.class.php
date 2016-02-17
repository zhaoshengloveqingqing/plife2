<?php
namespace Mobile\Controller;
use Think\Controller;
class ResourceController extends Controller {
    public function __construct(){
        $this->Res = D('Resource','Logic');
        $this->Video=  M('Video');
        $this->App=  M('App');
    }
    private $Res;
    private $App;
    private $Video;

    /**
     * 分页获取全部视频列表（含搜索）
     * 参数：
     * @param int p :（可选）分页，默认为1
     * @param string s : 搜索关键词，如无，则给所有列表（按分页），暂时只可以用一个关键词，后期增加多关键词搜索
     * @param int t :（可选）类型：1:电影（默认）  2:电视剧
     * @return json : list
     */
    public function videolist(){
        $p = I('post.p','','int')?I('post.p','','int'):1;
        $s = trim(I('post.s'));
        if(I('post.t','','int') == 1 || I('post.t','','int') == 2){$t = I('post.t','','int');}else{ $t=1; }
        $cond = array();
        $cond['type'] = $t;
        if(!empty($s)){
            $cond[] = array(' (name like "%'.$s.'%")  OR (director like "%'.$s.'%") OR ( actors like "%'.$s.'%") ');
        }
        $vlist = $this->Res->getVideos($cond, $p);
        $this->ajaxReturn($vlist);
    }

    /**
     * 分页带获取个数获取推荐视频列表
     * 参数：
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int n : （必填）推荐视频个数
     * @param int t : （可选）类型：1:电影（默认）  2:电视剧
     * @return json : list
     */
    public function recommandvideo(){
        $p = I('post.p','','int')?I('post.p','','int'):1;
        $n = I('post.n','','int');
        if(I('post.t','','int') == 1 || I('post.t','','int') == 2){$t = I('post.t','','int');}else{ $t=1; }
        $cond = array();
        $cond['type'] = $t;
        $cond['isrecommend'] = 1;
        if($n>0){
            $vlist = $this->Res->getVideos($cond, $p, 1, $n);
        }else{
            $vlist = $this->Res->getVideos($cond, $p);
        }
        $this->ajaxReturn($vlist);
    }

    /**
     * 分页筛选视频列表
     * 参数：
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int t : （可选）类型：1:电影（默认）  2:电视剧
     * @param int f : （可选）种类：1:最新（默认）  2:最热
     * @param string cate : (可选) 口味：‘剧情’，‘动作’，...，默认：全部
     * @param string area : (可选) 国家：‘中国’，‘美国’，...，默认：全部
     * @param int y : （可选）年份：‘2013’，‘2014’，...，默认：全部
     * @param int ys :（可选）特别年份：默认为空/0，仅当该值为"1"时，显示当前年份前5年以前的视频，同时y参数失效
     * @return json : list
     */
    public function filtervideo(){
        $p = I('post.p','','int')?I('post.p','','int'):1;
        if(I('post.t','','int') == 1 || I('post.t','','int') == 2){$t = I('post.t','','int');}else{ $t=1; }
        if(I('post.f','','int') == 1 || I('post.f','','int') == 2){$f = I('post.f','','int');}else{ $f=1; }
        $cate = trim(I('post.cate'));
        $area = trim(I('post.area'));

        $cond = array();
        $cond['type'] = $t;
        if(!empty($cate)){ $cond['category'] =  array('like', '%'.$cate.'%'); }
        if(!empty($area)){ $cond['country'] =  array('like', '%'.$area.'%'); }
        if(I('post.ys','','int')==1){
            $year = (int)date("Y")-5;
            $cond['years'] = array('elt',$year);
        }else{
            $year = I('post.y','','int');
            if($year<=(int)date("Y") && $year>((int)date("Y")-5)){
                $cond['years'] = $year;
            }
        }
        $vlist = $this->Res->getVideos($cond, $p, $f);
        $this->ajaxReturn($vlist);
    }

    /**
     * 获取单一视频
     * 参数：
     * @param int id : （必填）视频id
     * @return json : data
     */
    public function getvideo(){
        $id = I('post.id','','int');
        if($id) $this->ajaxReturn($this->Res->getVideoById($id));
        else die(0);
    }



    /**
     * 分页获取全部应用列表（含搜索）
     * 参数：
     * @param int p :（可选）分页，默认为1
     * @param string s : 搜索关键词，如无，则给所有列表（按分页），暂时只可以用一个关键词，后期增加多关键词搜索
     * @param int t :（可选）类型：1:游戏（默认）2:应用
     * @return json : list
     */
    public function applist(){
        $p = I('post.p','','int')?I('post.p','','int'):1;
        $s = trim(I('post.s'));
        if(I('post.t','','int') == 1 || I('post.t','','int') == 2){$t = I('post.t','','int');}else{ $t=1; }
        $cond = array();
        $cond['apptype'] = $t;
        if(!empty($s)){
            $cond['name'] = array('like', '%'.$s.'%');
        }
        $vlist = $this->Res->getApps($cond, $p);
        $this->ajaxReturn($vlist);
    }

    /**
     * 分页带获取个数获取推荐应用列表
     * 参数：
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int n : （必填）推荐应用个数
     * @param int t :（可选）类型：1:游戏（默认）2:应用
     * @param int m : （可选）此参数获取上月精品游戏推荐:m
     * @return json : list
     */
    public function recommandapp(){
        $p = I('post.p','','int')?I('post.p','','int'):1;
        $n = I('post.n','','int');
        if(I('post.t','','int') == 1 || I('post.t','','int') == 2){$t = I('post.t','','int');}else{ $t=I('post.t','','int'); }
        $cond = array();
        $cond['apptype'] = $t;
        $cond['isrecommend'] = 1;
        if(I('post.m') == 'm'){
            $lastmonth = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
            $samemonth = date('Y-m-01 H:i:s', strtotime(date("Y-m-d")));
            $cond['creatime']=array(array('gt',$lastmonth),array('lt',$samemonth));
        }
        if($n>0){
            $vlist = $this->Res->getApps($cond, $p, 1, $n);
        }else{
            $vlist = $this->Res->getApps($cond, $p);
        }
        $this->ajaxReturn($vlist);
    }

    /**
     * 分页筛选应用列表
     * 参数：
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int f : （可选）种类：1:最新（默认） 2:最热
     * @param int t : （可选）类型：1:游戏（默认） 2:应用
     * @param string cate : (可选) 口味：‘益智’，‘动作’，...，默认：全部
     * @return json : list
     */
    public function filterapp(){
        $p = I('post.p','','int')?I('post.p','','int'):1;
        if(I('post.t','','int') == 1 || I('post.t','','int') == 2){$t = I('post.t','','int');}else{ $t=1; }
        if(I('post.f','','int') == 1 || I('post.f','','int') == 2){$f = I('post.f','','int');}else{ $f=1; }
        $cate = trim(I('post.cate'));
        $cond = array();
        $cond['type'] = $t;
        if(!empty($cate)){ $cond['tags'] =  array('like', '%'.$cate.'%'); }
        $vlist = $this->Res->getApps($cond, $p, $f);
        $this->ajaxReturn($vlist);
    }

    /**
     * 获取单一应用
     * 参数：
     * @param int id : （必填）应用id
     * @return json : data
     */
    public function getapp(){
        $id = I('post.id','','int');
        if($id) $this->ajaxReturn($this->Res->getAppById($id));
        else die(0);
    }

    /**
     * 分页带获取个数获取评论列表
     * 参数：
     * @param int p : （可选）分页，默认为1 （不需要额外定义列表的数量）
     * @param int t : （可选）类型：1:视频（默认）  2:app
     * @return json : list
     */
    public function comment(){
        $p = I('post.p','','int')?I('post.p','','int'):1;
        if(I('post.t','','int') == 1 || I('post.t','','int') == 2){$t = I('post.t','','int');}else{ $t=1; }
        $cond = array();
        $cond['type'] = $t;
        $vlist = $this->Res->getComments($cond, $p);
        $this->ajaxReturn($vlist);
    }

}