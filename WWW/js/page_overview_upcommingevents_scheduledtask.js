Overview.UpcommingEvents.ScheduledTask = function(id, scheduledTask) {
	this.Id = id;

	this.ScheduledTask = scheduledTask;

	this.Order = 0xF00000000;

	this.Element = Overview.UpcommingEvents.ScheduledTask.Html.cloneNode(true);

	this.Element.ScheduledTask = scheduledTask;

	this.TitleElement = this.Element.childNodes[0].childNodes[0].childNodes[0];

	this.LoadingCellElement = this.Element.childNodes[0].childNodes[1];

	this.ExecutionTimeElement = this.Element.childNodes[0].childNodes[2].childNodes[1];

	this.NextExecutionElement = this.Element.childNodes[0].childNodes[3].childNodes[1];

	this.LoopedCellElement = this.Element.childNodes[0].childNodes[4];
}

Overview.UpcommingEvents.ScheduledTask.LoadHtml = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/js/page_overview_upcommingevents_scheduledtask.html?" + Math.random(), false);

	request.send(null);

	this.Html = document.createElement("div");

	this.Html.className = "ScheduledTask";

	this.Html.innerHTML = request.responseText.replace(/\t/g, "").replace(/\r/g, "").replace(/\n/g, "");
}

Overview.UpcommingEvents.ScheduledTask.prototype.Update = function(time) {
	this.Update_Order(time);

	this.Update_ExecutionTime(time);

	this.Update_NextExecution(time);
}

Overview.UpcommingEvents.ScheduledTask.prototype.Update_Order = function(time) {
	if(this.ScheduledTask.TimeLastExecuted === 0) {
		this.Order = 0x200000000;
	}
	else if(this.ScheduledTask.ExecutionInterval <= 300) {
		this.Order = 0x100000000;
	}
	else if(this.ScheduledTask.IsExecuting) {
		this.Order = 0x000000000;
	}
	else {
		this.Order = 0x000000000 + this.ScheduledTask.TimeUntilNextExecution;
	}
}

Overview.UpcommingEvents.ScheduledTask.prototype.Update_ExecutionTime = function(time) {
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

Overview.UpcommingEvents.ScheduledTask.prototype.Update_NextExecution = function(time) {
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

Overview.UpcommingEvents.ScheduledTask.prototype.UpdateData = function() {
	this.TitleElement.innerHTML = this.ScheduledTask.Title;
}