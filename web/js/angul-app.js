var app = angular.module("App",['angularUtils.directives.dirPagination']);

app.controller("AppCtrl", function($scope,$http){
/**NO CONFLICT**/
$.fn.bootstrapBtn = $.fn.button.noConflict();

$scope.totalmainlist = 0;
$scope.mainlistPerPage = 15; // this should match however many results your API puts on one page
$scope.pagination = { current: 1 };

$scope.totalcrating = 0;
$scope.cratingPerPage = 10; // this should match however many results your API puts on one page
$scope.cratingPgntion = { current: 1 };
//$scope.pageSize = 10;

$scope.minSumm = 100;
$scope.list = {};
$scope.data = {};
$scope.formData = {};
$scope.calcData = {};
$scope.bystatus = 0;
$scope.cid = 0;
$scope.ticket = 0;
$scope.exchangeData = {};

var arrTs = [];
var phoneBook = [];
var arrData = [];

$('.digi').keyup(function () {
     if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
$('#mainlist-loan').keyup(function () {
     if (this.value != this.value.replace(/[^0-9\.]/g, '')) {
       this.value = this.value.replace(/[^0-9\.]/g, '');
    }
});
$('.money').mask("##########.##", {reverse: true});

$('body').popover({
    selector: '[data-toggle="popover"]'
});

$('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('destroy');
        }
    });
});

/***********************************************************/
$(document).on('click', '#exchangeModal', function(){
      $("#dialog-form-exchange").dialog({
          title: "Конвертация",
          autoOpen: false,
          width: 690,
          modal: true,
          close: function( event, ui ) {
            $("#exchange-box :input").find("input[type=text], textarea").val("");
          },
          buttons: [{
            text: "Сохранить",
            id : "btn-exchange",
            click: saveExchanged
          }]
      }).dialog("open");
});

/*****************Exchange section****************/
	$(document).on('change', '#getExchange', function(){
		exchangeCalc();
	});
/*****************Exchange section****************/
	$(document).on('keyup', '.onchanging', function(){
		exchangeCalc();
	});
/*****************Function Exchange section****************/
	function exchangeCalc()
  {
  		if($('#getExchange').val() != ''){
  			if($('#getExchange').val() == 1){
  				var esumm = (Number($('#exch_summ').val()) * Number($('#exch_curr').val()));
  				$('#exchangedSumm').html('Конвертированная сумма:&nbsp;'+ Math.ceil(esumm).toFixed(2)+ '&nbsp;Сом');
  				$('#exchangedSummVal').val(Math.ceil(esumm).toFixed(2));
  			}
  			if($('#getExchange').val() == 2){
  				var esumm = (Number($('#exch_summ').val()) / Number($('#exch_curr').val()));
  				$('#exchangedSumm').html('Конвертированная сумма:&nbsp;'+ Math.ceil(esumm).toFixed(2)+ '&nbsp;Доллар');
  				$('#exchangedSummVal').val(Math.ceil(esumm).toFixed(2));
  			}
  		}
	}
/*****************Function SaveExchange Data section****************/
	function saveExchanged()
	{
	  	 if($('#exch_summ').val() == '' || $('#exch_curr').val() == ''|| $('#getExchange').val() == ''){
	  	 	alert('Укажите все объязательные поля!');
	  	 }else{
	  	 	if($('#exchangedSummVal').val() == ''){
	  	 		alert("Произошла ошибка, пожалуйста перезапустите страницу");
	  	 	}else{
            $($('#exchange-box :input').serializeArray()).each(function(index, obj){
              $scope.exchangeData[obj.name] = obj.value;
            });
            $scope.exchangeData['token'] = $('#token').val();
            $http({
              method: 'POST',
              url: '/exchangeajax',
              data: $scope.exchangeData
            }).then(function successCallback(response) {

                $("#dialog-form-exchange").dialog( "close" );
                $scope.data = response.data.data;
                var state = response.data;
                if(state.status == 0){

                }else{
                  alert(state.msg);
                }
              }, function errorCallback(response) {
                  //console.log(response);
            });
	  	 	}
	  	 }
	}

/***********************************************************/

/**START OPEN DIALOG BOX **/
$(document).on('click', '.addModal', function(){
		clearClientFormFunc();
		clearCalFormFunc();
		$('#history-btn').hide();
		$("#dialog-form-clients").dialog({
				title: "Окно выдачи кредита",
				autoOpen: false,
				width: 690,
				modal: true,
				open: function(){
				},
				close: function( event, ui ) {
					$("#addWindowItem").find("input[type=text], textarea").val("");
				},
				buttons: [{
					text: "Далее",
					//icon: "ui-icon-seek-next",
					id : "btn1",
					click: function() {
						$($('#client-data :input').serializeArray()).each(function(index, obj){
            	$scope.calcData[obj.name.slice(8,-1)] = obj.value;
      			});
						$($('#credit-data :input').serializeArray()).each(function(index, obj){
            	$scope.calcData[obj.name.slice(9,-1)] = obj.value;
      			});
						$scope.calcData['gold'] = arrTs;
						$scope.calcData['phone'] = phoneBook;
						$scope.calcData['token'] = $('#token').val();
						$scope.calcData['id'] = $('#client-id').val();

						var resp = checker($scope.calcData);
						if(resp[0] == false){
							alert(resp[1]);
						}else{
							savedata($scope.calcData);
						}
					}
				}]
		}).dialog("open");
});
/**END OPEN DIALOG BOX **/

