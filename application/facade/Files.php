<?php 
namespace app\facade;
use think\Facade;
class Files extends Facade{
	protected static function getFacadeClass(){
		return 'app\common\Files';
	}
} 
 ?>