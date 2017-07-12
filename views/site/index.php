<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */

//$this->title = 'My Yii Application';
?>
<script src="/js/JsBarcode.all.min.js"></script>
<script>
        Number.prototype.zeroPadding = function(){
            var ret = "" + this.valueOf();
            return ret.length == 1 ? "0" + ret : ret;
        };
</script>

<div ng-cloak ng-app="App" ng-controller="AppCtrl" class="site-index">

    <div class="body-content">
    <br/>
    <!--div class="row">
      <div class="col-md-6">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Поиск по названию изделия..." ng-model="searchInput" id="searchId" ng-keyup="onSearch($event)" aria-required="true" aria-invalid="true"><span class="glyphicon glyphicon-search form-control-feedback"></span>
        </div>
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary" onclick="addBtn()" ng-click="onAddProd()"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Добавить товар</button>

      </div>
      <div class="col-md-3">
        <div class="form-group has-feedback">
         <input type="text" class="form-control" autofocus="autofocus" placeholder="Поиск по баркоду..." ng-model="searchBarcode" id="searchBarcode" ng-keyup="onBarcode($event)" aria-required="true" aria-invalid="true"><span class="glyphicon glyphicon-search form-control-feedback"></span>
        </div>
      </div>
    </div-->
</p>
        <table class="table table-hover">
          <head>
            <tr>
               <th>Название</th>
               <th>Группа</th>
               <th>Вставка</th>
               <th>Проба</th>
               <th>Вид поставки</th>
               <th>Размер</th>
               <th>Вес(гр)</th>
               <th>Цена покупки</th>
               <th>Цена продажи</th>
               <th>Дата прихода</th>
               <th>Комментарии</th>
               <th>update</th>
               <th>delete</th>
            </tr>
          </head>
          <tbody>
              <tr ng-repeat="ml in mainlist">
                <td>{{ml.name}}</td>
                <td>{{ml.groupbyName}}</td>
                <td>{{ml.insertionName}}</td>
                <td>{{ml.sampleName}}</td>
                <td>{{ml.tdName}}</td>
                <td>{{ml.size}}</td>
                <td>{{ml.weight_grams | number : 2}}</td>
                <td>{{ml.price_buy | number : 2}}</td>
                <td>{{ml.price_sale | number : 2}}</td>
                <td>{{ml.date_of_arrival }}</td>
                <td>{{ml.comment}}</td>
                <td style="display:none;">{{ml.bar_code}}</td>
                <td><button class="btn btn-block btn-primary btn-sm" ng-click="onAction(ml)"><span class="fa fa-edit"></span>&nbsp;</button></td>
                <td><button class="btn btn-block btn-danger btn-sm" ng-click="onDelete(ml.id)"><span class="glyphicon glyphicon-trash"></span>&nbsp;</button></td>
              </tr>
          </tbody>
      </table>
    </div>
    <!--Modal Window-->
<div id="dataModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Добавить новые товары</h4>
    </div>
    <div class="modal-body">
    <?php $form = ActiveForm::begin(['id' => 'main-form']); ?>
    <input type="hidden" name="token" value="<?=md5(Yii::$app->session->getId().'opn'); ?>" id="token"/>
    <input type="hidden" name="data_id" value="" id="data-id"/>
    <input type="hidden" name="bar_code" value="" id="data-barcode"/>

      <table class="table">
        <tr>
            <td>
            <?= $form->field($mainForm, 'name',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-name has-feedback required'],
                'template'=>'{input}<span class="fa fa-tag form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Название','ng-model'=>'data.name'])->label('Название'); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'size',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-size has-feedback required'],
                'template'=>'{input}<span class="fa fa-eye form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Размер','ng-model'=>'data.size'])->label('Размер'); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'count',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-count has-feedback required'],
                'template'=>'{input}<span class="fa fa-cubes form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Количество','ng-model'=>'data.count'])->label('Количество'); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'weight_grams',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-weight_grams has-feedback required'],
                'template'=>'{input}<span class="fa fa-tachometer form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Вес,Гр.','ng-model'=>'data.weight_grams'])->label('Вес,Гр.'); ?>
            </td>
        </tr>
        <tr>
            <td>
            <?= $form->field($mainForm, 'insertion',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-insertion has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList([],
                ['prompt' => 'Вставка...',
                 'ng-model' => 'data.insertion',
                 'ng-options'=> 'inser.id as inser.name for inser in insertionarr' 
                ])->label(false); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'groupby',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-groupby has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList([],
                ['prompt' => 'Группа...',
                 'ng-model' => 'data.groupby',
                 'ng-options'=> 'pgroup.id as pgroup.name for pgroup in productGroupar' 
                ])->label(false); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'type_of_delivery',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-type_of_delivery has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList([],
                ['prompt' => 'Вид поставки...',
                 'ng-model' => 'data.type_of_delivery',
                 'ng-options'=> 'typedlr.id as typedlr.name for typedlr in typeOfDeliveryar' 
                ])->label(false); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'sample',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-sample has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList([],
                ['prompt' => 'Проба...',
                 'ng-model' => 'data.sample',
                 'ng-options'=> 'smpl.id as smpl.name for smpl in samplesarr' 
                ])->label(false); ?>
            </td>
        </tr>
        <tr>
          <td>
            <?= $form->field($mainForm, 'price_buy',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-price_buy has-feedback required'],
                'template'=>'{input}<span class="fa fa-money form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Цена покупки','ng-model'=>'data.price_buy'])->label('Цена покупки'); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'buy_currency',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-buy_currency has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList(['1'=>'KGS','2'=>'USD'],
                ['prompt' => 'Ваюта покупки...'])->label(false); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'price_sale',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-price_sale has-feedback required'],
                'template'=>'{input}<span class="fa fa-money form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Цена продажи','ng-model'=>'data.price_sale'])->label('Цена продажи'); ?>
            </td>
            <td>
            <?= $form->field($mainForm, 'sale_currency',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-sale_currency has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList(['1'=>'KGS','2'=>'USD'],
                ['prompt' => 'Ваюта продажи...'])->label(false); ?>
            </td>
        </tr>
      </table>

      <svg id="barcode"/>
        <script>
        function addBtn(){
            barcodeGen();
        }
        function barcodeGen(){
        var uid = Math.floor(10000000+Math.random()*90000000);
        $('#data-barcode').val(uid);
        console.log(uid);
            JsBarcode("#barcode", uid, {
              format:"CODE128C",
              displayValue:true,
              fontSize:12,
              width:1,
              height:10
            });            
        }
        </script>


      <?php ActiveForm::end(); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="save()">Сохранить изменения</button>
    </div>  
    </div>
  </div>
</div>

</div>
<!-- Large modal -->

<br/>




