app.controller('HomeCtrl', function($scope, $state){
	$scope.doLogin = function(role){
		$state.go('login', {role: role})
	}
});