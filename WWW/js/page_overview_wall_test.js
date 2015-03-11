Overview.Wall.Test = function(id, test) {
	this.Id = id;

	this.Test = test;

	this.Element = Overview.Wall.Test.Html.cloneNode(true);

	this.PriorityLevelElement = this.Element.childNodes[0];
	this.PriorityLevelElement.className = this.Test.StatePriorityLevel + "Priority";

	this.TitleElement = this.Element.childNodes[1].childNodes[0];
	this.TitleElement.innerHTML = this.Test.Title;

	this.DescriptionElement = this.Element.childNodes[1].childNodes[1];
	this.DescriptionElement.innerHTML = this.Test.StateDescription;

	this.TimeElapsedElement = this.Element.childNodes[2].childNodes[1];
}

Overview.Wall.Test.LoadHtml = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/js/page_overview_wall_test.xml", false);

	request.send(null);

	this.Html = document.importNode(request.responseXML.childNodes[0], true);
}

Overview.Wall.Test.prototype.Update = function(time) {
	this.UpdateTimeElapsed(time);
}

Overview.Wall.Test.prototype.UpdateTimeElapsed = function(time) {
	var timeElapsed = time - this.Test.TimeLastFailed;

	var seconds = Math.floor(timeElapsed) % 60;

	var minutes = Math.floor(timeElapsed / 60) % 60;

	var hours = Math.floor(timeElapsed / 3600);

	this.TimeElapsedElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
}