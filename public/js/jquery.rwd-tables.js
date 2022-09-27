$.fn.rwdTables = function() {
  	this.each(function() {
		var tableHeadings = jQuery('th', this);
	    $( "tbody td", this ).wrapInner( "<span class='rwd-tables tbody'></div>");
	    $( "tfoot", this).insertAfter($( "tbody", this ));
		$( "tbody tr", this ).each(function( index ) {
		  	$( "td", this ).each(function( index ) {
				$( "span", this ).before( "<span class='rwd-tables thead'>" + tableHeadings.eq(index).html() + "</span>"); 
			});
		});
	});
	return this;
};