jQuery(document).ready(function(a){console.log("HELLO doenanova_load_more_cards"),console.log(phpVars),a("#loadmore_cards-btn").click(function(o){console.log("Load more button clicked"),dataSend={},dataSend.action=phpVars["wp-action-doenanova-load-more-cards"],dataSend.last_card=a("#last_card").val(),a(".loadmore_cards-message").hide();var e=a("#loadmore_cards-btn").html();a("#loadmore_cards-btn").attr("disabled","disabled").addClass("loading").html('<i class="fas fa-circle-notch fa-spin"></i>'),a.ajax({type:"post",url:phpVars["checkout-url"],data:dataSend,dataType:"json"}).done(function(o,l){console.log("$.ajax.done"),console.log("data: ",o),console.log("status: ",l),a("#loadmore_cards-btn").removeAttr("disabled").removeClass("loading").html(e)}).fail(function(o,l,d){console.log("$.ajax.fail"),console.log("data: ",o),console.log("status: ",l),console.log("error: ",d),a("#loadmore_cards-btn").removeAttr("disabled").removeClass("loading").html(e),a("#loadmore_cards-fail-message").fadeIn(800)}),o.preventDefault()})});