<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Api\Logic;


class BookLogic extends \Think\Model{
    public function __construct(){
        $this->Book = M('Book');
        $this->Bookparam = M('Bookparam');
        $this->Readrecord = M('Readrecord');
    }
    private $Book;

    public function getBookTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Book->join('pn2_bookparam on pn2_book.id = pn2_bookparam.bookid')->where($mycond)->where('pn2_bookparam.isdel is null')->count();
        return $num;
    }

    public function getBookList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Book->join('pn2_bookparam on pn2_book.id = pn2_bookparam.bookid')->where($mycond)->where('pn2_book.isdel is null and pid = 0')->page($pstr)->order('pn2_bookparam.id asc')->select();
        return $data;
    }

    public function getBookById($id){
        if($id){
            $data = $this->Book->getById($id);
            return $data;
        }else{
            return false;
        }
    }

    public function getBookParamById($id){
        if($id){
            $data = $this->Book->join('pn2_bookparam on pn2_book.id = pn2_bookparam.bookid')->where('id is '.$id.'')->where('pn2_book.isdel is null')->find();
            return $data;
        }else{
            return false;
        }
    }

    public function getChapterTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Book->join('pn2_bookparam on pn2_book.id = pn2_bookparam.bookid')->where($mycond)->where('pn2_bookparam.isdel is null and pid != 0')->count();
        return $num;
    }

    public function getChapterList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Book->join('pn2_bookparam on pn2_book.id = pn2_bookparam.bookid')->where($mycond)->where('pn2_bookparam.isdel is null and pid != 0')->page($pstr)->order('pn2_bookparam.sort asc')->select();
        return $data;
    }

    public function getChapterParamById($id){
        if($id){
            $data = $this->Bookparam->where('id = '.$id.'')->find();
            return $data;
        }else{
            return false;
        }
    }

    public function getRecordTotal($cond = array()){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $num = $this->Readrecord->join('pn2_bookparam on pn2_readrecord.paramid = pn2_bookparam.id')
            ->join('pn2_book on pn2_readrecord.bookid = pn2_book.id')
            ->where($mycond)->where('pn2_book.isdel is null')->order('pn2_bookparam.id asc')->count();
        return $num;
    }

    public function getRecordList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Readrecord->join('pn2_bookparam on pn2_readrecord.paramid = pn2_bookparam.id')
            ->join('pn2_book on pn2_readrecord.bookid = pn2_book.id')
            ->where($mycond)->where('pn2_book.isdel is null')->page($pstr)->order('pn2_bookparam.id asc')->select();
        return $data;
    }

    public function getRecordById($id){
        if($id){
            $data = $this->Readrecord->where('paramid = '.$id.'')->find();
            return $data;
        }else{
            return false;
        }
    }

    public function getReadRecordList($cond=array(), $p){
        $mycond = array();
        if(is_array($cond) && count($cond)>0){
            $mycond = $cond;
        }
        $pstr = $p.','.C('ADMIN_REC_PER_PAGE');
        $data = $this->Readrecord->where($mycond)->where('isdel is null')->page($pstr)->order('id asc')->select();
        return $data;
    }

}
