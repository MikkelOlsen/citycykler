ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );


var inputs = document.querySelectorAll( '.inputfile' );
Array.prototype.forEach.call( inputs, function( input )
{
	var label	 = input.nextElementSibling,
		labelVal = label.innerHTML;

	input.addEventListener( 'change', function( e )
	{
		var fileName = '';
			fileName = e.target.value.split( '\\' ).pop();

        if( fileName.length >= 40) {
            fileName = fileName.substring(0, 37) + "...";
        }

		if( fileName )
            
			label.querySelector( 'span' ).innerHTML = fileName;
		else
			label.innerHTML = labelVal;
	});
});

var select_element = document.getElementById('multiColor');
multi( select_element, {
	'enable_search': true,
    'search_placeholder': 'Søg...',
    'non_selected_header': 'Farve muligheder',
    'selected_header': 'Valgte farver'
} );