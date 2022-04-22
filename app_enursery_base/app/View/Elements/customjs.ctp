<script type="text/javascript">
// $(document).ready(function(){
	
// });

function refreshPage(){
	location.reload();
}

var prevSubdomainText = '';
function showSubdomainUrl() {
	$('#sitenametext').css('display', 'block');	
	var siteName = $('#SiteDomainName').val();	
	if(siteName != '') {
		var numericExpression = /^[A-Za-z0-9]+$/;
		if(siteName.match(numericExpression)) {
			$('#sitenametext').text('http://'+siteName+'.letsgreenify.com');			
			prevSubdomainText = siteName;
		}
		else {
			alert('Special characters and spaces are not allowed');
			$('#SiteDomainName').val(prevSubdomainText);
			$('#sitenametext').focus();	
		}
	}
	else {
		$('#sitenametext').text('*Enter Subdomain Name');	
	}
}

function setSubdomainName() {
	$('#sitenametext').css('display', 'block');	
	var siteTitle = $('#SiteTitle').val();	
	if(siteTitle != '') {
		var numericExpression = /^[A-Za-z0-9]+$/;
		siteTitle = siteTitle.toLowerCase();
		var subdomainText = siteTitle.replace(/[^a-zA-Z\d]/gi, '');
		$('#SiteDomainName').val(subdomainText);
		$('#sitenametext').text('http://'+subdomainText+'.letsgreenify.com');	
	}
	else {
		$('#sitenametext').text('*Enter Subdomain Name');	
	}
}
</script>

