Overview.Wall = {};

Overview.Wall.Element = null;

Overview.Wall.ElementHeight = 50;

Overview.Wall.ElementSpacing = 8;

Overview.Wall.ElementOffset = Overview.Wall.ElementHeight + Overview.Wall.ElementSpacing;

Overview.Wall.Elements = [];

Overview.Wall.Load = function() {
	this.Element = document.getElementById("Wall");

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
		}
	);

	OnTestStateChangedToOK.AddListener(
		this,
		function(invoker, data) {
			Overview.Wall.RemoveTest(data.Test);
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

			this.Elements[i - 1].Element.style.top = (28 + Overview.Wall.ElementOffset * i -  Overview.Wall.ElementOffset) + "px";

			this.Elements[i].Element.style.top = (28 + Overview.Wall.ElementOffset * i) + "px";
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

Overview.Wall.AddTest = function(test) {
	var id = "Test-" + test.Id;

	var element = this.FindElement(id);

	if(element === null) {
		element = new Overview.Wall.Test(id, test);

		element.Element.style.top = (28 + Overview.Wall.ElementOffset * this.Elements.length) + "px";

		this.Elements.push(element);
	}

	element.UpdateData();

	if(element.Element.parentNode === null) {
		this.Element.appendChild(element.Element);
	}
}

Overview.Wall.RemoveTest = function(test) {
	var id = "Test-" + test.Id;

	var element = this.FindElement(id);

	if(element !== null) {
		this.Element.removeChild(element.Element);
	}
}