/** START checker function **/
var checker = function(data){
		var response = [];
			if(data['fio'] == ''){
				response.push(false);
				response.push('Заполните поле Ф.И.О.');
			}else if(data['date_of_issue'] == ''){
				response.push(false);
				response.push('Укажите дату выдачи документа');
			}else if(data['passport_id'] == ''){
				response.push(false);
				response.push('Заполните поле номер паспорта');
			}else if(jQuery.isEmptyObject(data['phone']) == true){
				response.push(false);
				response.push('Добвьте контактные номера');
			}else if(data['passport_issued'] == ''){
				response.push(false);
				response.push('Заполните поле орган выдавший документа');
			}else if(data['address'] == ''){
				response.push(false);
				response.push('Заполните поле Адрес');
			}else if(jQuery.isEmptyObject(data['gold']) == true && data['other_prod'] == ''){
				response.push(false);
				response.push('Заполните тип залога ЗОЛОТО или АВТО/ТЕХНИКА');
			}else if(data['loan'] == ''){
				response.push(false);
				response.push('Введите сумму займа!');
			}else if(data['currency'] == ''){
				response.push(false);
				response.push('Выберите валюту!');
			}else if(data['percents'] == ''){
				response.push(false);
				response.push('Выберите % ставку!');
			}else{
				response.push(true);
				response.push('ok');
			}
		return response;
};
/** END checker function **/

/** START SAVE DATA FUNCTIONS **/
var savedata = function(param){
		$http({
			method: 'POST',
			url: '/issuanceofcredit',
			data: param
		}).then(function successCallback(response) {

				$("#dialog-form-clients").dialog( "close" );
				$scope.data = response.data.data;
				var state = response.data;
        if(state.status == 0){
          $('#printPreviewModal').modal({ keyboard: false });
          printGold(param['gold'],curr(param['currency']));
          $scope.init = function(){ };
          $scope.getData(1,$scope.mainlistPerPage,$scope.bystatus);
        }else{
          alert(state.msg);
        }
			}, function errorCallback(response) {
					//console.log(response);
		});
};
/*** END SAVE DATA FUNCTIONS */

/** START Calculate function **/
$scope.onCalc = function(data){
  $('#part-of-loan').val('');
	$.each(data,function(key,val){
		if(key == 'loan' || key == 'ticket' || key == 'dateStart' || key == 'dateEnd'){
			$('#'+key).text(val);
		}else if(key == 'currency'){
			$('#loan').append(' '+curr(val));
		}else if(key == 'percents'){
			$('#'+key).text(Number(val).toFixed(2));
		}

	});
	calcFunc(data);
	$("#dialog-form-calculate").dialog({
			title: data.fio,
			autoOpen: false,
			width: 690,
			modal: true,
			open: function(){
				$('#btn1,#btn2').show();
			},
			close: function( event, ui ) {
				//$("#addWindowItem").find("input[type=text], textarea").val("");
			},
			buttons: [{
				text: "Закрыть кредит",
				//icon: "ui-icon-seek-next",
				id : "btn1",
				click: function() {
          if($('#part-of-loan').val() != ''){
            alert('Для выкупа ссуды удалите введеную вами частичную сумму!');
          }else{
            data['fstatus'] = 2;
            serverSide(data);
          }
				}
			},{
				text: "Продлить кредит",
				//icon: "ui-icon-seek-next",
				id : "btn2",
				click: function() {
					$('#btn1,#btn2').hide();
          data['fstatus'] = 1;
					serverSide(data);
				}
			}]
	}).dialog("open");
	//console.log(data);
};

$scope.onDelete = function(id){
			$http({
				method: 'POST',
				url: '/deleteaction',
				data: { id: id, token : $('#token').val() }
			}).then(function successCallback(response) {
        var respdata = eval(response.data);
        if(respdata.status == 0){
              $scope.mainlistview = eval(respdata.data.mainlistview);
              $scope.totalmainlist = eval(respdata.data.count);
        } else if(respdata.status > 0){
            alert(respdata.msg);
        }
				}, function errorCallback(response) {
						//console.log(response);
			});
};

$scope.onRealize = function(obj){
    $http({
      method: 'POST',
      url: '/realizeajax',
      data: { id: obj.id, status: 5, token : $('#token').val() }
    }).then(function successCallback(response) {
          var respdata = eval(response.data);
          if(respdata.status == 0){
            $scope.getData(1,$scope.mainlistPerPage,$scope.bystatus);
          } else if(respdata.status > 0){
              alert(respdata.msg);
          }
      }, function errorCallback(response) {
          //console.log(response);
    });
};

$(document).on('keyup','#part-of-loan',function(){
		if(parseInt(this.value) < parseInt($('#loan').text())){
			$('#btn1').attr('disabled',true).hide();
		}else if(this.value == ''){
			$('#btn1').attr('disabled',false).show();
		}else if(parseInt(this.value) > parseInt($('#loan').text())){
			alert('Часть от ссуды не может быть больше чем выданная ссуда!');
			$('#btn1').attr('disabled',true).hide();
			//$(this).val(parseInt($('#loan').text()));
		}
});

