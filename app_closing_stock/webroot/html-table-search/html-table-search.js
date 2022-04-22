/**
	**options to have following keys:
		**searchText: this should hold the value of search text
		**searchPlaceHolder: this should hold the value of search input box placeholder
**/
/*
(function($){
	$.fn.tableSearch = function(options){
		if(!$(this).is('table')){
			return;
		}
		var tableObj = $(this),
			searchText = (options.searchText)?options.searchText:'',
			searchPlaceHolder = (options.searchPlaceHolder)?options.searchPlaceHolder:'',
			divObj = $('<div style="float:left; width: 100%; max-width:500px;"> '+searchText+'</div><br /><br />'),
			inputObj = $('<input type="search" class="form-control input-sm" placeholder="'+searchPlaceHolder+'" />'),
			caseSensitive = (options.caseSensitive===true)?true:false,
			searchFieldVal = '',
			pattern = '';
			inputObj.off('keyup').on('keyup', function(){
				searchFieldVal = $(this).val().trim();
				pattern = (caseSensitive)?RegExp(searchFieldVal):RegExp(searchFieldVal, 'i');
				tableObj.find('tbody tr').hide().each(function(){
					var currentRow = $(this);
					currentRow.find('td').each(function(){
						if(pattern.test($(this).html())){
							currentRow.show();
							return false;
						}
					});
				});
			});
		tableObj.before(divObj.append(inputObj));
		return tableObj;
	}
}(jQuery));
*/

// customized
$(document).ready(function(){
	searchBox = '<div class="input-group input-group-sm"><input type="text" id="search" class="form-control input-xs" placeholder="Search for..." /><span class="input-group-btn"><button class="btn btn-default" type="button">Search &nbsp;<span class="glyphicon glyphicon-search"></span></button></span></div>';
	
	$('.search-table').before(searchBox);
	var $rows = $('.table tbody tr');
	$('#search').keyup(debounce(function() {
		var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

		$rows.show().filter(function() {
			var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
			return !~text.indexOf(val);
		}).hide();
	}, 700));
	
	function debounce(func, wait, immediate) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if (!immediate) func.apply(context, args);
			};
			var callNow = immediate && !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) func.apply(context, args);
		};
	};
});