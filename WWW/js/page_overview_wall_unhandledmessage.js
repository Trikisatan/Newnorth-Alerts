Overview.Wall.UnhandledMessage = function(id, unhandledMessage) {
	this.Id = id;

	this.UnhandledMessage = unhandledMessage;

	this.Order = 0xF00000000;

	this.Element = Overview.Wall.UnhandledMessage.Html.cloneNode(true);

	this.Element.UnhandledMessage = unhandledMessage;

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

	this.TimeCreatedElement = this.Element.childNodes[0].childNodes[2].childNodes[1];
}

Overview.Wall.UnhandledMessage.LoadHtml = function() {
	var request = new XMLHttpRequest();

	request.open("GET", "/js/page_overview_wall_unhandledmessage.html?" + Math.random(), false);

	request.send(null);

	this.Html = document.createElement("div");

	this.Html.className = "UnhandledMessage";

	this.Html.innerHTML = request.responseText.replace(/\t/g, "").replace(/\r/g, "").replace(/\n/g, "");
}

Overview.Wall.UnhandledMessage.prototype.Update = function(time) {
	this.Update_Order(time);

	this.Update_TimeElapsed(time);
}

Overview.Wall.UnhandledMessage.prototype.Update_Order = function(time) {
	if(this.UnhandledMessage.PriorityLevel === "Unknown") {
		this.Order = 0x000000000 + this.UnhandledMessage.TimeCreated;
	}
	else if(this.UnhandledMessage.PriorityLevel === "High") {
		this.Order = 0x100000000 + this.UnhandledMessage.TimeCreated;
	}
	else if(this.UnhandledMessage.PriorityLevel === "Medium") {
		this.Order = 0x200000000 + this.UnhandledMessage.TimeCreated;
	}
	else if(this.UnhandledMessage.PriorityLevel === "Low") {
		this.Order = 0x300000000 + this.UnhandledMessage.TimeCreated;
	}
}

Overview.Wall.UnhandledMessage.prototype.Update_TimeElapsed = function(time) {
 	var elapsedTimeSinceCreated = Math.max(0, time - this.UnhandledMessage.TimeCreated);

	var seconds = Math.floor(elapsedTimeSinceCreated % 60);

	var minutes = Math.floor(elapsedTimeSinceCreated / 60) % 60;

	var hours = Math.floor(elapsedTimeSinceCreated / 3600);

	this.TimeCreatedElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);

	this.TimeCreatedElement.parentNode.style.display = "table-cell";
}

Overview.Wall.UnhandledMessage.prototype.UpdateData = function() {
	this.PriorityLevelElement.className = this.UnhandledMessage.PriorityLevel + "Priority";

	this.TitleElement.innerHTML = this.UnhandledMessage.Title;

	this.DescriptionElement.innerHTML = this.UnhandledMessage.Text.substring(0, 100) + "...";

	this.MoreInformationElement.style.display = 22.5 < this.DescriptionElement.scrollHeight ? "block" : "none";

	var time = Date.now() / 1000;

	var elapsedTimeSinceCreated = Math.max(0, time - this.UnhandledMessage.TimeCreated);

	var seconds = Math.floor(elapsedTimeSinceCreated % 60);

	var minutes = Math.floor(elapsedTimeSinceCreated / 60) % 60;

	var hours = Math.floor(elapsedTimeSinceCreated / 3600);

	this.TimeCreatedElement.innerHTML = (9 < hours ? hours : "0" + hours) + ":" + (9 < minutes ? minutes : "0" + minutes) + ":" + (9 < seconds ? seconds : "0" + seconds);

	this.TimeCreatedElement.parentNode.style.display = "table-cell";
}