Tests = [];

OnTestAdded = new Newnorth.Event();

OnTestUpdated = new Newnorth.Event();

OnTestStateChangedToOK = new Newnorth.Event();

OnTestStateChangedToFAILED = new Newnorth.Event();

Load = function() {
	Overview.Load();
}

Start = function() {
	UpdateTests();

	Update();
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
			ExecuteTest(Tests[i], false);
		}
	}
}

UpdateTests = function() {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function() {
		if(this.readyState === 4) {
			try {
				var response = JSON.parse(request.responseText);

				for(var i = 0; i < response.length; ++i) {
					var test = FindTest(response[i].Id);

					if(test === null) {
						AddTest(response[i]);
					}
					else {
						UpdateTest(test, response[i], false);
					}
				}
			}
			catch(exception) {

			}

			setTimeout(UpdateTests, 1000);
		}
	};

	request.open("GET", "/data/tests/", true);

	request.send(null);
}

FindTest = function(id) {
	for(var i = 0; i < Tests.length; ++i) {
		if(Tests[i].Id === id) {
			return Tests[i];
		}
	}

	return null;
}

AddTest = function(test) {
	Tests.push(test);

	OnTestAdded.Invoke(null, test);
}

UpdateTest = function(test, data, isPreUpdated) {
	var isUpdated = isPreUpdated;

	var isStateUpdated = false;

	if(test.State !== data.State) {
		test.State = data.State;

		isUpdated = true;

		isStateUpdated = true;
	}

	if(test.StatePriorityLevel !== data.StatePriorityLevel) {
		test.StatePriorityLevel = data.StatePriorityLevel;

		isUpdated = true;
	}

	if(test.StateDescription !== data.StateDescription) {
		test.StateDescription = data.StateDescription;

		isUpdated = true;
	}

	if(test.TimeLastFailed !== data.TimeLastFailed) {
		test.TimeLastFailed = data.TimeLastFailed;

		isUpdated = true;
	}

	if(test.IsExecuting !== data.IsExecuting) {
		test.IsExecuting = data.IsExecuting;

		isUpdated = true;
	}

	if(test.TimeLastExecuted !== data.TimeLastExecuted) {
		test.TimeLastExecuted = data.TimeLastExecuted;

		isUpdated = true;
	}

	if(isUpdated) {
		OnTestUpdated.Invoke(null, test);
	}

	if(isStateUpdated) {
		if(test.State === "OK") {
			OnTestStateChangedToOK.Invoke(null, {From: state, Test: test});
		}
		else if(test.State === "FAILED") {
			OnTestStateChangedToFAILED.Invoke(null, {From: state, Test: test});
		}
	}
}

ExecuteTest = function(test, force) {
	test.IsExecuting = true;

	var request = new XMLHttpRequest();

	request.Test = test;

	request.onreadystatechange = function() {
		if(this.readyState === 4) {
			try {
				var response = JSON.parse(request.responseText);

				if(response !== false) {
					UpdateTest(this.Test, response, false);
				}
			}
			catch(exception) {

			}
		}
	};

	request.open("GET", "/execute-test/" + test.Id + "/" + (force ? "?force" : ""), true);

	request.send(null);
}

window.addEventListener(
	"load",
	function() {
		Load();

		Start();
	}
);