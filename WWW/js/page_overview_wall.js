Overview.Wall = {};

Overview.Wall.Element = null;

Overview.Wall.ElementHeight = 50;

Overview.Wall.ElementSpacing = 8;

Overview.Wall.ElementOffset = Overview.Wall.ElementHeight + Overview.Wall.ElementSpacing;

Overview.Wall.Elements = [];

Overview.Wall.Load = function() {
	this.Element = document.getElementById("Wall");

	this.ScheduledTask.LoadHtml();

	OnScheduledTaskAdded.AddListener(
		this,
		function(invoker, data) {
			if(data.TimeUntilNextExecution < 0) {
				Overview.Wall.AddScheduledTask(data);
			}
		}
	);

	OnScheduledTaskUpdated.AddListener(
		this,
		function(invoker, data) {
			if(data.TimeUntilNextExecution < 0) {
				Overview.Wall.AddScheduledTask(data);
			}
			else {
				Overview.Wall.RemoveScheduledTask(data);
			}
		}
	);

	this.Test.LoadHtml();

	OnTestAdded.AddListener(
		this,
		function(invoker, data) {
			if(data.State === "FAILED") {
				Overview.Wall.AddTest(data);
			}
		}
	);

	OnTestUpdated.AddListener(
		this,
		function(invoker, data) {
			if(data.State === "FAILED") {
				Overview.Wall.AddTest(data);
			}
			else {
				Overview.Wall.RemoveTest(data);
			}
		}
	);

	this.UnhandledMessage.LoadHtml();

	OnUnhandledMessageAdded.AddListener(
		this,
		function(invoker, data) {
			if(data.TimeSolved === 0) {
				Overview.Wall.AddUnhandledMessage(data);
			}
		}
	);

	OnUnhandledMessageUpdated.AddListener(
		this,
		function(invoker, data) {
			if(data.TimeSolved === 0) {
				Overview.Wall.AddUnhandledMessage(data);
			}
			else {
				Overview.Wall.RemoveUnhandledMessage(data);
			}
		}
	);
}

Overview.Wall.Update = function(time) {
	for(var i = 0; i < this.Elements.length; ++i) {
		this.Elements[i].Update(time);
	}

	this.Update_Sort();
}

Overview.Wall.Update_Sort = function() {
	for(var i = 1; i < this.Elements.length; ++i) {
		if(this.Elements[i].Order < this.Elements[i - 1].Order) {
			var element = this.Elements[i];

			this.Elements[i] = this.Elements[i - 1];

			this.Elements[i - 1] = element;

			this.Elements[i - 1].Element.style.top = (Overview.Wall.ElementOffset * i - Overview.Wall.ElementOffset) + "px";

			this.Elements[i - 1].Element.style.zIndex = 1000 - i + 1;

			this.Elements[i].Element.style.top = (Overview.Wall.ElementOffset * i) + "px";

			this.Elements[i].Element.style.zIndex = 1000 - i;
		}
	}
}

Overview.Wall.FindElement = function(id) {
	for(var i = 0; i < this.Elements.length; ++i) {
		if(this.Elements[i].Id === id) {
			return this.Elements[i];
		}
	}

	return null;
}

Overview.Wall.FindElementIndex = function(id) {
	for(var i = 0; i < this.Elements.length; ++i) {
		if(this.Elements[i].Id === id) {
			return i;
		}
	}

	return null;
}

Overview.Wall.AddScheduledTask = function(scheduledTask) {
	var id = "ScheduledTask-" + scheduledTask.Id;

	var element = this.FindElement(id);

	if(scheduledTask.IsDisabled) {
		this.RemoveScheduledTask(scheduledTask);
	}
	else {
		if(element === null) {
			element = new Overview.Wall.ScheduledTask(id, scheduledTask);

			element.Element.style.top = (Overview.Wall.ElementOffset * this.Elements.length) + "px";

			element.Element.style.zIndex = 1000 - this.Elements.length;

			this.Elements.push(element);
		}

		element.UpdateData();

		if(element.Element.parentNode === null) {
			this.Element.appendChild(element.Element);
		}
	}

	this.Element.style.height = (Overview.Wall.ElementOffset * this.Elements.length) + "px";
}

Overview.Wall.RemoveScheduledTask = function(scheduledTask) {
	var id = "ScheduledTask-" + scheduledTask.Id;

	var elementIndex = this.FindElementIndex(id);

	if(elementIndex !== null) {
		this.Element.removeChild(this.Elements[elementIndex].Element);

		this.Elements.splice(elementIndex, 1);

		for(var i = elementIndex; i < this.Elements.length; ++i) {
			this.Elements[i].Element.style.top = (Overview.Wall.ElementOffset * i) + "px";

			this.Elements[i].Element.style.zIndex = 1000 - i;
		}
	}
}

Overview.Wall.AddTest = function(test) {
	var id = "Test-" + test.Id;

	var element = this.FindElement(id);

	if(test.IsDisabled) {
		this.RemoveTest(test);
	}
	else {
		if(element === null) {
			element = new Overview.Wall.Test(id, test);

			element.Element.style.top = (Overview.Wall.ElementOffset * this.Elements.length) + "px";

			element.Element.style.zIndex = 1000 - this.Elements.length;

			this.Elements.push(element);
		}

		element.UpdateData();

		if(element.Element.parentNode === null) {
			this.Element.appendChild(element.Element);
		}
	}

	this.Element.style.height = (Overview.Wall.ElementOffset * this.Elements.length) + "px";
}

Overview.Wall.RemoveTest = function(test) {
	var id = "Test-" + test.Id;

	var elementIndex = this.FindElementIndex(id);

	if(elementIndex !== null) {
		this.Element.removeChild(this.Elements[elementIndex].Element);

		this.Elements.splice(elementIndex, 1);

		for(var i = elementIndex; i < this.Elements.length; ++i) {
			this.Elements[i].Element.style.top = (Overview.Wall.ElementOffset * i) + "px";

			this.Elements[i].Element.style.zIndex = 1000 - i;
		}
	}
}

Overview.Wall.AddUnhandledMessage = function(unhandledMessage) {
	var id = "UnhandledMessage-" + unhandledMessage.Id;

	var element = this.FindElement(id);

	if(unhandledMessage.IsDisabled) {
		this.RemoveUnhandledMessage(unhandledMessage);
	}
	else {
		if(element === null) {
			element = new Overview.Wall.UnhandledMessage(id, unhandledMessage);

			element.Element.style.top = (Overview.Wall.ElementOffset * this.Elements.length) + "px";

			element.Element.style.zIndex = 1000 - this.Elements.length;

			this.Elements.push(element);
		}

		element.UpdateData();

		if(element.Element.parentNode === null) {
			this.Element.appendChild(element.Element);
		}
	}

	this.Element.style.height = (Overview.Wall.ElementOffset * this.Elements.length) + "px";
}

Overview.Wall.RemoveUnhandledMessage = function(unhandledMessage) {
	var id = "UnhandledMessage-" + unhandledMessage.Id;

	var elementIndex = this.FindElementIndex(id);

	if(elementIndex !== null) {
		this.Element.removeChild(this.Elements[elementIndex].Element);

		this.Elements.splice(elementIndex, 1);

		for(var i = elementIndex; i < this.Elements.length; ++i) {
			this.Elements[i].Element.style.top = (Overview.Wall.ElementOffset * i) + "px";

			this.Elements[i].Element.style.zIndex = 1000 - i;
		}
	}
}