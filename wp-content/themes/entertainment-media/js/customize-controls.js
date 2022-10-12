( function( api ) {

	// Extends our custom "entertainment-media" section.
	api.sectionConstructor['entertainment-media'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );