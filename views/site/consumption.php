<?
use yii\widgets\LinkPager;
?>
<h1>Test DB</h1>

<? foreach ($cons as $item): ?>
<?=$item->comment;?><br/>
<? endforeach; ?>
<?=LinkPager::widget(['pagination'=>$pagination]) ?>