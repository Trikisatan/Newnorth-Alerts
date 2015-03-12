Overview.UpcommingEvents = {};

Overview.UpcommingEvents.Element = null;

Overview.UpcommingEvents.ElementHeight = 50;

Overview.UpcommingEvents.ElementSpacing = 8;

Overview.UpcommingEvents.ElementOffset = Overview.UpcommingEvents.ElementHeight + Overview.UpcommingEvents.ElementSpacing;

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

	this.Update_Sort();
}

Overview.UpcommingEvents.Update_Sort = function() {
	for(var i = 1; i < this.Elements.length; ++i) {
		if(this.Elements[i].Order < this.Elements[i - 1].Order) {
			var element = this.Elements[i];

			this.Elements[i] = this.Elements[i - 1];

			this.Elements[i - 1] = element;

			this.Elements[i - 1].Element.style.top = (28 + Overview.UpcommingEvents.ElementOffset * i -  Overview.UpcommingEvents.ElementOffset) + "px";

			this.Elements[i].Element.style.top = (28 + Overview.UpcommingEvents.ElementOffset * i) + "px";
		}
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

		element.Element.style.top = (28 + Overview.UpcommingEvents.ElementOffset * this.Elements.length) + "px";

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