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
		switch($scope.user.r){
			case 'Agent' : $state.go('agentLogin'); break;
			case 'Admin' : $state.go('adminLogin'); break;
			case 'Customer' : $state.go('customerLogin'); break;
			case 'Merchant' : $state.go('merchantLogin'); break;	
		}	
	}
	$scope.register = function(){
		switch($scope.user.r){
			case 'Agent' : $state.go('agentRegister'); break;
			case 'Customer' : $state.go('customerRegister'); break;
			case 'Merchant' : $state.go('merchantRegister'); break;	
		}	
	}
})
.controller('AgentRegCtrl', function($scope, $state, $stateParams, $http){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		fname:'Test',
		lname:'test',
		uname:'test',
		pass:'test',
		cpass:'test',
		email:'test',
		numb:'test',
		r: $stateParams.role
	}
	$scope.register = function(){
		console.log($scope.user)
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)                    
            /*if(data.result){
                console.log(data.user)                    
                $cookies.put('user', JSON.stringify(data.user));
                //Create Cookies here
                switch(parseInt(data.user.r)){
                    case 0 : $state.go('client', {user:data.user}); break;
                    case 1 : $state.go('helpDesk', {user:data.user}); break;
                    case 2 : $state.go('admin', {user:data.user}); break;
                }
            }else{
                alert("Wrong ID/Password!");
                $scope.user = {u:"", p:"",r:"0",ua:navigator.userAgent};
            }*/
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
	}
})
.controller('AgentHomeCtrl', function($scope, $state, $stateParams){
	/*$scope.product = {
		pcode:'',
		pname:'',
		pdesc:'',
		psize:'',
		pprice:'',
		r: $stateParams.role
	}
	$scope.register = function(){
		console.log($scope.user)
	}*/
})
.controller('CustRegCtrl', function($scope, $state, $stateParams, $http){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		fname:'Test',
		lname: 'Test',
		uname:'Test',
		pass:'Test',
		cpass:'Test',
		email:'Test',
		dep:'Test',
		numb:'Test',
		r: $stateParams.role
	}
	$scope.register = function(){
		console.log($scope.user)
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)                    
            /*if(data.result){
                console.log(data.user)                    
                $cookies.put('user', JSON.stringify(data.user));
                //Create Cookies here
                switch(parseInt(data.user.r)){
                    case 0 : $state.go('client', {user:data.user}); break;
                    case 1 : $state.go('helpDesk', {user:data.user}); break;
                    case 2 : $state.go('admin', {user:data.user}); break;
                }
            }else{
                alert("Wrong ID/Password!");
                $scope.user = {u:"", p:"",r:"0",ua:navigator.userAgent};
            }*/
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });

	}
})
.controller('CustHomeCtrl', function($scope, $state, $stateParams){
	/*$scope.user = {
		fields:''

	}
	$scope.register = function(){
		console.log($scope.user)
	}*/
})
.controller('MerchRegCtrl', function($scope, $state, $stateParams, $http){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		fname:'Test',
		lname: 'Test',
		uname:'Test',
		pass:'Test',
		cpass:'Test',
		add: 'Test',
		city: 'Test',
		state: 'Test',
		zip: 'Test',
		comp: 'Test',
		numb: 'Test',
		email: 'Test',
		url: 'Test',
		r: $stateParams.role
	}
	/*and ye sab fields html form me bhi map karne hai 
	hum log name ki ng-model le rahe h na?
	ng-model use karke yaad hai na?...sabse pehle din padhaya th ..?login wala dekh le html..theek h boss m krta hu.. .*/
	$scope.register = function(){
		console.log($scope.user)
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)                    
            /*if(data.result){
                console.log(data.user)                    
                $cookies.put('user', JSON.stringify(data.user));
                //Create Cookies here
                switch(parseInt(data.user.r)){
                    case 0 : $state.go('client', {user:data.user}); break;
                    case 1 : $state.go('helpDesk', {user:data.user}); break;
                    case 2 : $state.go('admin', {user:data.user}); break;
                }
            }else{
                alert("Wrong ID/Password!");
                $scope.user = {u:"", p:"",r:"0",ua:navigator.userAgent};
            }*/
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
	}
})
.controller('MerchHomeCtrl', function($scope, $state, $stateParams){
	/*$scope.user = {
		field1:'',
		
	}
	$scope.register = function(){
		console.log($scope.user)
	}*/
})
.controller('AdminHomeCtrl', function($scope, $state, $stateParams){
	$scope.user = {
		field1:''
	}
	$scope.register = function(){
		console.log($scope.user)
	}
});
