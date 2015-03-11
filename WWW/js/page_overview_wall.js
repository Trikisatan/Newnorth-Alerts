Overview.Wall = {};

Overview.Wall.Element = null;

Overview.Wall.Elements = [];

Overview.Wall.Load = function() {
	this.Element = document.getElementById("Wall");

	this.Test.LoadHtml();

	OnTestAdded.AddListener(
		this,
		function(invoker, data) {
			if(data.State === "Failed") {
				this.AddTest(data);
			}
		}
	);
}

Overview.Wall.Update = function(time) {
	for(var i = 0; i < this.Elements.length; ++i) {
		this.Elements[i].Update(time);
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

		this.Elements.push(element);
	}

	if(element.Element.parentNode === null) {
		this.Element.appendChild(element.Element);
	}
}