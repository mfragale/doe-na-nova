jQuery(document).ready(function(e){e("#form-doar #amount").on("keyup change",function(){var o;o=e("#form-doar #amount").val().length,e(".amount_width_container").animate({width:"40"*o},100,function(){})}),e("#amount").keydown(function(o){-1!==e.inArray(o.keyCode,[46,8,9,27,13,110,190])||65===o.keyCode&&(!0===o.ctrlKey||!0===o.metaKey)||o.keyCode>=35&&o.keyCode<=40||(o.shiftKey||o.keyCode<48||o.keyCode>57)&&(o.keyCode<96||o.keyCode>105)&&o.preventDefault()}),e("#doenanova-wrap").on("click",".load-on-click",function(o){o.preventDefault(),e(this).attr("disabled","disabled").addClass("loading").html('<i class="fas fa-circle-notch fa-spin"></i>');var n=e(this).parent();n.is("form")?n.submit():document.location.href=e(this).attr("href")}),e("#doacoes-recorrentes").on("click",".dropdown-menu-btn",function(){var o=e(this).data("target");e("#"+o).toggleClass("is-active"),e(this).children().toggleClass("fa-rotate-90")}),e("#doenanova-wrap").on("click",".doenanova-report",function(o){o.preventDefault(),e("#doenanova-report").fadeIn()}),e("#doenanova-wrap").on("click",".doenanova-report-close",function(o){o.preventDefault(),e("#doenanova-report").fadeOut()}),e("#doenanova-wrap").on("click",".doenanova-how-to-use",function(o){o.preventDefault(),e("#doenanova-how-to-use").fadeIn()}),e("#doenanova-wrap").on("click",".doenanova-how-to-use-close",function(o){o.preventDefault(),e("#doenanova-how-to-use").fadeOut()})});