var calcFunc = function(data){
  $('.lbl-view.din').show();
	var cday = parseInt((new Date() - new Date(data['dateStart'])) / (1000 * 60 * 60 * 24));
  if(cday == 0){ cday = 1; }
  var com = 0, totsum = 0,	minDay = 10, minSumm = 100;
  if(cday < minDay){ cday = minDay; }

	if(parseInt(data['currency']) == 2){ //Если валюта доллар
			if(parseInt(data['status']) > 0){ //Если статус был проден
					com = (parseFloat(data['loan']) / 100 * parseFloat(data['percents']) * parseInt(cday));
					totsum = (parseFloat(data['loan']) + com);
			}else{
				 com = (parseFloat(data['loan']) / 100 * parseFloat(data['percents']) * parseInt(cday));
				 totsum = (parseFloat(data['loan']) + com);
			}
	}else if(parseInt(data['currency']) == 1){ // KGS Сом
			if(parseInt(data['status']) > 0){ //Если статус был проден
					if(parseFloat(data['loan']) > 1000){ //Если сумма ссуды > 1000
            cday = parseInt((new Date() - new Date(data['dateStart'])) / (1000 * 60 * 60 * 24));
						com = (parseFloat(data['loan']) / 100 * parseFloat(data['percents']) * parseInt(cday));
	 				  totsum = (parseFloat(data['loan']) + com);
            $('.lbl-view.din').hide();
					}else{ //Если сумма ссуды < 1000
            com = (parseFloat(data['loan']) / 100 * parseFloat(data['percents']) * parseInt(cday));
            if(cday <= 30){
              com = (com < minSumm) ? minSumm : com;
            }else if(cday <= 60){
              com = (com < (minSumm * 2)) ? (minSumm * 2) : com;
            }else if( cday <= 90){
              com = (com < (minSumm * 3)) ? (minSumm * 3) : com;
            }else if( cday <= 120){
              com = (com < (minSumm * 4)) ? (minSumm * 4) : com;
            }
              totsum = (parseFloat(data['loan']) + com);
					}
			}else{ //Если статус 0 (Первый раз)
					if(parseFloat(data['loan']) > 1000){ //Если сумма ссуды > 1000
							com = (parseFloat(data['loan']) / 100 * parseFloat(data['percents']) * parseInt(cday));
						if(com < minSumm){ com = minSumm; }
	 				  	totsum = (parseFloat(data['loan']) + com);
					}else{ //Если сумма ссуды < 1000
            com = (parseFloat(data['loan']) / 100 * parseFloat(data['percents']) * parseInt(cday));
              if(cday <= 30){
                com = (com < minSumm) ? minSumm : com;
              }else if(cday <= 60){
                com = (com < (minSumm * 2)) ? (minSumm * 2) : com;
              }else if( cday <= 90){
                com = (com < (minSumm * 3)) ? (minSumm * 3) : com;
              }else if( cday <= 120){
                com = (com < (minSumm * 4)) ? (minSumm * 4) : com;
              }
						totsum = (parseFloat(data['loan']) + com);
					}
			}
	}
	$('#real-comission').text(Math.ceil(com).toFixed(2) +' '+curr(data['currency']));
	$('#total-summ').text(Math.ceil(totsum).toFixed(2)  +' '+curr(data['currency']));
	$('#countDay').text(cday);
	$('#min-term').text(minDay);

};

var serverSide = function(data){
		data['token'] = $('#token').val();
    data['part_of_loan'] = $('#part-of-loan').val();
		$http({
			method: 'POST',
			url: '/calcaction',
			data: data
		}).then(function successCallback(response) {
					$("#dialog-form-calculate").dialog('close');
          var respdata = eval(response.data);
          if(respdata.status == 0){
                $scope.mainlistview = eval(respdata.data.mainlistview);
                $scope.totalmainlist = eval(respdata.data.count);
                $scope.stsbar = eval(respdata.data.stsbar);
                $scope.getinit = function(){ };
          } else if(respdata.status > 0){
              alert(respdata.msg);
          }
			}, function errorCallback(response) {
					//console.log(response);
		});
};
/** END Calculate function **/

/** MASK **/
 $("#clients-phone").mask("999999999",{placeholder:"XXX XX XX XX"});
/** END MASK **/

/** START TABER **/
$( "#tabs" ).tabs();
/** END TABER **/

/*********** START autocomplete ********/
$( "#clients-fio" ).autocomplete({
		 source: function(request, response) {
					 $.ajax({
							 url:"/getautocomplete",
							 dataType: "json",
							 data: {
								 	 token : $('#token').val(),
									 term : request.term
							 },
							 success: function(data) {
									 response(data);
							 }
					 });
			 },
		 minLength: 2,
		 select: function( event, ui ) {
				 //console.log(ui.item);
				 phoneBook = [];
				 $('#tbody-phone').html('');
				 $('#client-id').val(ui.item.id);
				 var tbl = '';
				 phoneBook = eval(ui.item.phone);
				 for(i=0; i<phoneBook.length; i++){
					 tbl += '<tr id="rn'+phoneBook[i]+'"><td>'+phoneBook[i]+'</td><td><a href="javascript:void(0)" class="delphone" id="rn'+phoneBook[i]+'"><span class="glyphicon glyphicon-trash"></span><a/></td></tr>';
				 }
					$('#phone-table').show();
					$('#tbody-phone').html(tbl);
					$.each(ui.item,function(key, val){
							if(key !== 'phone'){
								$('#clients-'+key).val(val);
							}
		      });
					$('.iconer').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
					$('#popover750031').show();
					$('#history-btn').show();
				 //$scope.formData = ui.item;
 			  //ui.item.id | ui.item | ui.item.value
			 }
});
/*********** END autocomplete ********/

/**********START DATE PICKER***************/
$('#clients-date_of_issue').mask("99/99/9999", {placeholder: 'Дата выдачи (пасспорт) д/м/г'});
/*
$('#clients-date_of_issue').datepicker({
	format: "yyyy-mm-dd",
	startView: 3,
	language: "ru",
	autoclose: true,
	orientation: "bottom right"
}).on('hide', function() { });
*/
/********END DATE PICKER****************/

/**IN TAB GOLDS FUNCTIONS **/
$(document).on('click', '.delRow', function(){
		var thisid = Number(this.id.substring(1));
		delete arrTs[thisid-1];
		$('#'+this.id).closest('tr').remove();
		if(jQuery.isEmptyObject(arrTs)){ $('#thing_table').hide(); }
		return false;
});

