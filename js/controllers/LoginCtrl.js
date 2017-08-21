app.controller('LoginCtrl', function($scope, $state, $stateParams){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		u: '',
		p: '',
		r: $stateParams.role
	}
	$scope.login = function(){
		console.log($scope.user)
	}
	$scope.register = function(){
		switch($scope.user.r){
			case 'Agent' : $state.go('agentRegister'); break;
			case 'Customer' : $state.go('customerRegister'); break;
			case 'Merchant' : $state.go('merchantRegister'); break;	
		}	
	}
});