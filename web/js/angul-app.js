var app = angular.module("App",['angularUtils.directives.dirPagination']);

app.controller("AppCtrl", function($scope,$http){
/**NO CONFLICT**/
$.fn.bootstrapBtn = $.fn.button.noConflict();

$scope.totalmainlist = 0;
$scope.mainlistPerPage = 3; // this should match however many results your API puts on one page
$scope.pagination = { current: 1 };

$scope.list = {};
$scope.data = {};
$scope.formData = {};
$scope.calcData = {};
var arrTs = [];
var phoneBook = [];
var arrData = [];

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

/**START OPEN DIALOG BOX **/
$(document).on('click', '.addModal', function(){
		clearClientFormFunc();
		clearCalFormFunc();
		$('#history-btn').hide();
		$( "#dialog-form-clients" ).dialog({
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
						//var data = {};
						$($('#client-data :input').serializeArray()).each(function(index, obj){
            	$scope.calcData[obj.name.slice(8,-1)] = obj.value;
      			});
						//data = $scope.calcData;
						$scope.calcData['gold'] = arrTs;
						$scope.calcData['phone'] = phoneBook;
						$scope.calcData['token'] = $('#token').val();
						$scope.calcData['id'] = $('#client-id').val();
						$http({
							method: 'POST',
							url: '/issuanceofcredit',
							data: $scope.calcData
						}).then(function successCallback(response) {
								$scope.calcData['ticket'] = eval(response.data);
								console.log($scope.calcData);
								$("#dialog-form-clients").dialog( "close" );


							}, function errorCallback(response) {
									//console.log(response);
						});
						console.log($scope.calcData);
					}
				}]
		}).dialog( "open" );
});
/**END OPEN DIALOG BOX **/



/***Print Dialog BOX***/
$(document).on('click', '#exchangeModal', function(){
		$( "#dialog-form-print" ).dialog({
				title: "Залоговый билет",
				autoOpen: false,
				width: 800,
				modal: true,
				close: function( event, ui ) {

				},
				buttons: [{
					text: "Распечатать",
					icon: "glyphicon glyphicon-print",
					id : "print-btn",
					click: function() {
					}
				}],
				open: function(){
					$('#print-btn > span').removeClass('ui-button-icon ui-icon');
				}
		}).dialog( "open" );
});
/***END Print Dialog Box ****/


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
$('#clients-date_of_issue').datepicker({
	format: "yyyy-mm-dd",
	startView: 3,
	language: "ru",
	autoclose: true,
	orientation: "bottom right"
}).on('hide', function() { });
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
							var appdata;
							var n=1;
							$(formdata).each(function(index, obj){
										data[obj.name] = obj.value;
							});
							arrTs.push(data);
							$('#numCount').val(Number(data.num)+1);
							appdata =
							'<tr id="r'+data.num+'">'+
							'<td>'+data.groups+''+
							'</td><td>'+data.sample+'пр.'+
							'</td><td>'+data.count+'шт.'+
							'</td><td>'+data.gramm+'гр.'+
							'</td><td>'+data.summ+' '+ curr($('#mainlist-currency').val()) +
							'</td><td>'+
							'<a href="javascript:void(0);" class="delRow" id="r'+data.num+'"><span class="glyphicon glyphicon-trash"></span></a>'+
							'</td></tr>';

							$('.thdata').val('');
							$('#tbody-gold').append(appdata);
							///$('#currency').val()
							//console.log(arrTs);
				}else{
						alert("Заполняйте все поля обязательно!");
				}
		}

});

