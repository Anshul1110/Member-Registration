function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}  

Date.prototype.toMysqlFormat = function(h, m, s) {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(this.getUTCDate()) + " " + twoDigits((h || h===0)?h:this.getUTCHours()) + ":" + twoDigits((m || m===0)?m:this.getUTCMinutes()) + ":" + twoDigits((s || s===0)?s:this.getUTCSeconds());
};

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
			case 'Online Entrepreneur' : $state.go('agentLogin'); break;
			case 'Admin' : $state.go('adminLogin'); break;
			case 'Customer' : $state.go('customerLogin'); break;
			case 'Merchant' : $state.go('merchantLogin'); break;	
		}	
	}
	$scope.register = function(){
		switch($scope.user.r){
			case 'Online Entrepreneur' : $state.go('agentRegister'); break;
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
		credits:10,
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
	$scope.product = {
		pcode:'Test',
		pname:'Test',
		pdesc:'Test',
		pcat:'Test',
		pbrand:'Test',
		psize:'Test',
		pprice:'Test'
	}
	$scope.register = function(){
		console.log($scope.product)
	}
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
		nic:'102933843348',
		dob: new Date(),
		numb:'Test',
		r: $stateParams.role
	}
	$scope.register = function(){
		$scope.user.sqlDob = $scope.user.dob.toMysqlFormat(0,0,0);
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
