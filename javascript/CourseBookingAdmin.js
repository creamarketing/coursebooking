// disable separator resizing, it messes with the calendar resizing!
DraggableSeparator.prototype.onmousedown = function() {};

// set tree-node selection to normal anchor behaviour (i.e. go to href location)
TreeNodeAPI.prototype.selectTreeNode = function() {
	if (this.getElementsByTagName('a')[0].href) {
		location.href = this.getElementsByTagName('a')[0].href;
	}
};

// disable tree-node context-menu
TreeNodeAPI.prototype.oncontextmenu = function(event){};

// needed?
/*
jQuery(document).ready(function() {
	jQuery('div.dialogtabset').tabs();

});
*/