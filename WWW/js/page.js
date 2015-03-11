Tests = [];

OnTestAdded = new Newnorth.Event();

OnTestStateChangedToOK = new Newnorth.Event();

OnTestStateChangedToFAILED = new Newnorth.Event();

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

	setTimeout(Update, 10);
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

	request.onreadystatechange = function() {
		if(this.readyState === 4) {
			var response;

			try {
				response = JSON.parse(request.responseText);
			}
			catch(exception) {
				response = false;
			}

			if(response !== false) {
				var state = this.Test.State;

				this.Test.State = response.State;

				this.Test.StatePriorityLevel = response.StatePriorityLevel;

				this.Test.StateDescription = response.StateDescription;

				this.Test.TimeLastFailed = response.TimeLastFailed;

				this.Test.IsExecuting = response.IsExecuting;

				this.Test.TimeLastExecuted = response.TimeLastExecuted;

				if(this.Test.State !== state) {
					if(this.Test.State === "OK") {
						OnTestStateChangedToOK.Invoke(null, {From: state, Test: this.Test});
					}
					else if(this.Test.State === "FAILED") {
						OnTestStateChangedToFAILED.Invoke(null, {From: state, Test: this.Test});
					}
				}
			}
		}
	};

	request.open("GET", "/execute-test/" + test.Id + "/", false);

	request.send(null);
}

window.addEventListener(
	"load",
	function() {
		Overview.Load();

		LoadTests();

		Update();
	}
);