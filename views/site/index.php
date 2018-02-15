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
    <dir-pagination-controls on-page-change="pageChanged(newPageNumber)"></dir-pagination-controls>
    <!--max-size="3" direction-links="true" boundary-links="true"-->
    <!--button ng-click="test(1)">Test</button-->
  </div>

<!-- START Dialog box for add data -->
<div style="text-align:center;display:none;" id="dialog-form-clients">
  <?php $form = ActiveForm::begin(['id' => 'action-form']); ?>
      <input type="hidden" id="codeid" name="id" value=""/>
      <div id="client-data">
          <div class="row">
            <div class="col-md-6">
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
                  ])->textInput(['autofocus' => false,'placeholder'=>'Дата выдачи (пасспорт) д/м/г','ng-model'=>'formData.date_of_issue','readonly'=>'readonly'])->label('Дата выдачи (пасспорт) д/м/г');
                  ?>
            </div>
          </div>
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
      </div>
      <div id="credit-data">
          <div class="row">
            <div class="col-md-12">
              <div id="tabs">
                <ul>
                  <li><a href="#tabs-1">Золото</a></li>
                  <li><a href="#tabs-2">Авто/Техника</a></li>
                </ul>
                <div id="tabs-1">
                  <div class="row gold-form-box">
                    <div class="col-md-3 paddLR0">
                      <div class="form-group field-mainform-golds has-feedback required field-mainlist-golds">
                        <select id="mainlist-golds" class="form-control" name="groups">
                          <option value="" selected="selected">Предмет</option>
                          <? foreach (Yii::$app->ListItem->getList(0) as $item): ?>
                            <option value="<?=$item['keyname']; ?>"><?=$item['keyname']; ?></option>
                          <? endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2 paddLR0">
                      <div class="form-group field-mainform-golds has-feedback required field-mainlist-golds">
                        <select id="mainlist-golds" class="form-control" name="sample" ng-model="data.sample">
                          <option value="" selected="selected">Проба</option>
                          <? foreach (Yii::$app->ListItem->getList(1) as $item): ?>
                            <option value="<?=$item['keyname']; ?>"><?=$item['keyname']; ?></option>
                          <? endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2 paddLR0">
                      <div class="form-group field-mainform-count has-feedback required field-mainlist-count required">
                        <input type="text" id="mainlist-count" class="form-control ng-pristine ng-untouched ng-valid ng-empty" name="count" placeholder="Кол-тво" ng-model="data.count" aria-required="true">
                        <span class="fa fa-cubes form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-2 paddLR0">
                      <div class="form-group field-mainform-gramm has-feedback required field-mainlist-gramm required">
                        <input type="text" id="mainlist-gramm" class="form-control ng-pristine ng-untouched ng-valid ng-empty" name="gramm" placeholder="Грам" ng-model="data.gramm" aria-required="true">
                        <span class="fa fa-dashboard form-control-feedback"></span>
                      </div>
                    </div>
                    <div class="col-md-2 paddLR0">
                      <div class="form-group field-mainform-summ has-feedback required field-mainlist-summ required">
                        <input type="text" id="mainlist-summ" class="form-control ng-pristine ng-untouched ng-valid ng-empty" name="summ" placeholder="Сумма" ng-model="data.summ" aria-required="true">
                        <span class="fa fa-money form-control-feedback"></span>
                        <input type="hidden" name="num" value="1" id="numCount">
                      </div>
                    </div>
                    <div class="col-md-1 paddLR0">
                        <button type="button" id="add-gold"><span class="fa fa-plus-circle"></span></button>
                    </div>
                  </div>
                  <div class="row table-box">
                    <div class="col-md-12">
                      <table class="table table-striped" id="thing_table">
                          <thead>
                            <tr><th>Предмет</th>
                            <th>Проба</th>
                            <th>Кол.</th>
                            <th>Грамм</th>
                            <th>Оц.Сумм</th>
                            <th>Удалить</th>
                          </tr></thead>
                          <tbody id="tbody-gold">
                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div id="tabs-2">
                  <p>Morbi tincidunt, dui sit amet facilisis feugiat, odio metus gravida ante, ut pharetra massa metus id nunc. Duis scelerisque molestie turpis. Sed fringilla, massa eget luctus malesuada, metus eros molestie lectus, ut tempus eros massa ut dolor. Aenean aliquet fringilla sem. Suspendisse sed ligula in ligula suscipit aliquam. Praesent in eros vestibulum mi adipiscing adipiscing. Morbi facilisis. Curabitur ornare consequat nunc. Aenean vel metus. Ut posuere viverra nulla. Aliquam erat volutpat. Pellentesque convallis. Maecenas feugiat, tellus pellentesque pretium posuere, felis lorem euismod felis, eu ornare leo nisi vel felis. Mauris consectetur tortor et purus.</p>
                </div>
              </div>
            </div>
            </div>
            <br/>
            <div class="row">
              <div class="col-md-6">
                <div class="col-md-8 paddLR0">
                  <?=$form->field($mainList, 'loan',['options'=>
                      ['tag' => 'div','class'=> 'form-group field-mainform-loan has-feedback required'],
                      'template'=>'{input}<span class="fa fa-money form-control-feedback"></span>{error}{hint}'
                      ])->textInput(['autofocus' => false,'placeholder'=>'Сумма (Ссуда)','ng-model'=>'formData.loan'])->label('Сумма(Ссуда)');
                      ?>
                </div>
                <div class="col-md-4 paddLR0">
                  <?= $form->field($mainList, 'currency',['options'=>
                      ['tag' => 'div','class'=> 'form-group field-mainform-currency has-feedback required'],
                      'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                      ])->dropDownList(['1'=>'KGS','2'=>'USD'],
                      ['options' =>[ '1' => ['Selected' => true]]],
                      ['ng-model' => 'formData.currency']
                      )->label(false); ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="col-md-5 paddLR0">
                <?= $form->field($mainList, 'percents',['options'=>
                    ['tag' => 'div','class'=> 'form-group field-mainform-percents has-feedback required'],
                    'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                    ])->dropDownList(Yii::$app->ListItem->getListPercent(),
                    ['prompt' => '% ставка','ng-model' => 'formData.percents']
                    )->label(false); ?>
                  </div>
                  <div class="col-md-7 paddLR0">
                      <span class="lbl-view lbl-view-comm">Комиссия:<span id="comission">100 сом</span></span>
                  </div>
              </div>
            </div>
          <div class="row">
            <div class="col-md-12">
              <?= $form->field($mainList, 'description')->textarea(['autofocus' => false,'placeholder'=>'Комментарии','ng-model'=>'formData.description'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <span class="lbl-view">Дата выдачи:<?=date('d/m/Y');?></span>
            </div>
            <div class="col-md-6">
              <span class="lbl-view">Срок выкупа:<?= date('d/m/Y', strtotime('+ 90 day'));?></span>
            </div>
          </div>
      </div>
  <?php ActiveForm::end(); ?>
</div>
<!-- END Dialog box for add data -->
</div>
<br/>
