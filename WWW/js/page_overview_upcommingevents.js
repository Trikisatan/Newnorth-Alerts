Overview.UpcommingEvents = {};

Overview.UpcommingEvents.Element = null;

Overview.UpcommingEvents.Elements = [];

Overview.UpcommingEvents.Load = function() {
	this.Element = document.getElementById("UpcommingEvents");

	this.Test.LoadHtml();

	OnTestAdded.AddListener(
		this,
		function(invoker, data) {
			Overview.UpcommingEvents.AddTest(data);
		}
	);

	OnTestUpdated.AddListener(
		this,
		function(invoker, data) {
			Overview.UpcommingEvents.AddTest(data);
		}
	);
}

Overview.UpcommingEvents.Update = function(time) {
	for(var i = 0; i < this.Elements.length; ++i) {
		this.Elements[i].Update(time);
	}
}

Overview.UpcommingEvents.FindElement = function(id) {
	for(var i = 0; i < this.Elements.length; ++i) {
		if(this.Elements[i].Id === id) {
			return this.Elements[i];
		}
	}

	return null;
}

Overview.UpcommingEvents.AddTest = function(test) {
	var id = "Test-" + test.Id;

	var element = this.FindElement(id);

	if(element === null) {
		element = new Overview.UpcommingEvents.Test(id, test);

		this.Elements.push(element);
	}

	element.UpdateData();

	if(element.Element.parentNode === null) {
		this.Element.appendChild(element.Element);
	}
}

Overview.UpcommingEvents.RemoveTest = function(test) {
	var id = "Test-" + test.Id;

	var element = this.FindElement(id);

	if(element !== null) {
		this.Element.removeChild(element.Element);
	}
}