$(document).on('click', '#add-gold', function(){
		if($('#mainlist-currency').val() == ''){
			alert('Выберите Валюту!');
			$('#mainlist-currency').focus().css("border-color", "#dd4b39");
		}else{
					var formdata='';
					var flag = true;
					formdata = $('.gold-form-box > div > div > :input').serializeArray();
					$(formdata).each(function(index, obj){
							if(obj.value === ""){
								flag = false;
								return false;
							}
					});
					//console.log(formdata);
					if(flag){
						 $('#thing_table').show();
							var data = {};
							var appdata, n = 1;
							$(formdata).each(function(index, obj){
										data[obj.name] = obj.value;
                    data['currs'] = curr($('#mainlist-currency').val());
							});
							arrTs.push(data);
							$('#numCount').val(Number(data.num)+1);
							appdata =
							'<tr id="r'+data.num+'">'+
							'<td>'+data.groups+''+
							'</td><td>'+data.sample+'пр.'+
							'</td><td>'+data.count+'шт.'+
							'</td><td>'+data.gramm+'гр.'+
							'</td><td>'+data.summ+' '+ data.currs +
							'</td><td>'+
							'<a href="javascript:void(0);" class="delRow" id="r'+data.num+'"><span class="glyphicon glyphicon-trash"></span></a>'+
							'</td></tr>';

							$('.thdata').val('');
							$('#tbody-gold').append(appdata);
							$('#mainlist-count,#mainlist-gramm,#mainlist-summ,#mainlist-sample,#mainlist-golds').val('');
              //$("#mainlist-currency option[value='Ваюта']").prop('selected', true);
              $("#mainlist-currency option[value='1']").prop('selected', true);
              $("#mainlist-percents option[value='% ставка']").prop('selected', true);
              //$('#numCount').val(1);

				}else{
						alert("Заполняйте все поля обязательно!");
				}
		}

});

var curr = function(arg){
  if(arg != ''){
    return (Number(arg) == 1) ? 'KGS' : 'USD';
  }else{
    return '';
  }
	//if(Number(arg) == 1){ return 'KGS'; }else if(Number(arg) == 2){ return 'USD'; }
}
/**END IN TAB GOLDS FUNCTIONS **/

/** START CONTACT FUNCTIONS **/
$(document).on('click', '.add-cont-btn', function(){
	 $('.iconer').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
	 $('#popover750031').show();
	 if(phoneBook.length >= 4){
		 alert('Максимальное количество вводимых номеров 4');
	 }else{
		 if(phoneBook.includes($scope.formData.phone)){
			 alert('Номер: '+$scope.formData.phone +' в списке уже есть!');
		 }else{
			 phoneBook.push($scope.formData.phone);
			 $('#clients-phone').val('');
			 $('#rowNum').val(Number($('#rowNum').val())+1);
				var tbl = '';
				tbl = '<tr id="rn'+$scope.formData.phone+'"><td>'+$scope.formData.phone+'</td><td><a href="javascript:void(0)" title="Удалить" class="delphone" id="rn'+$scope.formData.phone+'"><span class="glyphicon glyphicon-trash"></span><a/></td></tr>';
				$('#phone-table').show();
				$('#tbody-phone').append(tbl);
				//console.log(phoneBook);
		 }
	 }
});

$(document).on('click', '.delphone', function(){
		var thisid = this.id.substring(2);
		var idx = phoneBook.indexOf(thisid);
		// be careful, .indexOf() will return -1 if the item is not found
		if (idx !== -1) {
		    phoneBook.splice(idx, 1);
		}
		//delete phoneBook[thisid-1];
		$('#'+this.id).closest('tr').remove();
		if(jQuery.isEmptyObject(phoneBook)){ $('#phone-table').hide(); }
		//console.log(phoneBook);
		return false;
});

$(document).on('click', '.show-cont-btn', function(){
		$('#popover750031').toggle();
		if($('#popover750031').css('display') == 'none')
		{
			$('.iconer').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
		}else{
			$('.iconer').removeClass('fa-angle-double-down').addClass('fa-angle-double-up');
		}
});
/** END CONTACT FUNCTIONS **/

/** START HISTORY FUNCTIONS **/
$scope.showHistory = function(ticket){
  $scope.cid = 0;
  $scope.ticket = ticket;
  $scope.getHistoryData(1,$scope.cratingPerPage,$scope.cid,$scope.ticket);
  $('#dialog-form-history').dialog({
      autoOpen: false,
      width: 700,
      modal: true,
      close:function(){ $scope.ticket = 0; }
  }).dialog( "open" );
};

$(document).on('click', '#history-btn', function(){
    $scope.cid = $('#client-id').val();
    $scope.getHistoryData(1,$scope.cratingPerPage,$scope.cid,$scope.ticket);
		$('#dialog-form-history').dialog({
		    autoOpen: false,
        width: 700,
				modal: true,
        close:function(){ $scope.ticket = 0; }
		}).dialog( "open" );
});

$scope.getallHistory = function(){
  $scope.ticket = 0;
  $scope.getHistoryData(0,$scope.cratingPerPage,$scope.cid,$scope.ticket);
};

$scope.getHistoryData = function(pageNum,showPageCount,cid,ticket){
  $http.get('/gethistoryajax?page=' + pageNum +'&shpcount='+ showPageCount+'&cid='+ cid + '&ticket='+ ticket + '&token='+ $('#token').val())
        .then(function(result) {
          var respdata = eval(result.data);
          if(respdata.status == 0){
                $scope.clientRating = eval(respdata.data.clientRating);
                $scope.totalcrating = eval(respdata.data.count);
          } else if(respdata.status > 0){
              alert(respdata.msg);
          }
        }, function errorCallback(response) {
            //console.log(response);
        });
};

