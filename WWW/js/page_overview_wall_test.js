Overview.Wall.Test = function(id, test) {
	this.Id = id;

	this.Test = test;

	this.Element = Overview.Wall.Test.Html.cloneNode(true);

	this.Element.Test = test;

	this.Element.addEventListener(
		"mouseover",
		function() {
			this.childNodes[1].childNodes[1].style.height = this.childNodes[1].childNodes[1].scrollHeight + "px";
		}
	);

	this.Element.addEventListener(
		"mouseout",
		function() {
			this.childNodes[1].childNodes[1].style.height = "15px";
		}
	);

	this.PriorityLevelElement = this.Element.childNodes[0];

	this.TitleElement = this.Element.childNodes[1].childNodes[0];

	this.DescriptionElement = this.Element.childNodes[1].childNodes[1];

	this.MoreInformationElement = this.Element.childNodes[1].childNodes[2];

	this.LoadingCellElement = this.Element.childNodes[2];

	this.ExecutionTimeElement = this.Element.childNodes[3].childNodes[1];

	this.ReloadCellElement = this.Element.childNodes[4];

	this.ReloadCellElement.childNodes[0].addEventListener(
		"click",
		function() {
			ExecuteTest(this.parentNode.parentNode.Test, true);
		}
	);

	this.NextExecutionElement = this.Element.childNodes[5].childNodes[1];

	this.TimeElapsedElement = this.Element.childNodes[6].childNodes[1];
}

Overview.Wall.Test.LoadHtml = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/js/page_overview_wall_test.html", false);

	request.send(null);

	this.Html = document.createElement("div");

	this.Html.className = "Test";

	this.Html.innerHTML = request.responseText.replace(/\n/g, "");
}

Overview.Wall.Test.prototype.Update = function(time) {
	this.Update_ExecutionTime(time);

	this.Update_NextExecution(time);

	this.Update_TimeElapsed(time);
}

Overview.Wall.Test.prototype.Update_ExecutionTime = function(time) {
	if(this.Test.IsExecuting) {
		var executionTime = Math.max(0, time - this.Test.TimeLastExecuted);

		var seconds = Math.floor(executionTime % 60);

		var minutes = Math.floor(executionTime / 60) % 60;

		var hours = Math.floor(executionTime / 3600);

		this.ExecutionTimeElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);

		this.LoadingCellElement.style.display = "table-cell";

		this.ExecutionTimeElement.parentNode.style.display = "table-cell";
	}
	else {
		this.LoadingCellElement.style.display = "none";

		this.ExecutionTimeElement.parentNode.style.display = "none";
	}
}

Overview.Wall.Test.prototype.Update_NextExecution = function(time) {
	if(this.Test.IsExecuting) {
		this.ReloadCellElement.style.display = "none";

		this.NextExecutionElement.parentNode.style.display = "none";
	}
	else {
		var nextExecution = Math.max(0, this.Test.TimeLastExecuted + this.Test.ExecutionInterval - time);

		var seconds = Math.floor(nextExecution % 60);

		var minutes = Math.floor(nextExecution / 60) % 60;

		var hours = Math.floor(nextExecution / 3600);

		this.NextExecutionElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);

		this.ReloadCellElement.style.display = "table-cell";

		this.NextExecutionElement.parentNode.style.display = "table-cell";
	}
}

Overview.Wall.Test.prototype.Update_TimeElapsed = function(time) {
	var timeElapsed = time - this.Test.TimeLastFailed;

	var seconds = Math.floor(timeElapsed % 60);

	var minutes = Math.floor(timeElapsed / 60) % 60;

	var hours = Math.floor(timeElapsed / 3600);

	this.TimeElapsedElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
}

Overview.Wall.Test.prototype.UpdateData = function() {
	this.PriorityLevelElement.className = this.Test.StatePriorityLevel + "Priority";

	this.TitleElement.innerHTML = this.Test.Title;

	this.DescriptionElement.innerHTML = this.Test.StateDescription;

	this.MoreInformationElement.style.display = 22.5 < this.DescriptionElement.scrollHeight ? "block" : "none";
}