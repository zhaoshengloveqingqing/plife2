<?php
namespace Api\Controller;
use Think\Controller;
class IndexFunctionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->functionLogic = D('IndexFunction', 'Logic');
        $this->Function = M('Function');
    }

    private $functionLogic;
    private $Function;

    /**
     * 首页功能文字描述查询接口
     * @return json : data
     */
    public function getindexfunctions()
    {
        $data = $this->functionLogic->getFunctionList();
        $this->ajaxReturn($data);
    }
}