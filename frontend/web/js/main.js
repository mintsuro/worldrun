// Активация промокода через ajax
jQuery("#active-promocode-test").click(function(e){
    var path = "";
    var element = this;
    var valueCode = jQuery("#input-value-code").val();

    $.ajax({
        url: path,
        type: "POST",
        data: { code: valueCode },
        dataType: "json",
        success: function(data){
            if(data == null){
                $(".code-status").addClass("show bg-danger");
            }else{
                $(".code-status").addClass("show bg-success").removeClass("bg-danger").text("Промокод активирован.");
                $(".total-info .numb").text(parseFloat($(".total-info .numb").text())) - parseFloat(data);
            }
            console.log(data);
        },
        error: function(err){
            console.log("Ошибка запроса активации.");
        }
    });
});