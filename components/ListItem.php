<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Library;
/**
 *
 */
class ListItem extends Component
{

  public function getList($param)
  {
    return Library::find()->select(['id','keyname'])->asArray()->where('status = '.$param)->all();
  }
  public function getListPercent()
  {
    $arrList = [];
    $itemList = Library::find()->select(['param','keyname'])->asArray()->where('status = 2')->all();

        foreach ($itemList as $item) {
            $arrList[$item['param']] = $item['keyname'];
        }
        return $arrList;
  }
}


?>
