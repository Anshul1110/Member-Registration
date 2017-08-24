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
		u: 'Test',
		p: 'Test',
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
                console.log(data)                    
            	switch($scope.user.r){
					case 'Online Entrepreneur' : $state.go('agentHome', {user:data.user}); break;
					case 'Customer' : $state.go('customerHome', {user:data.user}); break;
					case 'Merchant' : $state.go('merchantHome', {user:data.user}); break;	
					case 'Admin' : var tuser = {fname:"Admin", role:"Admin"}; $state.go('adminHome', {user:tuser}); break;	
				}
            }
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
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
	console.log($stateParams);
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
		r: $stateParams.role,
		c: $stateParams.refCode
	}
	$scope.register = function(){
		$scope.showLoader = true;
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
			$scope.showLoader = false;
            console.log(data)                    
            alert(data.message);
            if(data.result){
                $state.go('login', {role:$scope.user.r});
            }
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
	}
})
.controller('AgentHomeCtrl', function($scope, $state, $stateParams, $http, $location){
	if($stateParams.user==null){
		$state.go('home');
	}else{
		$scope.user = $stateParams.user;
		$scope.user.r = 'Online Entrepreneur';
	}

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
		$scope.showLoader = false;
		$scope.getProducts();
		$scope.getReferrals();
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
            $scope.user.products = data.products;
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
    }

    $scope.getReferrals = function(){
    	$http({
            url: "backend/getReferrals.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)
            $scope.user.referrals = data.referrals;
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
    }

    $scope.shareLink = function(type){
    	console.log(type);
    	var link = $location.$$absUrl;
    	link = link.split("/");
    	delete link[link.length - 1];
    	link = link.join("/") + "register/" + $scope.user['a_ref'];
    	switch(type){
    		case 'url': 
    			prompt("Here is the link you can share:", link);
    			break;
    		case 'fb':
    			window.open('https://www.facebook.com/sharer/sharer.php?u=' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
    			break;
    		case 'wa':
    			window.open('https://web.whatsapp.com/send?text=Check out this link: ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
    			break;
    	}
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
		r: $stateParams.role,
		c: $stateParams.refCode
	}
	$scope.register = function(){
		$scope.showLoader = true;
		$scope.user.sqlDob = $scope.user.dob.toMysqlFormat(0,0,0);
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
			$scope.showLoader = false;
            console.log(data)                    
            alert(data.message);
            if(data.result){
                $state.go('login', {role:$scope.user.r});
            }
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
	}
})
.controller('CustHomeCtrl', function($scope, $state, $stateParams, $location, $http){
	if($stateParams.user==null){
		$state.go('home');
	}else{
		$scope.user = $stateParams.user;
		$scope.user.r = 'Customer';		
	}
	$scope.getReferrals = function(){
    	$http({
            url: "backend/getReferrals.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)
            $scope.user.referrals = data.referrals;
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
    }

    $scope.shareLink = function(type){
    	console.log(type);
    	var link = $location.$$absUrl;
    	link = link.split("/");
    	delete link[link.length - 1];
    	link = link.join("/") + "register/" + $scope.user['c_ref'];
    	switch(type){
    		case 'url': 
    			prompt("Here is the link you can share:", link);
    			break;
    		case 'fb':
    			window.open('https://www.facebook.com/sharer/sharer.php?u=' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
    			break;
    		case 'wa':
    			window.open('https://web.whatsapp.com/send?text=Check out this link: ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
    			break;
    	}
    }

    $scope.getReferrals();
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
		r: $stateParams.role,
		c: $stateParams.refCode
	}

	$scope.register = function(){
		$scope.showLoader = true;
		$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
			$scope.showLoader = false;
            console.log(data)                    
            alert(data.message);
            if(data.result){
                $state.go('login', {role:$scope.user.r});
            }
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
	}
})
.controller('MerchHomeCtrl', function($scope, $state, $stateParams, $location, $http){
	if($stateParams.user==null){
		$state.go('home');
	}else{
		$scope.user = $stateParams.user;
		$scope.user.r = 'Merchant';
	}
	$scope.getReferrals = function(){
    	$http({
            url: "backend/getReferrals.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            console.log(data)
            $scope.user.referrals = data.referrals;
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
    }

    $scope.shareLink = function(type){
    	console.log(type);
    	var link = $location.$$absUrl;
    	link = link.split("/");
    	delete link[link.length - 1];
    	link = link.join("/") + "register/" + $scope.user['m_ref'];
    	switch(type){
    		case 'url': 
    			prompt("Here is the link you can share:", link);
    			break;
    		case 'fb':
    			window.open('https://www.facebook.com/sharer/sharer.php?u=' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
    			break;
    		case 'wa':
    			window.open('https://web.whatsapp.com/send?text=Check out this link: ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
    			break;
    	}
    }

    $scope.getReferrals();
})
.controller('AdminHomeCtrl', function($scope, $state, $stateParams, $http){
	$scope.getData = function(){
		$http({
	        url: "backend/admindata.php",
	        method: "POST",
	        data: $scope.user,
	        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).success(function(data, status, headers, config) {
	        console.log(data)
	        $scope.user.agents = data.agents;
	        $scope.user.products = data.products;
	    }).error(function(data, status, headers, config) {
	        $state.go('home');
	    });
	}

	$scope.updateCredits = function(action, agent){
		console.log(action, agent);
		var obj = {
			action: action,
			agent: agent
		}
		$http({
	        url: "backend/updateCredit.php",
	        method: "POST",
	        data: obj,
	        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).success(function(data, status, headers, config) {
	        console.log(data)
	        alert(data.message);
	        $scope.getData();
	    }).error(function(data, status, headers, config) {
	        $state.go('home');
	    });
	}

	$scope.updateProduct = function(product){
		console.log(product);
		$http({
	        url: "backend/approveProduct.php",
	        method: "POST",
	        data: product,
	        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).success(function(data, status, headers, config) {
	        console.log(data)
	        alert(data.message)
	        if(data.result){
	        	$scope.getData();
	    	}
	    }).error(function(data, status, headers, config) {
	        $state.go('home');
	    });
	}

	if($stateParams.user==null){
		$state.go('home');
	}else{
		$scope.user = $stateParams.user;
		$scope.user.r = 'Admin';
		$scope.getData();
	}
});
