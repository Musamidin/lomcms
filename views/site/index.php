<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */

//$this->title = 'My Yii Application';
?>

<div ng-controller="AppCtrl" class="site-index">
  <input type="hidden" class="tokenclass" name="token" value="<?=md5(Yii::$app->session->getId().'opn');?>"/>
  <div id="index" class="body-content-page">
    <div ng-init="getinit()" class="row stsbar">
        <div class="col-md-1"></div>
        <div class="col-md-2 text-center"><span ng-click="getbystatus(1)" id="sts1" class="stsbtns">Продлен<span class="pull-right badge bg-red">{{stsbar.sts1}}</span></span></div>
        <div class="col-md-2 text-center"><span ng-click="getbystatus(3)" id="sts3" class="stsbtns">Просрочен<span class="pull-right badge bg-red">{{stsbar.sts3}}</span></span></div>
        <div class="col-md-2 text-center"><span ng-click="getbystatus(4)" id="sts4" class="stsbtns">Продвинут срок<span class="pull-right badge bg-red">{{stsbar.sts4}}</span></span></div>
        <div class="col-md-2 text-center"><span ng-click="getbystatus(2)" id="sts2" class="stsbtns">Выкуп<span class="pull-right badge bg-red">{{stsbar.sts2}}</span></span></div>
        <div class="col-md-2 text-center"><span ng-click="getbystatus(5)" id="sts5" class="stsbtns">Реализован<span class="pull-right badge bg-red">{{stsbar.sts5}}</span></span></div>
        <div class="col-md-1"></div>
    </div>

    <div class="row">
    </div>
    <table class="table table-striped ml-table">
      <tbody id="thead">
        <tr>
          <th>Дата</th>
           <th>№ Билета</th>
           <th>Ф.И.О.</th>
           <th>Номер пасспорта</th>
           <th>Телефон</th>
           <th>Дата начало</th>
           <th>Дата возврата</th>
           <th>Ссуда</th>
           <th>Комиссия</th>
           <th>%-Ставка</th>
           <th>Залог</th>
           <th style="width:10%;">Описание П.З.</th>
           <th>Действия</th>
        </tr>
      </tbody>
      <tbody>
          <tr class="color{{ml.status}}" dir-paginate="ml in mainlistview | itemsPerPage: mainlistPerPage" total-items="totalmainlist" current-page="pagination.current" pagination-id="cust">
            <td>{{ml.sysDate | formatDatetime}}</td>
            <td><a class="sortbtn" ng-click="showHistory(ml.ticket)" href="javascript:void(0)">{{ml.ticket}}</a></td>
            <td>{{ml.fio}}</td>
            <td>{{ml.passport_id}}</td>
            <td>
              <span class="arr-down-ph" data-html="true" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="{{ml.phone | phone:1}}">
                <span class="glyphicon glyphicon-chevron-down"></span></span>
            </td>
            <td>{{ml.dateStart}}</td>
            <td>{{ml.dateEnd}}</td>
            <td>{{ml.loan}} {{ml.currency | currFilt }}</td>
            <td>{{ml.comission}}</td>
            <td>{{ml.percents | fixedto}} %</td>
            <td>
              <span class="arr-down-gold" data-html="true" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="{{ml.golds | parser: ml.other_prod : ml.id }}">
                <span class="glyphicon glyphicon-eye-close"></span></span>
            </td>
            <td>{{ml.description}}</td>
            <td>
                <div ng-if="ml.status != '2' && ml.status != '5'" class="btn-group">
                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                      Действие <span class="caret"></span>
                   </button>
                    <ul class="dropdown-menu">
                      <li><a href="javascript:void(0)" ng-click="onCalc(ml)"><span class="fa fa-calculator"></span>&nbsp;Посчитать</a></li>
                      <li><a href="javascript:void(0)" ng-click="printTempPreview(ml.id)"><span class="fa fa-print"></span>&nbsp;Распечатать</a></li>
                      <li class="divider"></li>
                      <li ng-if="<?=Yii::$app->user->identity->role; ?> == '1' && ml.status == '3' || ml.status == '4'"><a href="javascript:void(0)" ng-click="onRealize(ml)"><span class="fa fa-cart-arrow-down"></span>&nbsp;Списать</a></li>
                      <li ng-if="<?=Yii::$app->user->identity->role; ?> == '1' && ml.status =='0'"><a href="javascript:void(0)" ng-click="onDelete(ml.id)"><span class="glyphicon glyphicon-trash"></span>&nbsp;Удалить</a></li>
                    </ul>
               </div>
               <span ng-if="ml.status == '5' || ml.status == '2'">{{ml.actionDate | formatDatetime}}</span>
            </td>
          </tr>
      </tbody>
  </table>
    <dir-pagination-controls pagination-id="cust" on-page-change="pageChanged(newPageNumber)">
    </dir-pagination-controls>
    <!--max-size="3" direction-links="true" boundary-links="true"-->
    <!--button ng-click="test(1)">Test</button-->
  </div>