$scope.historyPageChanged = function(newPage) {
	    $scope.getHistoryData(newPage,$scope.cratingPerPage,$scope.cid,$scope.ticket);
};

$scope.sortHistory = function(ticket){
  $scope.ticket = ticket;
  $scope.getHistoryData(0,$scope.cratingPerPage,$scope.cid,$scope.ticket);
};
/** END HISTORY FUNCTIONS **/

/** START CALC FUNCTIONS **/
$(document).on('change', '#mainlist-currency', function(){
		$(this).focus().css("border-color", "#00a65a");
});

$(document).on('change', '#mainlist-percents', function(){
      var val = 0;
      val = ($('#mainlist-percents').val() == '') ? 0 : $('#mainlist-percents').val();

      if($('#mainlist-currency').val() == 1){
          var result = (Number($('#mainlist-loan').val())/100 * parseFloat(val) * 30);
          result = (result < $scope.minSumm) ? 100 : result;
      }else if($('#mainlist-currency').val() == 2){
        var result = (Number($('#mainlist-loan').val())/100 * parseFloat(val) * 30);
      }else{
        result = 0;
        alert('Выберите валюту!');
      }
		$('#comission').text(Math.ceil(result)+' '+curr($('#mainlist-currency').val()));
});

$(document).on('keyup', '#mainlist-loan', function(){
  		var val = 0;
  		val = ($('#mainlist-percents').val() == '') ? 0 : $('#mainlist-percents').val();

      if($('#mainlist-currency').val() == 1){
  		    var result = (Number($('#mainlist-loan').val())/100 * parseFloat(val) * 30);
          result = (result < $scope.minSumm) ? 100 : result;
      }else if($('#mainlist-currency').val() == 2){
        var result = (Number($('#mainlist-loan').val())/100 * parseFloat(val) * 30);
      }else{
        result = 0;
        alert('Выберите валюту!');
      }
		  $('#comission').text(Math.ceil(result)+' '+curr($('#mainlist-currency').val()));
});
/** END CALC FUNCTIONS **/

/** START CLEAR FUNCTIONS **/
$(document).on('click', '#reset-btn', function(){
	clearClientFormFunc();
});
/** END CLEAR FUNCTIONS **/

/**START Print Button function **/
var printbtn = function() {
	$('#printPreviewModal').modal('hide');
	var innerContents = document.getElementById('printarea').innerHTML;
	var popupWinindow = window.open('', '_blank', 'width=800,height=700,scrollbars=no,menubar=no,toolbar=no,location=no,status=no,titlebar=no');
	popupWinindow.document.open();
	popupWinindow.document.write('<html><head><link rel="stylesheet" type="text/css" href="css/printer.css" /></head><body onload="window.print()">' + innerContents + '</html>');
	popupWinindow.document.close();
};

$scope.printTempPreview = function(pid){
		$http({
			method: 'POST',
			url: '/getprintpreviewdata',
			data: {id:pid, token: $('#token').val() }
		}).then(function successCallback(response) {
				$scope.data = response.data.data;
				var state = response.data;
				printGold($scope.data['golds']);
				$scope.init = function(){ };
				$('#printPreviewModal').modal({ keyboard: false });
			}, function errorCallback(response) {
					//console.log(response);
		});
};

$(document).on('click','.printingbtn', function(){
		printbtn();
});
/**END Print Button function **/

/** START FUNCTIONS **/
var clearClientFormFunc = function(){
		$('#client-data :input').val('');
		$('#client-id').val('');
		$('#tbody-phone').html('');
		$('#phone-table,#history-btn,#popover750031').hide();
		$('.iconer').removeClass('fa-angle-double-up').addClass('fa-angle-double-down');
		phoneBook = [];
};

var clearCalFormFunc = function(){
		arrTs = [];
		//$('.gold-form-box :input').val('');
		$('#tbody-gold').html('');
		$('#thing_table').hide();
    $('#credit-data :input,textarea').val('');
    $("#mainlist-currency option[value='1']").prop('selected', true);
    $("#mainlist-percents option[value='% ставка']").prop('selected', true);
    $('#numCount').val(1);
    $('#comission').text(0);
};
/** END FUNCTIONS **/

$(document).on('keyup','#searcher',function(){
      if($.isNumeric(this.value) == true){
        if(this.value.length > 4){
          search('ticket',this.value);
        }else if(this.value.length == 0){
          $scope.getData(1,$scope.mainlistPerPage,$scope.bystatus);
        }
      }else{
        if(this.value.length > 4){
          search('fio',this.value);
        }else if(this.value.length == 0){
          $scope.getData(1,$scope.mainlistPerPage,$scope.bystatus);
        }
      }
});

var search = function(field,value){
  $http({
    method: 'POST',
    url: '/searchajax',
    data: { field: field, key: value, token : $('.tokenclass').val() }
  }).then(function successCallback(result) {
        var respdata = eval(result.data);
        if(respdata.status == 0){
              $scope.mainlistview = eval(respdata.data.mainlistview);
              $scope.totalmainlist = eval(respdata.data.count);
        } else if(respdata.status > 0){
            alert(respdata.msg);
        }
    }, function errorCallback(response) {
        //console.log(response);
  });
};

$scope.getbystatus = function(sid){
  $scope.bystatus = sid;
  $scope.getData(1,$scope.mainlistPerPage,$scope.bystatus);
};

