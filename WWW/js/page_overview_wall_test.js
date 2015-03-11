Overview.Wall.Test = function(id, test) {
	this.Id = id;

	this.Test = test;

	this.Element = Overview.Wall.Test.Html.cloneNode(true);

	this.PriorityLevelElement = this.Element.childNodes[0];

	this.TitleElement = this.Element.childNodes[1].childNodes[0];

	this.DescriptionElement = this.Element.childNodes[1].childNodes[1];

	this.NextExecutionElement = this.Element.childNodes[2].childNodes[1];

	this.TimeElapsedElement = this.Element.childNodes[3].childNodes[1];
}

Overview.Wall.Test.LoadHtml = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/js/page_overview_wall_test.xml", false);

	request.send(null);

	this.Html = document.importNode(request.responseXML.childNodes[0], true);
}

Overview.Wall.Test.prototype.Update = function(time) {
	this.UpdateTimeElapsed(time);

	this.UpdateNextExecution(time);
}

Overview.Wall.Test.prototype.UpdateTimeElapsed = function(time) {
	var timeElapsed = Math.floor(time - this.Test.TimeLastFailed);

	var seconds = timeElapsed % 60;

	var minutes = Math.floor(timeElapsed / 60) % 60;

	var hours = Math.floor(timeElapsed / 3600);

	this.TimeElapsedElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
}

Overview.Wall.Test.prototype.UpdateNextExecution = function(time) {
	var nextExecution = Math.ceil(this.Test.TimeLastExecuted + this.Test.ExecutionInterval - time);

	var seconds = nextExecution % 60;

	var minutes = Math.floor(nextExecution / 60) % 60;

	var hours = Math.floor(nextExecution / 3600);

	this.NextExecutionElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
}