let checker=false;
let data = "";
let item = null;
function validationPayment(val,id,type) {
    if (type==="num"){
        const numValid = ValidateNum(val);
        if (!id){
            item = $("#amount")[0];
        }else {
            item=$("#amount_"+id)[0]
        }
        if (item){
            if (!numValid.valid){
               if ($(".success").find(item)[0]){
                   $(".success").find(item).parent().parent().removeClass("success")
               }
                $(item).parent().parent().addClass("error")

                $(item).parent().parent().find(" label").text(numValid.message);
            }else {
                if ($(".error").find(item)[0]){
                    $(".error").find(item).parent().parent().removeClass("error")
                }
                $(item).parent().parent().addClass("success")
                if ($(item).attr("aria-label")){
                    $(item).parent().parent().find(" label").text("Amount")
                }else {
                    $(item).parent().parent().find(" label").text("Select amount")
                }
            }
        }
    }else {
        if (type ==="email"){
            const emailValid = email(val)
            if (emailValid.valid){
                $("#email_"+id).parent().parent().find(" label").text("Email");
                if ($(".error").find("#email_"+id)[0]){
                    $(".error").find("#email_"+id).parent().parent().removeClass("error")
                }
                $("#email_"+id).parent().parent().addClass("success")
            }else {
                if ($(".success").find("#email_"+id)[0]){
                    $(".success").find("#email_"+id).parent().parent().removeClass("success")
                }
                $("#email_"+id).parent().parent().addClass("error")
                $("#email_"+id).parent().parent().find(" label").text(emailValid.message.split("Your ")[1]);
            }
        }else {
            if (isValidDate(val)){
                if ($("#deadline").parent().parent().find(" input").attr("id") === $(".error").find(" input").attr("id")){
                    $("#deadline").parent().parent().removeClass("error")
                }
                $("#deadline").parent().parent().addClass("success")
                $("#deadline").parent().parent().find(" label").text("Deadline")
            }else {
                if ($("#deadline").parent().parent().find(" input").attr("id") === $(".success").find(" input").attr("id")){
                    $("#deadline").parent().parent().removeClass("success")
                }
                $("#deadline").parent().parent().addClass("error")
                $("#deadline").parent().parent().find(" label").text("Deadline invalid")
            }
        }
    }
}
function validation(value,field){
    if (field === "First_name" || field==="Last_name"){
      checker = nameValidation(value,field);
    }else {
        if (field ==="email"){
         checker=email(value)
        }
        if (field==="password"){
         checker=password(value)
        }
        if (field==="re-pass"){
            if ($("[data-type='password']").val()!=="" && $("[data-type='password']").val() === value){
                checker={valid: true,message: ""}
            }else {
                checker=re_pass(value,$("[data-type='password']").val())
            }
        }
    }
    if (checker.valid){
        if ($(".error")[0] && $(".error")[0].dataset.col===field){
            $("[data-col='"+field+"']").removeClass("error");
        }
        $("[data-col='"+field+"']").addClass("success");
        $("[data-col='"+field+"']").find(" span").hide();
    }else {
        if ($(".success")[0] && $(".success")[0].dataset.col===field){
            $("[data-col='"+field+"']").removeClass("success");
        }
        $("[data-col='"+field+"']").addClass("error");
        $("[data-col='"+field+"']").find(" span").text(checker.message)
        $("[data-col='"+field+"']").find(" span").show();
    }
}


function handleSubmit(value) {
    event.preventDefault()
    $(value).find(" input").map(item=>{
        validation($(value).find(" input")[item].value,$(value).find(" input")[item].dataset.type)
    })
    if ($(".success").length===$(".form-control").length){
        if ($(" title").text() ==="Sign In"){
            data="email="+$("[data-type='email']").val()+"&password="+$("[data-type='password']").val()+"&action=login"
        }else {
            data="f_name="+$("[data-type='First_name']").val()+"&l_name="+$("[data-type='Last_name']").val()+"&email="+$("[data-type='email']").val()+"&password="+$("[data-type='password']").val()+"&action=register"
        }
        $.ajax({
            type: $(value).attr("method"),
            url: $(value).attr("action"),
            data: data,
            dataType: "json",
            success: function (data) {
                 if (data.response ==="error"){
                     $(" input").parent().map(function (index,item) {
                        if ($(".success")[index] && $(".success")[index].dataset.col === item.dataset.col){
                            $(item).removeClass("success")
                        }
                         $(item).addClass("error");
                         $(item).find(" span").hide();
                     })
                     $("#alert").show(200,function () {
                         if ($(this).children().length){
                             $(this).children().remove()
                         }
                         $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                                data.message+
                             "        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                             "            <span aria-hidden=\"true\">&times;</span>\n" +
                             "        </button>\n" +
                             "    </div>")
                         $(this).css("display","block")
                     })
                 }else {
                     $(" input").parent().map(function (index,item) {
                         if ($(".error")[index] && $(".error")[index].dataset.col === item.dataset.col){
                             $(item).removeClass("error")
                         }
                         $(item).addClass("success");
                         $(item).find(" span").hide();
                     })
                     $("#alert").hide(200)
                     if (data.message){
                         $("#alert").show(200,function () {
                             if ($(this).children().length){
                                 $(this).children().remove()
                             }
                             $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">\n" +
                                 data.message+
                                 "        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                                 "            <span aria-hidden=\"true\">&times;</span>\n" +
                                 "        </button>\n" +
                                 "    </div>")
                             $(this).css("display","block")
                             window.location.href = ".";
                         })
                     }else {
                         window.location.href = "dashboard.php";
                     }
                 }
            },
            error: function (err) {
                $("#alert").show(200,function () {
                    if ($(this).children().length){
                        $(this).children().remove()
                    }
                    $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                        "        Something went wrong please check in is everything correct?.\n" +
                        "        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                        "            <span aria-hidden=\"true\">&times;</span>\n" +
                        "        </button>\n" +
                        "    </div>")
                    $(this).css("display","block")
                })
            }
        })
    }else {
        $("#alert").show(200,function () {
            if ($(this).children().length){
                $(this).children().remove()
            }
            $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                "        Something went wrong please check in is everything correct?.\n" +
                "        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                "            <span aria-hidden=\"true\">&times;</span>\n" +
                "        </button>\n" +
                "    </div>")
            $(this).css("display","block")
        })
    }
}
$(".close").click(function () {
    event.preventDefault()
    $("#alert").hide(200,function () {
        $(this).find(" div").remove()
        $(this).css("display","none")
    })
})
$(".form-control").on("keyup",function(){
    validation($(this).val(),this.dataset.type)
});
$(".form-control").focus(function () {
    validation($(this).val(),this.dataset.type)
})
$(".form-control").focusout(function () {
    validation($(this).val(),this.dataset.type)
})
