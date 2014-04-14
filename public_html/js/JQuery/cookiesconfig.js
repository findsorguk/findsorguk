    $(document).ready(function() {
        function cookieController() {
        }   
        $.cookiesDirective({
        	explicitConsent: true, // false allows implied consent
        	position: 'bottom', // top or bottom of viewport
        	duration: 10, // display time in seconds
        	limit: 0, // limit disclosure appearances, 0 is forever     
        	message: null, // customise the disclosure message              
        	cookieScripts: 'Google Analytics', // disclose cookie settings scripts
        	privacyPolicyUri: '/info/advice/privacy/',   // uri of your privacy policy
        	scriptWrapper: function(){}, // wrapper function for cookie setting scripts
        	fontFamily: 'helvetica', // font style for disclosure panel
        	fontColor: '#FFFFFF', // font color for disclosure panel
        	fontSize: '13px', // font size for disclosure panel
        	backgroundColor: '#000000', // background color of disclosure panel
        	backgroundOpacity: '80', // opacity of disclosure panel
        	linkColor: '#CA0000' // link color in disclosure panel
        });
    });
