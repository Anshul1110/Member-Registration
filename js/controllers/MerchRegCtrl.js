app.controller('MerchRegCtrl', function($scope, $state, $stateParams){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
});