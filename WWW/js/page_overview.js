Overview = {};

Overview.Load = function() {
	this.Wall.Load();

	this.UpcommingEvents.Load();
}

Overview.Update = function(time) {
	Overview.Wall.Update(time);

	Overview.UpcommingEvents.Update(time);
}