var curr = function(arg){
	if(Number(arg) == 1){ return 'KGS'; }else if(Number(arg) == 2){ return 'USD'; }
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
				console.log(phoneBook);
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
		console.log(phoneBook);
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
$(document).on('click', '#history-btn', function(){
		$('#contact-phone-dialog').dialog({
		    autoOpen: false,
		    buttons: {
		        "Сохранить": function() {
		            $(this).dialog('close');
		        }
		    }
		}).dialog( "open" );
});
/** END HISTORY FUNCTIONS **/

/** START CALC FUNCTIONS **/
$(document).on('change', '#mainlist-currency', function(){
		$(this).focus().css("border-color", "#00a65a");
});

$(document).on('change', '#mainlist-percents', function(){
		var val = 0;
		if($('#mainlist-percents').val() == ''){
			val = 0;
		}else{
			val = $('#mainlist-percents').val();
		}
		var result = (Number($('#mainlist-loan').val())/100 * parseFloat(val) * 30);
		$('#comission').text(Math.round(result)+' Сом');
});

$(document).on('keyup', '#mainlist-loan', function(){
		var val = 0;
		if($('#mainlist-percents').val() == ''){
			val = 0;
		}else{
			val = $('#mainlist-percents').val();
		}
		var result = (Number($('#mainlist-loan').val())/100 * parseFloat(val) * 30);
		$('#comission').text(Math.round(result)+' Сом');
});
/** END CALC FUNCTIONS **/

/** START CLEAR FUNCTIONS **/
$(document).on('click', '#reset-btn', function(){
	clearClientFormFunc();
});
/** END CLEAR FUNCTIONS **/

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
};
/** END FUNCTIONS **/

$scope.pageChanged = function(newPage) {
	       $scope.getData(newPage,$scope.mainlistPerPage);
	};

$scope.getData = function(pageNum,showPageCount){
	$http.get('/getdata?page=' + pageNum +'&shpcount='+ showPageCount+'&token='+ $('#token').val()) // +'&pagenum='+pnum
				.then(function(result) {
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

	$scope.getData(1,$scope.mainlistPerPage);

}).filter("formatDatetime", function () {
    return function (input) {
				var dt = input.slice(0, -4).split('-');
				var td = dt[2].split(' ');
        return td[0]+'.'+dt[1]+'.'+dt[0]+' '+td[1];
    }
}).filter("phone", function () {
    return function (input) {
				var str = '<table>';
				var obj = JSON.parse(input);
				for(i=0; i< obj.length; i++){
						str += '<tr><td>'+obj[i]+'</td></tr>';
				}
				str += '</table>';
        return str;
    }
}).filter("currFilt", function(){
			return function(input){
				if(Number(input) == 1){
					return 'KGS';
				}
				if(Number(input) == 2){
					return 'USD';
				}
			}
}).filter("fixedto", function(){
	return function(input){
		return parseFloat(input).toFixed(2);
	}
}).filter("parser", function(){
	return function(input,param1,id){
				var strt = '';
				var objs = JSON.parse(input);
		if(jQuery.isEmptyObject(objs) == false){
			 strt = '<table class="thing-table" id="thing'+id+'"><tbody id="thing-tbody"><tr><th>Группа</th><th>Проба</th><th>Кол.</th><th>Грамм</th><th>Сумма</th></tr></tbody>';
				$.each(objs, function(key,objs){
					strt += '<tr><td>'+objs.groups+'</td><td>'+ objs.sample +' пр.</td><td>'+objs.count +' шт.</td><td>'+objs.gramm+' гр.</td><td>'+objs.summ+'</td></tr>';
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
$scope.poster = {};
$scope.totalmainlist = 0;
$scope.mainlistPerPage = 6; // this should match however many results your API puts on one page
$scope.pagination = { current: 1 };
$scope.formData = {};

	/** START TABER **/
	$( "#tabsSett" ).tabs();
	/** END TABER **/

tinymce.init({
				selector:'#tinymceeditor',
				height: 500,
				plugins: "print preview table code image",
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

//tinymce.get("#tinymceeditor").execCommand('mceInsertContent', false, 'your content');
//$('#tinymceeditor').text('sdf sdhfs dgfhsdg hjsdgf hsdjgfsj d');
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

}).controller("AppCtrlReport", function($scope,$http){

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
})
.controller("AppCtrlLibrary", function($scope,$http){

});
