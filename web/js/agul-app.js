var app = angular.module("App",[]);

app.controller("AppCtrl", function($scope,$http){
	$scope.actionner = 'save';
	$scope.data = {};
	$scope.poster = {};

	$scope.onAddProd = function(){

		$('#dataModal').modal({ keyboard: false });

	};

	$scope.save = function(){
		switch($scope.actionner){
			case 'save' : $scope.saveData(); break;
			case 'update' : $scope.updateData(); break;	 	
		}
	};

	$scope.saveData = function(){
		$scope.data.token = document.getElementById('token').value;
		$http({
		  method: 'POST',
		  url: '/setdata',
		  data: $scope.data
		}).then(function successCallback(response) {
				$scope.clearData();
				$scope.getData();
				$('#dataModal').modal('hide');
		  }, function errorCallback(response) {
		  		console.log(response);
		  });
	};
	$scope.updateData = function(){
		$scope.data.token = document.getElementById('token').value;
		$http({
		  method: 'POST',
		  url: '/setdata',
		  data: $scope.data
		}).then(function successCallback(response) {
				$scope.clearData();
				$scope.getData();
				$('#dataModal').modal('hide');
		  }, function errorCallback(response) {
		  		console.log(response);
		  });
	};
	$scope.clearData = function(){
		$scope.data = {

		};
	};
	$scope.onSearch = function(event){
		$scope.poster.token = document.getElementById('token').value;
		$scope.poster.key = $scope.searchInput;
		$scope.poster.stype = 1;
			$http({
			  method: 'POST',
			  url: '/search',
			  data: $scope.poster
			}).then(function successCallback(response) {
					$scope.mainlist = eval(response.data);
					console.log(response);
			  }, function errorCallback(response) {
			  		console.log(response);
			});
		
		
	}; 
	$scope.onBarcode = function(event){
		$scope.poster.token = document.getElementById('token').value;
		$scope.poster.key = $scope.searchBarcode;
		$scope.poster.stype = 2;
		if(event.keyCode === 13){
			$http({
			  method: 'POST',
			  url: '/search',
			  data: $scope.poster
			}).then(function successCallback(response) {
					$scope.mainlist = eval(response.data);
					console.log(response);
					$scope.searchBarcode = '';
			  }, function errorCallback(response) {
			  		console.log(response);
			});
		}	
	};
	// $scope.paste = function (e) {
 //    console.log(e.originalEvent.clipboardData.getData('text/plain'));
	// };

	$scope.onAction = function(thisdata){
		$scope.data = thisdata;
		$scope.actionner = 'update';
		$('#data-id').val(thisdata.id);
		$('#dataModal').modal({ keyboard: false });
	};
	$scope.onDelete = function(id){
		$http({
		  method: 'POST',
		  url: '/deleterow',
		  data: {id:id}
		}).then(function successCallback(response) {
				$scope.getData();
				//console.log(response);
		  }, function errorCallback(response) {
		  		console.log(response);
		  });
	};
	
	// $scope.search = function(){
	// 	$http({
	// 	  method: 'GET',
	// 	  url: '/getdata'
	// 	}).then(function successCallback(response) {
	// 			//console.log(response.data.mainlist);
	// 			$scope.mainlist = eval(response.data.mainlist);
	// 			$scope.operators = eval(response.data.sample);
	// 	  }, function errorCallback(response) {
	// 	  		console.log(response);
	// 	  });
	// };

	$scope.getData = function(){
		$http({
		  method: 'GET',
		  url: '/getdata'
		}).then(function successCallback(response) {
				//console.log(response.data.mainlist);
				$scope.mainlist = eval(response.data.mainlist);
				$scope.samplesarr = eval(response.data.sample);
				$scope.insertionarr = eval(response.data.insertion);
				$scope.productGroupar = eval(response.data.productGroup);
				$scope.typeOfDeliveryar = eval(response.data.typeOfDelivery);
		  }, function errorCallback(response) {
		  		console.log(response);
		  });
	};

	$scope.getData();
});