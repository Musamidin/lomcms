<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */

//$this->title = 'My Yii Application';
?>

<div ng-controller="AppCtrl" class="site-index">
    <div id="index" class="body-content-page">
    <br/>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Поиск по названию изделия..." ng-model="searchInput" id="searchId" ng-keyup="onSearch($event)" aria-required="true" aria-invalid="true"><span class="glyphicon glyphicon-search form-control-feedback"></span><!--ng-change="myFunc($event)" -->
        </div>
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Добавить товар</button>

      </div>
      <div class="col-md-3">
      </div>
    </div>
</p>
        <table class="table">
          <head>
            <tr>
               <th>Название</th>
               <th>Группа</th>
               <th>Вставка</th>
               <th>Проба</th>
               <th>Вид поставки</th>
               <th>Вес(гр)</th>
               <th>Цена покупки</th>
               <th>Цена продажи</th>
               <th>Дата прихода</th>
               <th>Комментарии</th>
               <th>Action</th>
            </tr>
          </head>
          <tbody>
              <tr class="color{{ml.status}}" dir-paginate="ml in mainlist | itemsPerPage: mainlistPerPage" total-items="totalmainlist" current-page="pagination.current">
                <td>{{ml.name}}{{ml.id}}</td>
                <td>{{ml.groupbyName}}</td>
                <td>{{ml.insertionName}}</td>
                <td>{{ml.sampleName}}</td>
                <td>{{ml.tdName}}</td>
                <td>{{ml.weight_grams | number : 2}}</td>
                <td>{{ml.price_buy| cutPrice }} {{ml.buy_currency | currFilt }}</td>
                <td>{{ml.price_sale| cutPrice }} {{ml.sale_currency | currFilt}}</td>
                <td>{{ ml.date_of_arrival | cutDate }}</td>
                <td>{{ml.comment}}</td>
                <td style="display:none;">{{ml.bar_code}}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                          Действие <span class="caret"></span>
                       </button>
                        <ul class="dropdown-menu">
                          <li><a href="javascript:void(0)" ng-click="onDo(ml,2)"><span class="fa fa-money"></span>&nbsp;Продать</a></li>
                          <li><a href="javascript:void(0)" ng-click="onDo(ml,3)"><span class="fa fa-share-alt"></span>&nbsp;Реализатор</a></li>
                          <li class="divider"></li>
                          <li><a href="javascript:void(0)" ng-click="onEdit(ml)"><span class="fa fa-edit"></span>&nbsp;Редактировать</a></li>
                          <li><a href="javascript:void(0)" ng-click="onDelete(ml.id)"><span class="glyphicon glyphicon-trash"></span>&nbsp;Удалить</a></li>
                        </ul>
                   </div>
                </td>
              </tr>
          </tbody>
      </table>
      <dir-pagination-controls on-page-change="pageChanged(newPageNumber)"></dir-pagination-controls>
      <!--max-size="3" direction-links="true" boundary-links="true"-->
      <!--button ng-click="test(1)">Test</button-->
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
    <!--?php $form = ActiveForm::begin([
                                      'id' => 'mainForm',
                                      'options' => ['name' => 'mainForm']
                                    ]); ?-->
    <input type="hidden" name="token" value="<?=md5(Yii::$app->session->getId().'opn'); ?>" id="token"/>
    <input type="hidden" name="data_id" value="" ng-model="data.data_id" id="data-id"/>
    <input type="hidden" name="bar_code" value="" ng-model="data.bar_code" id="data-barcode"/>

      <table class="table" style="margin-bottom: 0px;">
        <tr>
          <td colspan="4">
            <!--?= $form->field($agentsModel, 'fio',['options'=>
                ['tag' => 'div','class'=> 'form-group field-agentmodel-fio has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList($agentsList,['prompt' => 'Ф.И.О. Агента...','id' => 'agent-fio']);
            ?-->
          </td>
        </tr>
        <tr>
            <td>
              <!--?= $form->field($mainForm, 'groupby',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-groupby has-feedback required'],
                  'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                  ])->dropDownList([],
                  ['prompt' => 'Группа...',
                   'ng-model' => 'data.groupby',
                   'ng-options'=> 'pgroup.id as pgroup.name for pgroup in productGroupar'
                  ])->label(false); ?-->
            </td>
            <td>
              <!--?= $form->field($mainForm, 'sample',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-sample has-feedback required'],
                  'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                  ])->dropDownList([],
                  ['prompt' => 'Проба...',
                   'ng-model' => 'data.sample',
                   'ng-options'=> 'smpl.id as smpl.name for smpl in samplesarr'
                  ])->label(false); ?-->
            </td>
            <td>
            <!--?= $form->field($mainForm, 'exchangerate',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-exchangerate has-feedback required'],
                'template'=>'{input}<span class="glyphicon glyphicon-usd form-control-feedback"></span>{error}{hint}'
                ])->textInput(
                  ['autofocus' => false,
                  'value'=>$curr['usd'],
                  'ng-init'=>'data.exchangerate="'.$curr['usd'].'"',
                  'placeholder'=>'Кур валюты',
                  'ng-model'=>'data.exchangerate'
                  ])->label('Кур валюты'); ?-->
            </td>
            <td>
            <!--?= $form->field($mainForm, 'weight_grams',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-weight_grams has-feedback required'],
                'template'=>'{input}<span class="fa fa-tachometer form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Вес,Гр.','ng-model'=>'data.weight_grams'])->label('Вес,Гр.');
                ?-->
            </td>
        </tr>
        <tr>
            <td>
              <!--?= $form->field($mainForm, 'size',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-size has-feedback required'],
                  'template'=>'{input}<span class="fa fa-eye form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Размер','ng-model'=>'data.size'])->label('Размер'); ?-->
            </td>
            <td>
              <!--?= $form->field($mainForm, 'name',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-name has-feedback required'],
                  'template'=>'{input}<span class="fa fa-tag form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Название','ng-model'=>'data.name'])->label('Название'); ?-->
            </td>
            <td>
              <!--?= $form->field($mainForm, 'insertion',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-insertion has-feedback required'],
                  'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                  ])->dropDownList([],
                  ['prompt' => 'Вставка...',
                   'ng-model' => 'data.insertion',
                   'ng-options'=> 'inser.id as inser.name for inser in insertionarr'
                  ])->label(false); ?-->

            </td>
            <td>
              <!--?= $form->field($mainForm, 'type_of_delivery',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-type_of_delivery has-feedback required'],
                  'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                  ])->dropDownList([],
                  ['prompt' => 'Вид поставки...',
                   'ng-model' => 'data.type_of_delivery',
                   'ng-options'=> 'typedlr.id as typedlr.name for typedlr in typeOfDeliveryar'
                  ])->label(false); ?-->
            </td>
        </tr>
        <tr>
          <td>
            <!--?= $form->field($mainForm, 'price_buy',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-price_buy has-feedback required'],
                'template'=>'{input}<span class="fa fa-money form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Цена покупки','ng-model'=>'data.price_buy'])->label('Цена покупки'); ?-->
            </td>
            <td>
            <!--?= $form->field($mainForm, 'buy_currency',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-buy_currency has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList(['1'=>'KGS','2'=>'USD'],
                ['ng-model' => 'data.buy_currency','prompt' => 'Ваюта покупки...'])->label(false); ?-->
            </td>
            <td>
            <!--?= $form->field($mainForm, 'price_sale',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-price_sale has-feedback required'],
                'template'=>'{input}<span class="fa fa-money form-control-feedback"></span>{error}{hint}'
                ])->textInput(['autofocus' => false,'placeholder'=>'Цена продажи','ng-model'=>'data.price_sale'])->label('Цена продажи'); ?-->
            </td>
            <td>
            <!--?= $form->field($mainForm, 'sale_currency',['options'=>
                ['tag' => 'div','class'=> 'form-group field-mainform-sale_currency has-feedback required'],
                'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                ])->dropDownList(['1'=>'KGS','2'=>'USD'],
                ['ng-model' => 'data.sale_currency','prompt' => 'Ваюта продажи...'])->label(false);
              ?-->
            </td>
        </tr>
        <tr>
          <td colspan="4">
            <!--?= $form->field($mainForm, 'comment')->textarea(['autofocus' => false,'placeholder'=>'Комментарии','ng-model'=>'data.comment'])->label('Комментарии'); ?-->
          </td>
        </tr>
      </table>
      <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">

        </div>
      </div>

      <!--?php ActiveForm::end(); ?-->
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" ng-click="save()">Сохранить изменения</button>
    </div>
    </div>
  </div>
</div>

<!--Sall Modal Window-->
<div id="actionModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" style="width:500px;">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title sm-title">Действие</h4>
      </div>
      <div class="modal-body">
        <!--?php $form = ActiveForm::begin(['id' => 'action-form']); ?-->
          <input type="hidden" name="token" value="<?=md5(Yii::$app->session->getId().'opn'); ?>" id="token"/>
          <input type="hidden" name="dataid" value="" id="dataid"/>
          <input type="hidden" name="state" value="" id="state"/>
          <div class="row mb-row">
              <div class="col-md-12">
                <p class="text-success price-sale-box">Продажная цена:&nbsp;<span id="price-sale"></span></p>
              </div>
          </div>
          <div class="row mb-row">
              <div class="col-md-6">
                <!--?= $form->field($mainForm, 'price_sold',['options'=>
                    ['tag' => 'div','class'=> 'form-group field-mainform-price_sold has-feedback required'],
                    'template'=>'{input}<span class="fa fa-money form-control-feedback"></span>{error}{hint}'
                    ])->textInput(['autofocus' => false,'placeholder'=>'Цена продажи','ng-model'=>'formData.price_sold'])->label('Цена продажи'); ?!-->
              </div>
              <div class="col-md-6">
                <!--?= $form->field($mainForm, 'sale_currency',['options'=>
                    ['tag' => 'div','class'=> 'form-group field-mainform-sale_currency has-feedback required'],
                    'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                    ])->dropDownList(['1'=>'KGS','2'=>'USD'],
                    ['prompt' => 'Ваюта продажи...',
                    'ng-model' => 'formData.sale_currency'])->label(false); ?-->
              </div>
          </div>
          <div class="row mb-row fio-box">
              <div class="col-md-12">
                <!--?= $form->field($agentsModel, 'fio',['options'=>
                    ['tag' => 'div','class'=> 'form-group field-agentmodel-fio has-feedback required'],
                    'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                    ])->dropDownList($agentsList,
                    ['prompt' => 'Ф.И.О. Агента...',
                     'ng-model' => 'formData.fio']); ?-->
              </div>
          </div>
        <!--?php ActiveForm::end(); ?-->
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary savebtn" ng-click="onActionDo()">Сохранить изменения</button>
      </div>
    </div>
  </div>
</div>
<!--End Sall Modal Window-->
</div>
<br/>

<!-- Dialog box for add data -->
<div style="text-align:center;display:none;" id="dialog-form-clients">
  <?php $form = ActiveForm::begin(['id' => 'action-form']); ?>
      <input type="hidden" id="cmd" name="cmd" value=""/>
      <input type="hidden" id="codeid" name="codeid" value=""/>
      <div id="client-data">
          <div class="row">
            <div class="col-md-6">
              <input type="hidden" id="userid" name="userid" value="<?=$user_info['id'];?>"/>
              <?=$form->field($clients, 'fio',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-fio has-feedback required'],
                  'template'=>'{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Ф.И.О.','ng-model'=>'formData.fio'])->label('Ф.И.О.');
                  ?>
            </div>
            <div class="col-md-6">
              <?=$form->field($clients, 'date_of_issue',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-date_of_issue has-feedback required'],
                  'template'=>'{input}<span class="glyphicon glyphicon-calendar form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Дата выдачи (пасспорт) д/м/г','ng-model'=>'formData.date_of_issue'])->label('Дата выдачи (пасспорт) д/м/г');
                  ?>
            </div>
          </div>
          <br/>
          <div class="row">
            <div class="col-md-6">
              <?=$form->field($clients, 'passport_id',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-passport_id has-feedback required'],
                  'template'=>'{input}<span class="fa fa-barcode form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите номер пасспорта','ng-model'=>'formData.passport_id'])->label('Введите номер пасспорта');
                  ?>
            </div>
            <div class="col-md-6">
              <?=$form->field($clients, 'phone',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-phone has-feedback required'],
                  'template'=>'{input}<span class="fa fa-phone form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите номер телефона','ng-model'=>'formData.phone'])->label('Введите номер телефона');
                  ?>
            </div>
          </div>
          <br/>
          <div class="row">
            <div class="col-md-6">
              <?=$form->field($clients, 'passport_issued',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-passport_issued has-feedback required'],
                  'template'=>'{input}<span class="fa fa-registered form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Кем выдан (пасспорт)','ng-model'=>'formData.passport_issued'])->label('Кем выдан (пасспорт)');
                  ?>
            </div>
            <div class="col-md-6">
              <?=$form->field($clients, 'address',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-address has-feedback required'],
                  'template'=>'{input}<span class="fa fa-map-marker form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите адрес','ng-model'=>'formData.address'])->label('Введите адрес');
                  ?>
            </div>
          </div>
          <br/>
      </div>
      <div id="credit-data">
          <div class="row">
            <div class="col-md-6">
              <input type="hidden" id="userid" name="userid" value="<?=$user_info['id'];?>"/>
              <?=$form->field($clients, 'fio',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-fio has-feedback required'],
                  'template'=>'{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Ф.И.О.','ng-model'=>'formData.fio'])->label('Ф.И.О.');
                  ?>
            </div>
            <div class="col-md-6">
              <?=$form->field($clients, 'date_of_issue',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-date_of_issue has-feedback required'],
                  'template'=>'{input}<span class="glyphicon glyphicon-calendar form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Дата выдачи (пасспорт) д/м/г','ng-model'=>'formData.date_of_issue'])->label('Дата выдачи (пасспорт) д/м/г');
                  ?>
            </div>
          </div>
          <br/>
          <div class="row">
            <div class="col-md-6">
              <?=$form->field($clients, 'passport_id',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-passport_id has-feedback required'],
                  'template'=>'{input}<span class="fa fa-barcode form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите номер пасспорта','ng-model'=>'formData.passport_id'])->label('Введите номер пасспорта');
                  ?>
            </div>
            <div class="col-md-6">
              <?=$form->field($clients, 'phone',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-phone has-feedback required'],
                  'template'=>'{input}<span class="fa fa-phone form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите номер телефона','ng-model'=>'formData.phone'])->label('Введите номер телефона');
                  ?>
            </div>
          </div>
          <br/>
          <div class="row">
            <div class="col-md-6">
              <?=$form->field($clients, 'passport_issued',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-passport_issued has-feedback required'],
                  'template'=>'{input}<span class="fa fa-registered form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Кем выдан (пасспорт)','ng-model'=>'formData.passport_issued'])->label('Кем выдан (пасспорт)');
                  ?>
            </div>
            <div class="col-md-6">
              <?=$form->field($clients, 'address',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-address has-feedback required'],
                  'template'=>'{input}<span class="fa fa-map-marker form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите адрес','ng-model'=>'formData.address'])->label('Введите адрес');
                  ?>
            </div>
          </div>
          <br/>
      </div>
  <?php ActiveForm::end(); ?>
</div>
