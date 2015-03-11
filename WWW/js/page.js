Tests = [];

OnTestAdded = new Newnorth.Event();

LoadTests = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/data/tests/", false);

	request.send(null);

	var tests = JSON.parse(request.responseText);

	for(var i = 0; i < tests.length; ++i) {
		AddTest(tests[i]);
	}
}

Update = function() {
	var time = Date.now() / 1000;

	Update_ExecuteTests(time);

	Overview.Update(time);

	setTimeout(Update, 100);
}

Update_ExecuteTests = function(time) {
	for(var i = 0; i < Tests.length; ++i) {
		var test = Tests[i];

		if(!test.IsExecuting && test.TimeLastExecuted + test.ExecutionInterval < time) {
			ExecuteTest(Tests[i]);
		}
	}
}

AddTest = function(test) {
	Tests.push(test);

	OnTestAdded.Invoke(null, test);
}

ExecuteTest = function(test) {
	test.IsExecuting = true;

	var request = new XMLHttpRequest();

	request.Test = test;

	request.open("GET", "/execute-test/" + test.Id + "/", false);

	request.send(null);
console.log(request.responseText);
	//var response = JSON.parse(request.responseText);
}

window.addEventListener(
	"load",
	function() {
		Overview.Load();

		LoadTests();

		Update();
	}
);