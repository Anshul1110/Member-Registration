app.controller('HomeCtrl', function($scope, $state){
	$scope.doLogin = function(role){
		$state.go('login', {role: role})
	}
})
.controller('LoginCtrl', function($scope, $state, $stateParams){
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
})
.controller('AgentRegCtrl', function($scope, $state, $stateParams){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
})
.controller('AgentHomeCtrl', function($scope, $state, $stateParams){
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
})
.controller('CustRegCtrl', function($scope, $state, $stateParams){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
})
.controller('CustHomeCtrl', function($scope, $state, $stateParams){
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
})
.controller('MerchRegCtrl', function($scope, $state, $stateParams){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
})
.controller('MerchHomeCtrl', function($scope, $state, $stateParams){
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
})
.controller('AdminHomeCtrl', function($scope, $state, $stateParams){
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
});