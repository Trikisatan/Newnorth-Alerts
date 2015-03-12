Overview.Wall.Test = function(id, test) {
	this.Id = id;

	this.Test = test;

	this.Element = Overview.Wall.Test.Html.cloneNode(true);

	this.Element.Test = test;

	this.PriorityLevelElement = this.Element.childNodes[0];

	this.TitleElement = this.Element.childNodes[1].childNodes[0];

	this.DescriptionElement = this.Element.childNodes[1].childNodes[1];

	this.ReloadCellElement = this.Element.childNodes[2];

	this.ReloadCellElement.childNodes[0].addEventListener(
		"click",
		function() {
			ExecuteTest(this.parentNode.parentNode.Test, true);
		}
	);

	this.NextExecutionElement = this.Element.childNodes[3].childNodes[1];

	this.LoadingCellElement = this.Element.childNodes[4];

	this.TimeElapsedElement = this.Element.childNodes[5].childNodes[1];
}

Overview.Wall.Test.LoadHtml = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/js/page_overview_wall_test.html", false);

	request.send(null);

	this.Html = document.createElement("div");

	this.Html.className = "Alert";

	this.Html.innerHTML = request.responseText;
}

Overview.Wall.Test.prototype.Update = function(time) {
	this.UpdateTimeElapsed(time);

	this.UpdateNextExecution(time);
}

Overview.Wall.Test.prototype.UpdateTimeElapsed = function(time) {
	var timeElapsed = time - this.Test.TimeLastFailed;

	var seconds = Math.floor(timeElapsed % 60);

	var minutes = Math.floor(timeElapsed / 60) % 60;

	var hours = Math.floor(timeElapsed / 3600);

	this.TimeElapsedElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
}

Overview.Wall.Test.prototype.UpdateNextExecution = function(time) {
	if(this.Test.IsExecuting) {
		this.ReloadCellElement.style.display = "none";

		this.NextExecutionElement.parentNode.style.display = "none";

		this.LoadingCellElement.style.display = "table-cell";
	}
	else {
		var nextExecution = Math.max(0, this.Test.TimeLastExecuted + this.Test.ExecutionInterval - time);

		var seconds = Math.floor(nextExecution % 60);

		var minutes = Math.floor(nextExecution / 60) % 60;

		var hours = Math.floor(nextExecution / 3600);

		this.NextExecutionElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);

		this.ReloadCellElement.style.display = "table-cell";

		this.NextExecutionElement.parentNode.style.display = "table-cell";

		this.LoadingCellElement.style.display = "none";
	}
}