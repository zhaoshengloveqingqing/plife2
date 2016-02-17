<?php
namespace Admin\Model;
use Think\Model\ViewModel;
class AdminuserViewModel extends ViewModel {   
	public $viewFields = array(
		'Admin'=>array('*','_type'=>'LEFT'), 			
		'Admingroup'=>array('id'=>'groupid','groupname','groupdesc','priv','isban','_on'=>'Admingroup.id=Admin.privgid'),
	
	); 
}
?>