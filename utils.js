var SEV_CRITICAL = 1;
var SEV_WARNING = 2;
var SEV_INFO = 3;
var SEV_DEBUG = 4;

var DEB_LEVEL_PROD = 1;
var DEB_LEVEL_DEBUG = 2;


//Log a message on the server using ajax
function log(msg, log_level, severity){
	
	$.ajax({url: "logging.php",
		    async: false,
		    data: "msg=" + msg + "&severity=" + severity
		   })
	  .done(function() {
	    //alert( "success" );
	  })
	  .fail(function() {
	    //alert( "error" );
	  })
	  .always(function() {
	    //alert( "complete" );
	  });
}