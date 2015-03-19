Overview.Wall.ScheduledTask = function(id, scheduledTask) {
	this.Id = id;

	this.ScheduledTask = scheduledTask;

	this.Order = 0xF00000000;

	this.Element = Overview.Wall.ScheduledTask.Html.cloneNode(true);

	this.Element.ScheduledTask = scheduledTask;

	this.Element.addEventListener(
		"mouseover",
		function() {
			this.childNodes[0].childNodes[1].childNodes[1].style.height = this.childNodes[0].childNodes[1].childNodes[1].scrollHeight + "px";
		}
	);

	this.Element.addEventListener(
		"mouseout",
		function() {
			this.childNodes[0].childNodes[1].childNodes[1].style.height = "15px";
		}
	);

	this.PriorityLevelElement = this.Element.childNodes[0].childNodes[0];

	this.TitleElement = this.Element.childNodes[0].childNodes[1].childNodes[0];

	this.DescriptionElement = this.Element.childNodes[0].childNodes[1].childNodes[1];

	this.MoreInformationElement = this.Element.childNodes[0].childNodes[1].childNodes[2];

	this.LoadingCellElement = this.Element.childNodes[0].childNodes[2];

	this.ExecutionTimeElement = this.Element.childNodes[0].childNodes[3].childNodes[1];

	this.NextExecutionElement = this.Element.childNodes[0].childNodes[4].childNodes[1];

	this.LoopedCellElement = this.Element.childNodes[0].childNodes[5];
}

Overview.Wall.ScheduledTask.LoadHtml = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/js/page_overview_wall_scheduledtask.html?" + Math.random(), false);

	request.send(null);

	this.Html = document.createElement("div");

	this.Html.className = "ScheduledTask";

	this.Html.innerHTML = request.responseText.replace(/\t/g, "").replace(/\r/g, "").replace(/\n/g, "");
}

Overview.Wall.ScheduledTask.prototype.Update = function(time) {
	this.Update_Order(time);

	this.Update_ExecutionTime(time);

	this.Update_NextExecution(time);
}

Overview.Wall.ScheduledTask.prototype.Update_Order = function(time) {
	if(this.ScheduledTask.PriorityLevel === "Unknown") {
		this.Order = 0x000000000 - this.ScheduledTask.TimeUntilNextExecution;
	}
	else if(this.ScheduledTask.PriorityLevel === "High") {
		this.Order = 0x100000000 - this.ScheduledTask.TimeUntilNextExecution;
	}
	else if(this.ScheduledTask.PriorityLevel === "Medium") {
		this.Order = 0x200000000 - this.ScheduledTask.TimeUntilNextExecution;
	}
	else if(this.ScheduledTask.PriorityLevel === "Low") {
		this.Order = 0x300000000 - this.ScheduledTask.TimeUntilNextExecution;
	}
}

Overview.Wall.ScheduledTask.prototype.Update_ExecutionTime = function(time) {
	if(this.ScheduledTask.IsExecuting) {
		var executionTime = Math.max(0, time - this.ScheduledTask.TimeLastExecuted);

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

Overview.Wall.ScheduledTask.prototype.Update_NextExecution = function(time) {
	if(this.ScheduledTask.IsExecuting) {
		this.NextExecutionElement.parentNode.style.display = "none";

		if(this.ScheduledTask.ExecutionInterval <= 300) {
			this.LoopedCellElement.style.display = "table-cell";
		}
		else {
			this.LoopedCellElement.style.display = "none";
		}
	}
	else if(this.ScheduledTask.ExecutionInterval <= 300) {
		this.NextExecutionElement.parentNode.style.display = "none";

		this.LoopedCellElement.style.display = "table-cell";
	}
	else if(this.ScheduledTask.TimeLastExecuted === 0) {
		this.NextExecutionElement.innerHTML = "-";

		this.NextExecutionElement.className = "";

		this.NextExecutionElement.parentNode.style.display = "table-cell";

		this.LoopedCellElement.style.display = "none";
	}
	else {
		var time = Math.abs(this.ScheduledTask.TimeUntilNextExecution);

		var seconds = Math.floor(time % 60);

		var minutes = Math.floor(time / 60) % 60;

		var hours = Math.floor(time / 3600) % 24;

		var days = Math.floor(time / 86400);

		if(this.ScheduledTask.TimeUntilNextExecution < 0) {
			if(0 < days) {
				this.NextExecutionElement.innerHTML = "-" + days + "d " + (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
			}
			else {
				this.NextExecutionElement.innerHTML = "-" + (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
			}

			this.NextExecutionElement.className = "IsLate";
		}
		else {
			if(0 < days) {
				this.NextExecutionElement.innerHTML = days + "d " + (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
			}
			else {
				this.NextExecutionElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);
			}

			this.NextExecutionElement.className = "";
		}

		this.NextExecutionElement.parentNode.style.display = "table-cell";

		this.LoopedCellElement.style.display = "none";
	}
}

Overview.Wall.ScheduledTask.prototype.UpdateData = function() {
	this.PriorityLevelElement.className = this.ScheduledTask.PriorityLevel + "Priority";

	this.TitleElement.innerHTML = this.ScheduledTask.Title;

	this.DescriptionElement.innerHTML = "Task not executed as planned.";

	this.MoreInformationElement.style.display = 22.5 < this.DescriptionElement.scrollHeight ? "block" : "none";
}