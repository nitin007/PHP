$(function(){

	var validate_category =  function()
	{
		var count=1;
		if(($('form#category_form select option').size()))
		{
			if($.trim($('form#category_form input[name^=cat]').val()))
			{
				$('form#category_form select option').each(function(){
					if(($(this).text())==($('input[name^=cat]').val()))
					{
						alert($('form#category_form input[name^=cat]').val()+" category already exists!");
						$('form#category_form input[name^=cat]').val("");
						return;
					}
					else
					{
						if(count==($('form#category_form select option').length))
						{
							$('form#category_form select').append('<option name=option2 value='+$('input[name^=cat]').val()+'>'+$('input[name^=cat]').val()+'</option>');
							$('form#category_form input[name^=cat]').val("");
						}
						else
						{
							count++;
						}
					}
				});
			}
			else
			{
				alert('cannot add blank category!');
			}
		}
		else
		{
			if($.trim($('form#category_form input[name^=cat]').val()))
			{
				$('form#category_form select').append('<option name=option1 value='+$('input[name^=cat]').val()+'>'+$('input[name^=cat]').val()+'</option>');
				$('form#category_form input[name^=cat]').val("");
			}
			else
			{
				alert('cannot add blank category!');
			}
		}
	}
	
	var edit_category = function()
	{
		var edit_val = $('select#test :selected').text();
		var cat_id = $('select#test :selected').attr('id');
		$('select#test :selected').removeClass('saved');
		edit_val?$('form#category_form input[name=category]').val(edit_val):alert('select a value!');
		$(document).keyup(function(){
			$('select#test :selected').text($('form#category_form input[name=category]').val());
		});
		$('form#category_form').append('<input type=hidden name=edit value='+cat_id+'>');
	}
	
	var delete_category = function()
	{
		var cat_id = $('form#category_form select :selected').attr('id');
		$('form#category_form').append('<div class="confirm_cat_del"><table cellspacing=0px><tr><td colspan=2><center>Are you sure you want to delete category?</center></td></tr><tr><td><input type=submit name=sure value=SURE id=sure /></td><td><input type=button name=no value=NO></td></tr></table><input type=hidden name=remove_cat value='+cat_id+'></div>');
		$('div.confirm_cat_del').fadeIn('slow');
	}
	
	
	
	var add_article = function()
	{
	
		var category = $('form#category_form #test :selected').text();
		$('.article_table').append('<tr><td class="title"><input type="text" name="title"></td><td class="desc"><textarea name="desc" rows="5" cols="25"></textarea></td><td class="cat"><input type=text name=category readonly></td><td class="posted"></td><td class="modified"></td><td class="act"><input type="submit" name="save_article" value="SAVE" /></td></tr>');
		$('input[name=category]').val($('select#test').find(':selected').text());
	}
	
	var edit_article = function(this_obj)
	{
		var title = $(this_obj).parent().siblings('.title').text();
		var desc = $(this_obj).parent().siblings('.desc').text();
		var cat = $(this_obj).parent().siblings('.cat').text();
		var article_id = $(this_obj).attr('id');
		$(this_obj).parent().siblings('.title').empty();
		$(this_obj).parent().siblings('.desc').empty();
		$(this_obj).parent().siblings('.cat').empty();
		$(this_obj).parent().siblings('.title').append('<input type=text name=title value='+title+'>');
		$(this_obj).parent().siblings('.desc').append('<textarea name="desc" rows="5" cols="25">'+desc+'</textarea>');
		$(this_obj).parent().siblings('.cat').append('<input type=text name=category  readonly value='+cat+'>');
		$(this_obj).next().remove();
		$(this_obj).parent().append('<input type=hidden name=article_id value='+article_id+'>');
		$(this_obj).replaceWith('<input type="submit" name="save_article" value="SAVE" />');
	}
	
	
	var delete_article = function(this_obj)
	{
		var article_id = $(this_obj).attr('id');
		$('form#article_form').append('<div class="confirm_art_del"><table cellspacing=0px><tr><td colspan=2><center>Are you sure you want to delete article?</center></td></tr><tr><td><input type=submit name=del_article value=SURE id=sure /></td><td><input type=button name=no value=NO></td></tr></table><input type=hidden name=article_id value='+article_id+'></div>');
		$('div.confirm_art_del').fadeIn('slow');
	}
	
	
	$('form#category_form input[name=add_category]').click(function(event){
		validate_category();
		event.stopPropagation();
	});
	
	
	$('form#category_form input[name=edit_category]').click(function(event){
		edit_category();
		event.stopPropagation();
	});
	
	
	
	$('form#category_form input[name=remove_category]').click(function(event){
		if(!($('#test :selected').text()))
		{
			alert('Select a category to delete!');
			event.stopPropagation();
		}
		else
		{
			delete_category();
			event.stopPropagation();
		}	
	});
	
	
	
	$('form#category_form').submit(function(){
		if($('#test :selected').hasClass('saved'))
		{
			$(this).find(':selected').removeAttr("selected");
			return;
		}
	});
	
	
	$('form#category_form select#test').change(function(event){
		$('form#article_form input[name=category]').val($(this).find(':selected').text());
		event.stopPropagation();
	});
	
	
	$('form#article_form input[name=add_article]').click(function(event){
		add_article();
		event.stopPropagation();
	});
	
	
	$('form#article_form input[name=edit]').live("click", function(event){
		edit_article(this);
		event.stopPropagation();
	});
	
	
	$('form#article_form input[name=delete]').live("click", function(event){
		delete_article(this);
		event.stopPropagation();
	});
	
	
	$('input[name=no]').live("click", function(event){
		$(this).parents(':eq(4)').fadeOut('slow', function(){
			$(this).remove();
		});
		event.stopPropagation();
	});
	
	
	$('form#visit span#all a').click(function(){
		//alert('fd');
		$('input[name=my]').remove();
		$(this).prepend('<input type=hidden name=default value=all>');
		$(this).parents('form').submit();
	});
	
		$('form#visit span#my a').click(function(){
		//alert('fd');
		$('input[name=default]').remove();
		$(this).prepend('<input type=hidden name=my value=mine>');
		$(this).parents('form').submit();
	});

});
