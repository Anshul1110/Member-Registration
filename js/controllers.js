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
.controller('LoginCtrl', function($scope, $state, $stateParams, $http){
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
		$http({
            url: "backend/login.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            alert(data.message);                
            if(data.result){
                console.log(data.user)                    
            }
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
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
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)                    
            if(data.result){
            	alert("Thank you for registering, a verification E-Mail has been sent to your E-Mail ID. You can login after verifying successfully.");
                $state.go('login', {role:$scope.user.r});
            }else{
                alert("There was an error in registering. Please try again!");
            }
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
	}
})
.controller('AgentHomeCtrl', function($scope, $state, $stateParams, $http){
	$scope.reset = function(){
		$scope.product = {
			pcode:'Test',
			pname:'Test',
			pdesc:'Test',
			pdet:'',
			pcat:'Test',
			pbrand:'Test',
			psize:'Test',
			pprice:'Test'
		}
		$scope.user = {
			a_id:"C",
			a_fname:"Anurag",
			a_credits:8
		}
		$scope.showLoader = false;
		$scope.getProducts();
	}

	$scope.addProduct = function() {     
		console.log($scope.product)
        $scope.showLoader = true; 
        var fd = new FormData();
        for (var i in $scope.product.imgFile) {
            fd.append("uploadedFile", $scope.product.imgFile[i]);
        }
        fd.append("user", angular.toJson($scope.user));
        fd.append("product", angular.toJson($scope.product));

        var xhr = new XMLHttpRequest()
        xhr.upload.addEventListener("progress", uploadProgress, false)
        xhr.addEventListener("load", uploadComplete, false)
        xhr.addEventListener("error", uploadFailed, false)
        xhr.addEventListener("abort", uploadCanceled, false)
        xhr.open("POST", "backend/addproduct.php")
        xhr.send(fd);
    }

    $scope.getProducts = function(){
    	$http({
            url: "backend/getProducts.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)
            $scope.products = data.products;
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
    }

    $scope.getReferrals = function(){

    }

    $scope.reset();

    function uploadProgress(evt) {
        $scope.$apply(function(){
            if (evt.lengthComputable) {
                $scope.progress = Math.round(evt.loaded * 100 / evt.total)
    			console.log($scope.progress)
            } else {
                $scope.progress = 0;
            }
        })
    }

    function uploadComplete(evt){
    	console.log(evt.target.response)
        $scope.$apply(function(){
            $scope.showLoader = false; 
            var data = JSON.parse(evt.target.response);
            console.log(data);
            alert(data.message);
            if(data.inserted){
				$scope.getProducts();
            }
        });
    }

    function uploadFailed(evt) {
        console.log("There was an error attempting to upload the file.")
    }

    function uploadCanceled(evt) {
        console.log("The upload has been canceled by the user or the browser dropped the connection.")
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
		email:'anurag.131092@gmail.com',
		nic:'102933843348',
		dob: new Date(),
		numb:'Test',
		r: $stateParams.role
	}
	$scope.register = function(){
		$scope.user.sqlDob = $scope.user.dob.toMysqlFormat(0,0,0);
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)                    
            if(data.result){
            	alert("Thank you for registering, a verification E-Mail has been sent to your E-Mail ID. You can login after registering successfully.");
                $state.go('login', {role:$scope.user.r});
            }else{
                alert("There was an error in registering. Please try again!");
            }
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
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)                    
            if(data.result){
            	alert("Thank you for registering, a verification E-Mail has been sent to your E-Mail ID. You can login after registering successfully.");
                $state.go('login', {role:$scope.user.r});
            }else{
                alert("There was an error in registering. Please try again!");
            }
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
