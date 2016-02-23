<?php
namespace Api\Controller;
use Think\Controller;
class BookController extends Controller
{
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

    /**
     * 图书获取接口
     * @return json : data
     */
    public function getbooklists()
    {
        $data = $this->bookLogic->getBookList();
        $this->ajaxReturn($data);
    }

    /**
     * 用户浏览图书记录接口
     * @return json : data
     */
    public function getreadrecordlists()
    {
        $data = $this->bookLogic->getReadRecordList();
        $this->ajaxReturn($data);
    }
}