<!-- START Dialog box for add data -->
<div style="text-align:center;display:none;" id="dialog-form-clients">
  <?php $form = ActiveForm::begin(['id' => 'action-form']); ?>
      <input type="hidden" id="client-id" name="id" value=""/>
      <input type="hidden" id="token" name="token" value="<?=md5(Yii::$app->session->getId().'opn');?>"/>
      <div id="client-data">
          <div class="row">
            <div class="col-md-6">
              <?=$form->field($clients, 'fio',['options'=>
                  ['tag' => 'div','class'=> 'form-group field-mainform-fio has-feedback required'],
                  'template'=>'{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}{hint}'
                  ])->textInput(['placeholder'=>'Ф.И.О.','ng-model'=>'formData.fio','title'=>'Ф.И.О'])->label('Ф.И.О.');
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
                  'template'=>'{input}<span class="fa fa-barcode form-control-feedback addphone"></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите номер пасспорта','ng-model'=>'formData.passport_id'])->label('Введите номер пасспорта');
                  ?>
            </div>
            <div class="col-md-6">
              <?=$form->field($clients, 'phone',['options'=>
                  ['tag' => 'div','class'=> 'form-group input-group field-mainform-phone has-feedback required'],
                  'template'=>'<span class="input-group-addon show-cont-btn" data-toggle="popover" aria-describedby="popover750031"><i class="iconer fa fa-angle-double-down"></i></span>{input}<span class="input-group-addon add-cont-btn"><i class="fa fa-plus-circle"></i></span>{error}{hint}'
                  ])->textInput(['autofocus' => false,'placeholder'=>'Введите номер телефона','ng-model'=>'formData.phone'])->label('Введите номер телефона');
                  ?>
                  <div class="popover fade bottom in" role="tooltip" id="popover750031">
                    <div class="arrow"></div>
                    <h3 class="popover-title">Контактные номера</h3>
                    <div class="popover-content">
                        <table class="table table-striped" id="phone-table">
                            <thead>
                              <tr>
                                <th><span class="glyphicon glyphicon-earphone"></span></th>
                                <th title="Удалить"><span class="glyphicon glyphicon-trash"></span></th>
                              </tr>
                            </thead>
                            <tbody id="tbody-phone">
                            </tbody>
                          </table>
                        </div>
                    </div>
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
            <div class="col-md-6">
                <a href="javascript:void(0);" id="history-btn">История кредита</a>
            </div>
            <div class="col-md-6">
                <a href="javascript:void(0);" id="reset-btn">Сбросить</a>
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
                        <select id="mainlist-sample" class="form-control" name="sample" ng-model="data.sample">
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
                  <?= $form->field($mainList, 'other_prod')->textarea(['autofocus' => false,'placeholder'=>'Авто/Техника/...','ng-model'=>'calcData.other_prod'])->label(false); ?>
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
                      ])->textInput(['autofocus' => false,'placeholder'=>'Сумма (Ссуда)','ng-model'=>'calcData.loan'])->label('Сумма(Ссуда)');
                      ?>
                </div>
                <div class="col-md-4 paddLR0">
                  <?= $form->field($mainList, 'currency',['options'=>
                      ['tag' => 'div','class'=> 'form-group field-mainform-currency has-feedback required'],
                      'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                      ])->dropDownList(['1'=>'KGS','2'=>'USD'],
                      ['prompt' => 'Ваюта','ng-model' => 'calcData.currency']
                      )->label(false); ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="col-md-5 paddLR0">
                <?= $form->field($mainList, 'percents',['options'=>
                    ['tag' => 'div','class'=> 'form-group field-mainform-percents has-feedback required'],
                    'template'=>'{input}<span class="form-control-feedback"></span>{error}{hint}'
                    ])->dropDownList(Yii::$app->ListItem->getListPercent(),
                    ['prompt' => '% ставка','ng-model' => 'calcData.percents']
                    )->label(false); ?>
                  </div>
                  <div class="col-md-7 paddLR0">
                      <span class="lbl-view lbl-view-comm">Комиссия:<span id="comission">100 сом</span></span>
                  </div>
              </div>
            </div>
          <div class="row">
            <div class="col-md-12">
              <?= $form->field($mainList, 'description')->textarea(['autofocus' => false,'placeholder'=>'Комментарии','ng-model'=>'calcData.description'])->label(false); ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <span class="lbl-view">Дата выдачи:<?=date('d/m/Y');?></span>
            </div>
            <div class="col-md-6">
              <span class="lbl-view">Срок выкупа:<?= date('d/m/Y', strtotime('+ 30 day'));?></span>
            </div>
          </div>
      </div>
  <?php ActiveForm::end(); ?>
</div>
<!-- END Dialog box for add data -->
<!-- START Dialog box for Client history data -->
<div style="display:none;" id="dialog-form-history" title="Кредитная история">
  <div class="row">
      <div class="col-md-12">
        <table class="table table-striped cr-table">
          <tbody id="thead">
            <tr>
              <th>Дата</th>
               <th><a class="getallbtn" ng-click="getallHistory()" href="javascript:void(0)">№ Билета</a></th>
               <th>Ссуда</th>
               <th>%-Ставка</th>
               <th>Кол.Дней</th>
               <th>Комиссия</th>
               <th>Часть Пог.</th>
               <th>Залог</th>
            </tr>
          </tbody>
          <tbody>
              <tr class="color{{cr.status}}" dir-paginate="cr in clientRating | itemsPerPage: cratingPerPage" pagination-id="history-pg" total-items="totalcrating" current-page="cratingPgntion.current">
                <td>{{cr.actionDate | formatDatetime}}</td>
                <td><a class="sortbtn" ng-click="sortHistory(cr.ticket)" href="javascript:void(0)">{{cr.ticket}}</a></td>
                <td>{{cr.loan}} {{cr.currency | currFilt }}</td>
                <td>{{cr.percents | fixedto}} %</td>
                <td>{{cr.countDay}}</td>
                <td>{{cr.comission}}</td>
                <td>{{cr.part_of_loan | fixedto}}</td>
                <td>
                  <span class="arr-down-gold" data-html="true" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="bottom" data-content="{{cr.golds | parser: cr.other_prod : cr.id }}">
                    <span class="glyphicon glyphicon-eye-close"></span></span>
                </td>
              </tr>
          </tbody>
      </table>
        <dir-pagination-controls pagination-id="history-pg" on-page-change="historyPageChanged(newPageNumber)">
        </dir-pagination-controls>
      </div>
  </div>
</div>
<!-- END Dialog box for Client history data -->

<!-- START Dialog box for Print data-->
<div style="display:none;" id="dialog-form-calculate">
  <div id="calculate-data">
    <div class="row">
      <div class="col-md-6">
        <span class="lbl-view">№:<span id="ticket"></span></span>
      </div>
      <div class="col-md-6">
        <span class="lbl-view">Ссуда:<span id="loan"></span></span>
      </div>
    </div>
    <br/>
    <div class="row">
      <div class="col-md-6">
        <span class="lbl-view">Дата начало:<span id="dateStart"></span></span>
      </div>
      <div class="col-md-6">
        <span class="lbl-view">Дата возврата:<span id="dateEnd"></span></span>
      </div>
    </div>
    <br/>
    <div class="row">
      <div class="col-md-6">
        <span class="lbl-view">% - Ставка:<span id="percents"></span></span>
      </div>
      <div class="col-md-6">
        <span class="lbl-view">Количество дней:<span id="countDay"></span></span>
      </div>
    </div>
    <br/>
    <div class="row">
      <div class="col-md-6">
        <span class="lbl-view">Начислена:<span id="real-comission"></span></span>
      </div>
      <div class="col-md-6">
        <span class="lbl-view">Итого к выплате:<span id="total-summ"></span></span>
      </div>
    </div>
    <br/>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group has-feedback required has-default">
          <input type="text" id="part-of-loan" class="form-control ng-pristine ng-valid ui-autocomplete-input ng-empty ng-touched" name="part_of_loan" title="Погасить часть от ссуды" placeholder="Погасить часть от ссуды" autocomplete="off" aria-invalid="false">
          <span class="fa fa-money form-control-feedback"></span><p class="help-block help-block-error"></p>
        </div>
      </div>
      <div class="col-md-6">
        <span class="lbl-view">Минимальный срок:<span id="min-term">10</span> Дней</span>
      </div>
    </div>
  </div>
</div>
<!--  END Dialog box for Print data -->

<!--Small Modal Window-->
<div id="printPreviewModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm" style="width:800px;">
    <div class="modal-content">
      <!--div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title sm-title-lib">Залоговый билет</h4>
      </div-->
      <div class="modal-body" ng-init="init()" id="printarea">
        <?=$temp->temp; ?>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary printingbtn">Распечатать</button>
      </div>
    </div>
  </div>
</div>
<!--End Small Modal Window-->

</div>
<br/>
