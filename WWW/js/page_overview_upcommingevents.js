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

			this.Elements[i - 1].Element.style.top = (Overview.UpcommingEvents.ElementOffset * i -  Overview.UpcommingEvents.ElementOffset) + "px";

			this.Elements[i - 1].Element.style.zIndex = 1000 - i + 1;

			this.Elements[i].Element.style.top = (Overview.UpcommingEvents.ElementOffset * i) + "px";

			this.Elements[i].Element.style.zIndex = 1000 - i;
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

Overview.UpcommingEvents.FindElementIndex = function(id) {
	for(var i = 0; i < this.Elements.length; ++i) {
		if(this.Elements[i].Id === id) {
			return i;
		}
	}

	return null;
}

Overview.UpcommingEvents.AddTest = function(test) {
	var id = "Test-" + test.Id;

	var element = this.FindElement(id);

	if(test.IsDisabled) {
		this.RemoveTest(test);
	}
	else {
		if(element === null) {
			element = new Overview.UpcommingEvents.Test(id, test);

			element.Element.style.top = (Overview.UpcommingEvents.ElementOffset * this.Elements.length) + "px";

			element.Element.style.zIndex = 1000 - this.Elements.length;

			this.Elements.push(element);
		}

		element.UpdateData();

		if(element.Element.parentNode === null) {
			this.Element.appendChild(element.Element);
		}
	}

	this.Element.style.height = (Overview.UpcommingEvents.ElementOffset * this.Elements.length) + "px";
}

Overview.UpcommingEvents.RemoveTest = function(test) {
	var id = "Test-" + test.Id;

	var elementIndex = this.FindElementIndex(id);

	if(elementIndex !== null) {
		this.Element.removeChild(this.Elements[elementIndex].Element);

		this.Elements.splice(elementIndex, 1);
	}

	for(var i = elementIndex; i < this.Elements.length; ++i) {
		this.Elements[i].Element.style.top = (Overview.UpcommingEvents.ElementOffset * i) + "px";

		this.Elements[i].Element.style.zIndex = 1000 - i;
	}
}