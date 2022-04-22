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
			$('#sitenametext').text('http://'+siteName+'.enursery.in');			
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

function showRequestPriceQuoteForm(categoryID, productID, productName) {
	//RPQF: Request Price Quote Form 
	$('#RPQF').attr('action', '/shopping_carts/requestQuoteForProduct/'+categoryID+'/'+productID);
	
	$('#RPQF-Div').dialog({ width: 'auto', height:200, title:'Request Price Quote - '+productName, modal: true });
}

function showAddToCartForm(categoryID, productID, productName) {
	//RPQF: Request Price Quote Form 
	$('#RPQF').attr('action', '/shopping_carts/add/'+categoryID+'/'+productID);
	
	$('#RPQF-Div').dialog({ width: 'auto', height:200, title:'Add To Cart - '+productName, modal: true });
}

</script>

