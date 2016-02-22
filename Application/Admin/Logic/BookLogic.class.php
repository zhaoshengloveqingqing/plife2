<?php
/**
 * Created by PhpStorm.
 * User: IceLight
 * Date: 15/11/20
 * Time: 上午9:02
 */

namespace Admin\Logic;


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
        $data = $this->Book->join('pn2_bookparam on pn2_book.id = pn2_bookparam.bookid')->where($mycond)->where('isdel is null')->page($pstr)->order('pn2_bookparam.id asc')->select();
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
}