var printGold = function(input){
			var strt = '';
      var objs = {};
      if ( jQuery.type(input) == 'string') {
          objs = JSON.parse(input);
      }else{
          objs = input;
      }

		if(jQuery.isEmptyObject(objs) == false){
		 strt = '<table class="gld-table" border="1" width="100%"><tbody class="gld-tbody"><tr style="font-size: 10pt;"><th>Группа</th><th>Проба</th><th>Кол.</th><th>Грамм</th><th>Сумма</th></tr></tbody>';
			$.each(objs, function(key,objs){
				strt += '<tr style="font-size: 10pt;"><td>'+objs.groups+'</td><td>'+ objs.sample +' пр.</td><td>'+objs.count +' шт.</td><td>'+objs.gramm+' гр.</td><td>'+objs.summ+' '+objs.currs+'</td></tr>';
			});
			strt += '</table>';
		}
		$('.print-gold').html(strt);
};

$scope.pageChanged = function(newPage) {
	       $scope.getData(newPage,$scope.mainlistPerPage,$scope.bystatus);
	};

$scope.getData = function(pageNum,showPageCount,sts){
	$http.get('/getdata?page=' + pageNum +'&shpcount='+ showPageCount+'&sts='+ sts +'&token='+ $('#token').val()) // +'&pagenum='+pnum
				.then(function(result) {
					var respdata = eval(result.data);
					if(respdata.status == 0){
								$scope.mainlistview = eval(respdata.data.mainlistview);
								$scope.totalmainlist = eval(respdata.data.count);
                $scope.stsbar = eval(respdata.data.stsbar);
					} else if(respdata.status > 0){
							alert(respdata.msg);
					}
				}, function errorCallback(response) {
				  	//console.log(response);
				});
	};

	$scope.getData(1,$scope.mainlistPerPage,$scope.bystatus);

}).filter("formatDatetime", function ()
{
    return function (input) {
      if(jQuery.isEmptyObject(input) == false){
				var dt = input.slice(0, -4).split('-');
				var td = dt[2].split(' ');
        return td[0]+'.'+dt[1]+'.'+dt[0]+' '+td[1];
      }else{
        return '';
      }
    }
}).filter("shortDateFormat", function ()
{
    return function (input) {
      if(jQuery.isEmptyObject(input) == false){
				var d = input.split('-');
        return d[2]+'/'+d[1]+'/'+d[0];
      }else{
        return '';
      }
    }
}).filter("phone", function ()
{
    return function (input,param) {
			 var str = '';
			 if(param == 1){
				 if(jQuery.isEmptyObject(input) == false){
							 if ( jQuery.type(input) == 'string') {
								 str = '<table>';
								 var obj = JSON.parse(input);
								 for(i=0; i< obj.length; i++){
										 str += '<tr><td>'+obj[i]+'</td></tr>';
								 }
								 str += '</table>';
						 	}
				 }
			 }else if(param == 2){
				 if(jQuery.isEmptyObject(input) == false){
							 if ( jQuery.type(input) == 'string') {
								 var obj = JSON.parse(input);
								 for(i=0; i< obj.length; i++){
										 str += obj[i]+'; ';
								 }
								 str = str.slice(0, -2);
						 	}
				 }
			 }

      return str;
    }
}).filter("currFilt", function()
{
			return function(input){
				if(Number(input) == 1){
					return 'KGS';
				}
				if(Number(input) == 2){
					return 'USD';
				}
			}
}).filter("fixedto", function()
{
	return function(input){
    if(jQuery.isEmptyObject(input) == false){
		    return parseFloat(input).toFixed(2);
    }else{
      return parseFloat(0).toFixed(2);
    }
	}
}).filter("returnSumm", function()
{
	return function(input,comission){
    if(jQuery.isEmptyObject(input) == false && jQuery.isEmptyObject(comission) == false){
		    return (parseFloat(input) + parseFloat(comission)).toFixed(2);
    }else{
      return 0;
    }
	}
}).filter("daySumm", function()
{
	return function(input,comission){
    if(jQuery.isEmptyObject(input) == false && jQuery.isEmptyObject(comission) == false){
		    return (parseFloat(comission)/30).toFixed(2);
    }else{
      return 0;
    }
	}
}).filter("parser", function(){
	return function(input,param1,id){
				var strt = '';
				var objs = JSON.parse(input);
		if(jQuery.isEmptyObject(objs) == false){
			 strt = '<table class="thing-table" id="thing'+id+'"><tbody id="thing-tbody"><tr><th>Группа</th><th>Проба</th><th>Кол.</th><th>Грамм</th><th>Сумма</th></tr></tbody>';
				$.each(objs, function(key,objs){
          if(jQuery.isEmptyObject(objs) == false){
					   strt += '<tr><td>'+objs.groups+'</td><td>'+ objs.sample +' пр.</td><td>'+objs.count +' шт.</td><td>'+objs.gramm+' гр.</td><td>'+objs.summ+'</td></tr>';
          }
        });
				strt += '</table><hr class="viewer-hr"/>';
		}else{
			$('#thing'+id).hide();
		}
		strt += param1;
		//console.log(id);
		return strt;
	}
}).controller("AppCtrlSettings", function($scope,$http){
  $.fn.bootstrapBtn = $.fn.button.noConflict();

$scope.poster = {};
$scope.settdata = {};
	/** START TABER **/
	$( "#tabsSett" ).tabs();
	/** END TABER **/
$('#library-param').mask("##########.##", {reverse: true});

tinymce.init({
				selector:'#tinymceeditor',
				height: 500,
				plugins: "print preview table code image hr",
				toolbar: "codesample | bold italic sizeselect fontselect fontsizeselect | hr alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | insertfile undo redo | forecolor backcolor emoticons | code",
				fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
				init_instance_callback: function(){
					$scope.poster['token'] = $('#token').val();
					$http({
						method: 'POST',
						url: '/gettemplate',
						data: $scope.poster
					}).then(function successCallback(response) {
							var res = eval(response.data);
							tinymce.activeEditor.setContent(res.data);
							$('.currView').html(res.data);
						}, function errorCallback(response) {
								//console.log(response);
					});
				}
	 });

$scope.test = function(){
	tinymce.activeEditor.setContent('<span>some</span> html');
};

$scope.savetemplate = function(){
		$scope.poster = {};
		$scope.poster['temp'] = tinymce.activeEditor.getContent();
		$scope.poster['token'] = $('#token').val();
		$http({
			method: 'POST',
			url: '/updatetemplate',
			data: $scope.poster
		}).then(function successCallback(response) {
				 var res = eval(response.data);
				//console.log(res);
				$('.currView').html($scope.poster['temp']);
			}, function errorCallback(response) {
					//console.log(response);
		});
		//console.log( $scope.poster );
};

$scope.addPASBtn = function(prop,title){
    $('form#sett-form :input').val('');
    if(prop == 'percent'){ $('div.hdn').css('display','block'); }else{ $('div.hdn').css('display','none'); }
    $('#dialog-form-pas').dialog({
        title: title,
        autoOpen: false,
        width: 600,
        modal: true,
        buttons:{
          "Сохранить": function(){
            $scope.settdata['table'] = prop;
            setData($scope.settdata);
          }
        }
    }).dialog("open");
};

var setData = function(data){
    data['token'] = $('#token').val();
    $http({
      method: 'POST',
      url: '/setdataajax',
      data: data
    }).then(function successCallback(response) {
          $("#dialog-form-pas").dialog('close');
          var respdata = eval(response.data);
          if(respdata.status == 0){
                $scope.getLibData();
          } else if(respdata.status > 0){
              alert(respdata.msg);
          }else{
            alert(respdata.msg);
          }
      }, function errorCallback(response) {
          //console.log(response);
    });
};

$scope.getLibData = function(){
	$http.get('/getlibajax?token='+ $('#token').val())
				.then(function(result) {
					var respdata = eval(result.data);
					if(respdata.status == 0){
								$scope.percent = eval(respdata.data.percent);
                $scope.article = eval(respdata.data.article);
                $scope.sample = eval(respdata.data.sample);
                $scope.user = eval(respdata.data.user);
					} else if(respdata.status > 0){
							alert(respdata.msg);
					}
				}, function errorCallback(response) {
				  	//console.log(response);
				});
};

$scope.getLibData();

}).controller("AppCtrlReport", function($scope,$http){
$.fn.bootstrapBtn = $.fn.button.noConflict();

    $('.input-group.date').datepicker({
    	minViewMode: 1,
      format: 'yyyy-mm',
    	language: "ru",
    	autoclose: true,
    	orientation: "bottom right"
    }).on('changeDate', function(e) {

      				var month = $('.getbydatetime').val().split('-');
      				var dateFrom = ''+month[0]+'-'+month[1]+'-01T00:00:00';
      				var dateTo=null;
      				switch(Number(month[1])){
      					case 1: dateTo = ''+month[0]+'-'+month[1]+'-31T23:59:59';
      					break;
      					case 2: dateTo = month[0]+'-'+month[1]+'-28T23:59:59';
      					break;
      					case 3: dateTo = month[0]+'-'+month[1]+'-31T23:59:59';
      					break;
      					case 4: dateTo = month[0]+'-'+month[1]+'-30T23:59:59';
      					break;
      					case 5: dateTo = month[0]+'-'+month[1]+'-31T23:59:59';
      					break;
      					case 6: dateTo = month[0]+'-'+month[1]+'-30T23:59:59';
      					break;
      					case 7: dateTo = month[0]+'-'+month[1]+'-31T23:59:59';
      					break;
      					case 8: dateTo = month[0]+'-'+month[1]+'-31T23:59:59';
      					break;
      					case 9: dateTo = month[0]+'-'+month[1]+'-30T23:59:59';
      					break;
      					case 10: dateTo = month[0]+'-'+month[1]+'-31T23:59:59';
      					break;
      					case 11: dateTo = month[0]+'-'+month[1]+'-30T23:59:59';
      					break;
      					case 12: dateTo = month[0]+'-'+month[1]+'-31T23:59:59';
      					break;
      				}

    $http.get('/getreportajax?datefrom=' + dateFrom +'&dateto='+ dateTo +'&token='+ $('#token').val())
                .then(function(result) {
                  var respdata = eval(result.data);
                  if(respdata.status == 0){
                    $('.col-md-rep > table,.col-md-12.gram-box').show();
                        $scope.vydacha = eval(respdata.data.vydacha);
                        $scope.vykup = eval(respdata.data.vykup);
                        $scope.comission_pog = eval(respdata.data.comission_pog);

                        $scope.comission_perez = eval(respdata.data.comission_perez);
                        $scope.ch_pog = eval(respdata.data.ch_pog);
                        $scope.proch_prih = eval(respdata.data.proch_prih);
                        $scope.proch_rashod = eval(respdata.data.proch_rashod);
                        $scope.curr_golds = eval(respdata.data.curr_golds);
                        $scope.tickets = eval(respdata.data.tickets);
                        $scope.data = eval(respdata.data);
                        //console.log($scope.data);
                  } else if(respdata.status > 0){
                      alert(respdata.msg);
                  }
                }, function errorCallback(response) {
                    //console.log(response);
                });
    });


}).controller("AppCtrlUserReport", function($scope,$http){

  $scope.kassa = {};

    $('.input-group.date').datepicker({
      language: "ru",
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      autoclose: 'today',
    }).on('changeDate', function(e) {
        $http.get('/getuserreportajax?datefrom=' + $('.getbydatetime').val()+'T00:00:00' +'&dateto='+ $('.getbydatetime').val()+'T23:59:59'+'&typereport='+$('#typereport').val()+'&token='+ $('#token').val()) // +'&pagenum='+pnum
              .then(function(result) {
                //console.log(result);
                var respdata = eval(result.data);
                if(respdata.status == 0){
                  $('.report-box').show();
                      $scope.kgs = eval(respdata.data.kgs);
                      $scope.usd = eval(respdata.data.usd);
                      $scope.kassa = eval(respdata.data.kassa);
                } else if(respdata.status > 0){
                    alert(respdata.msg);
                }
              }, function errorCallback(response) {
                  //console.log(response);
              });
    });

    /**************************saveExcel***********************************/
    $(document).on('click', '.saveExcel', function(e){

      	if($('.getbydatetime').val() == ''){
      		alert('Выберите дату!!!');
      		return false;
      	}else{
     		$("#table2excel").table2excel({
     		    // exclude CSS class
     		    exclude: ".noExl",
     		    name: "Excel Document Name",
     			filename: "Отёт "+$('.getbydatetime').val(),
     			exclude_img: true,
     			exclude_links: true,
     			exclude_inputs: true
     		});
      	}
    });

}).filter('totalSumm', function() {
        return function(data, key) {
            if (typeof(data) === 'undefined' || typeof(key) === 'undefined') {
                return 0;
            }
						var sum = 0;
            for (var i = data.length - 1; i >= 0; i--) {
                sum += parseFloat(data[i][key]);
            }
            return sum;
        };
}).controller("AppCtrlRecognition", function($scope,$http){
      $.fn.bootstrapBtn = $.fn.button.noConflict();
      $scope.totalmainlist = 0;
      $scope.mainlistPerPage = 15; // this should match however many results your API puts on one page
      $scope.pagination = { current: 1 };

      $scope.data = {};

      $scope.pageChanged = function(newPage) {
        	   $scope.getrecognitionajax(newPage,$scope.mainlistPerPage,0);
      };

      $scope.getrecognitionajax = function(pageNum,showPageCount,datetime){

        $http.get('/getrecognitionajax?page=' + pageNum +'&shpcount='+ showPageCount+'&token='+ $('#token').val()+'&datetime='+datetime)
      				.then(function(result) {
      					var respdata = eval(result.data);
      					if(respdata.status == 0){
      								$scope.rnlist = eval(respdata.data.rnlist);
      								$scope.totalmainlist = eval(respdata.data.count);
      					} else if(respdata.status > 0){
      							alert(respdata.msg);
      					}
      				}, function errorCallback(response) {
      				  	//console.log(response);
      				});
      };

      $scope.getrecognitionajax(1,$scope.mainlistPerPage,0);

      $('.input-group.date.rep-dpicker').datepicker({
          language: "ru",
          format: 'yyyy-mm-dd',
          todayHighlight: true,
          autoclose: 'today',
      }).on('changeDate', function(e) {
              $scope.getrecognitionajax(1,$scope.mainlistPerPage,$('.getbydatetime').val());
      });

      $scope.addlog = function(){
            $('#recognition-data :input').val('');
            $("#dialog-form-recognition").dialog({
        				title: "Регистрация (Расход/Приход)",
        				autoOpen: false,
        				width: 690,
        				modal: true,
        				open: function(){
        				},
        				close: function( event, ui ) {
        					//$("#addWindowItem").find("input[type=text], textarea").val("");
        				},
        				buttons: [{
        					text: "Далее",
        					id : "btn1",
        					click: function() {
        						$($('#recognition-data :input').serializeArray()).each(function(index, obj){
                    	$scope.data[obj.name] = obj.value;
              			});
        						$scope.data['token'] = $('#token').val();
                    $scope.data['datetime'] = 0;
                    //console.log($scope.data);
                    savedatatodb($scope.data);
        						// var resp = checker($scope.calcData);
        						// if(resp[0] == false){
        						// 	alert(resp[1]);
        						// }else{
        						// 	savedatatodb($scope.calcData);
        						// }
        					}
        				}]
        		}).dialog("open");
      };

      var savedatatodb = function(data){
          $http({
            method: 'POST',
            url: '/recognitionajax',
            data: data
          }).then(function successCallback(response) {

              $("#dialog-form-recognition").dialog('close');
                var respdata = eval(response.data);
      					if(respdata.status == 0){
      								$scope.rnlist = eval(respdata.data.rnlist);
      								$scope.totalmainlist = eval(respdata.data.count);
      					} else if(respdata.status > 0){
      							alert(respdata.msg);
      					}
            }, function errorCallback(response) {
                //console.log(response);
          });
      };

      $scope.onDelete = function(obj){
            $http({
              method: 'POST',
              url: '/deleteajax',
              data: { id:obj.id, token: $('#token').val() }
            }).then(function successCallback(response) {
                  var respdata = eval(response.data);
                  if(respdata.status == 0){
                    $scope.rnlist = eval(respdata.data.rnlist);
                    $scope.totalmainlist = eval(respdata.data.count);
                  } else if(respdata.status > 0){
                      alert(respdata.msg);
                  }
              }, function errorCallback(response) {
                  //console.log(response);
            });
      };
      /*****************************************************/

      $(document).on('change', '#transfer', function(){
          if($(this).val() != ''){
            $('#status_inout').val('Расход');
            $('#status_inout').hide();
            //$('#comments').val($('#comments').val()+'-'+$(this).val());
          }else{
            $('#status_inout').val('');
            $('#status_inout').show();
          }
      });
    /*****************************************************/

      $(document).on('click', '#trf', function(){
          if($(this).prop('checked') == true){
            $('#transfer').show();
          }else{
            $('#transfer').hide();
            $('#transfer').val('');
            $('#status_inout').val('');
            $('#status_inout').show();
          }
      });

});
