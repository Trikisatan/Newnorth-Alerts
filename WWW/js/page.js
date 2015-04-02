ScheduledTasks = [];

Tests = [];

UnhandledMessages = [];

OnScheduledTaskAdded = new Newnorth.Event();

OnScheduledTaskUpdated = new Newnorth.Event();

OnTestAdded = new Newnorth.Event();

OnTestUpdated = new Newnorth.Event();

OnTestStateChangedToOK = new Newnorth.Event();

OnTestStateChangedToFAILED = new Newnorth.Event();

OnUnhandledMessageAdded = new Newnorth.Event();

OnUnhandledMessageUpdated = new Newnorth.Event();

Load = function() {
	Overview.Load();
}

Start = function() {
	UpdateScheduledTasks();

	UpdateTests();

	UpdateUnhandledMessages();

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

		if(!test.IsExecuting && test.TimeLastExecuted + test.ExecutionInterval <= time) {
			ExecuteTest(Tests[i], false);
		}
		else if(test.IsExecuting && test.TimeLastExecuted + test.ExecutionTimeout <= time) {
			ExecuteTest(Tests[i], true);
		}
	}
}

UpdateScheduledTasks = function() {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function() {
		if(this.readyState === 4) {
			try {
				var response = JSON.parse(request.responseText);

				for(var i = 0; i < response.length; ++i) {
					var test = FindScheduledTask(response[i].Id);

					if(test === null) {
						AddScheduledTask(response[i]);
					}
					else {
						UpdateScheduledTask(test, response[i], false);
					}
				}
			}
			catch(exception) {

			}

			setTimeout(UpdateScheduledTasks, 1000);
		}
	};

	request.open("GET", "/data/scheduled-tasks/", true);

	request.send(null);
}

FindScheduledTask = function(id) {
	for(var i = 0; i < ScheduledTasks.length; ++i) {
		if(ScheduledTasks[i].Id === id) {
			return ScheduledTasks[i];
		}
	}

	return null;
}

AddScheduledTask = function(scheduledTask) {
	ScheduledTasks.push(scheduledTask);

	OnScheduledTaskAdded.Invoke(null, scheduledTask);
}

UpdateScheduledTask = function(scheduledTask, data, isPreUpdated) {
	var state = scheduledTask.State;

	var isUpdated = isPreUpdated;

	for(var key in data) {
		if(scheduledTask[key] !== data[key]) {
			scheduledTask[key] = data[key];

			isUpdated = true;
		}
	}

	if(isUpdated) {
		OnScheduledTaskUpdated.Invoke(null, scheduledTask);
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
	var state = test.State;

	var isUpdated = isPreUpdated;

	for(var key in data) {
		if(test[key] !== data[key]) {
			test[key] = data[key];

			isUpdated = true;
		}
	}

	if(isUpdated) {
		OnTestUpdated.Invoke(null, test);
	}

	if(test.State !== state) {
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

	test.TimeLastExecuted = Math.floor(Date.now() / 1000);

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

UpdateUnhandledMessages = function() {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function() {
		if(this.readyState === 4) {
			try {
				var response = JSON.parse(request.responseText);

				for(var i = 0; i < response.length; ++i) {
					var test = FindUnhandledMessage(response[i].Id);

					if(test === null) {
						AddUnhandledMessage(response[i]);
					}
					else {
						UpdateUnhandledMessage(test, response[i], false);
					}
				}
			}
			catch(exception) {

			}

			setTimeout(UpdateUnhandledMessages, 1000);
		}
	};

	request.open("GET", "/data/unhandled-messages/", true);

	request.send(null);
}

FindUnhandledMessage = function(id) {
	for(var i = 0; i < UnhandledMessages.length; ++i) {
		if(UnhandledMessages[i].Id === id) {
			return UnhandledMessages[i];
		}
	}

	return null;
}

AddUnhandledMessage = function(unhandledMessage) {
	UnhandledMessages.push(unhandledMessage);

	OnUnhandledMessageAdded.Invoke(null, unhandledMessage);
}

UpdateUnhandledMessage = function(unhandledMessage, data, isPreUpdated) {
	var state = unhandledMessage.State;

	var isUpdated = isPreUpdated;

	for(var key in data) {
		if(unhandledMessage[key] !== data[key]) {
			unhandledMessage[key] = data[key];

			isUpdated = true;
		}
	}

	if(isUpdated) {
		OnUnhandledMessageUpdated.Invoke(null, unhandledMessage);
	}
}

window.addEventListener(
	"load",
	function() {
		Load();

		Start();
	}
);