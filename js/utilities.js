var UI = {
};
(function ()
{

	// UI Utility functions

	// Scope hack.
	var me = this.

	
	this.wrapInput(label, type, value)
	{
		return $("<div>" + label + ": <br><input type='" + type + "' size=30 value='" + value + "'> </div>");
	}

	this.wrapSelect(label)
	{
		return $("<div>" + label + ": <br><select></select> </div>");
	}

	

}).apply(UI);
