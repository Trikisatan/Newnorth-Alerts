Overview = {};

Overview.Load = function() {
	this.Wall.Load();
}

Overview.Update = function(time) {
	Overview.Wall.Update(time);
}