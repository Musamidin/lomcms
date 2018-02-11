<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Реализаторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div ng-controller="AppCtrlAgent" class="site-agents">
    <div id="agents" class = "body-content-page">
          <br/>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Поиск по Ф.И.О..." ng-model="searchInput" id="searchId" ng-keyup="onSearchAgent($event)" aria-required="true" aria-invalid="true">
                <span class="glyphicon glyphicon-search form-control-feedback"></span>
              </div>
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary" ng-click="onAddAgent()">
                  <span class="glyphicon glyphicon-plus-sign"></span>&nbsp;Добавить
              </button>
            </div>
            <div class="col-md-3"></div>
          </div>
          <table class="table table-hover">
            <head>
              <tr>
                 <th>Ф.И.О.</th>
                 <th>E-mail</th>
                 <th>Номер Телефона</th>
                 <th>Паспортные данные</th>
                 <th>Дата</th>
                 <th>Action</th>
              </tr>
            </head>
            <tbody>
                <tr class="color{{al.status}}" dir-paginate="al in agentsList | itemsPerPage: mainlistPerPage" total-items="totalmainlist" current-page="pagination.current">
                  <td>{{al.fio}}</td>
                  <td>{{al.email}}</td>
                  <td>{{al.phone}}</td>
                  <td>{{al.pid}}</td>
                  <td>{{ al.datetime | cutDate }}</td>
                  <td>
                      <div class="btn-group">
                          <button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                            Действие <span class="caret"></span>
                         </button>
                          <ul class="dropdown-menu">
                            <li><a href="javascript:void(0)" ng-click="onEdit(al)"><span class="fa fa-edit"></span>&nbsp;Редактировать</a></li>
                            <li><a href="javascript:void(0)" ng-click="onDelete(al.id,'<?=md5(Yii::$app->session->getId().'opn'); ?>')"><span class="glyphicon glyphicon-trash"></span>&nbsp;Удалить</a></li>
                          </ul>
                     </div>
                  </td>
                </tr>
            </tbody>
        </table>
        <dir-pagination-controls on-page-change="pageChanged(newPageNumber)"></dir-pagination-controls>
    </div>


    <!--Sall Modal Window-->
    <div id="agentModal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" style="width:600px;">
        <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title sm-title">Добавить реализатора</h4>
          </div>
          <div class="modal-body">
            <?php $form = ActiveForm::begin(['id' => 'agent-form']); ?>
              <input type="hidden" name="token" value="<?=md5(Yii::$app->session->getId().'opn'); ?>" id="token"/>
              <input type="hidden" name="dataid" value="" id="dataid"/>
              <div class="row mb-row">
                  <div class="col-md-12">

                  </div>
              </div>
              <div class="row mb-row">
                  <div class="col-md-6">
                    <?= $form->field($agentModel, 'fio',['options'=>
                        ['tag' => 'div','class'=> 'form-group field-mainform-fio has-feedback required'],
                        'template'=>'{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}{hint}'
                        ])->input('text',['placeholder'=>'Ф.И.О.','ng-model'=>'formData.fio'])->label('Ф.И.О.'); ?>
                  </div>
                  <div class="col-md-6">
                    <?= $form->field($agentModel, 'email',['options'=>
                        ['tag' => 'div','class'=> 'form-group field-mainform-email has-feedback required'],
                        'template'=>'{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>{error}{hint}'
                        ])->input('email',['placeholder'=>'E-mail','ng-model'=>'formData.email'])->label('E-mail'); ?>
                  </div>
              </div>
              <div class="row mb-row fio-box">
                  <div class="col-md-6">
                    <?= $form->field($agentModel, 'phone',['options'=>
                        ['tag' => 'div','class'=> 'form-group field-mainform-phone has-feedback required'],
                        'template'=>'{input}<span class="glyphicon glyphicon-earphone form-control-feedback"></span>{error}{hint}'
                        ])->input('tel',['min'=>12,'max'=>12,'placeholder'=>'XXX XXXXXX','ng-model'=>'formData.phone'])->label('Мобильный номер'); ?>
                  </div>
                  <div class="col-md-6">
                    <?= $form->field($agentModel, 'pid',['options'=>
                        ['tag' => 'div','class'=> 'form-group field-mainform-pid has-feedback required'],
                        'template'=>'{input}<span class="fa fa-file-text-o form-control-feedback"></span>{error}{hint}'
                        ])->input('text',['placeholder'=>'Паспортные данные','ng-model'=>'formData.pid'])->label('Паспортные данные'); ?>
                  </div>
              </div>
            <?php ActiveForm::end(); ?>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-primary savebtn" ng-click="onActionAE()">Добавить</button>
          </div>
        </div>
      </div>
    </div>
    <!--End Sall Modal Window-->

</div>
<br/>
