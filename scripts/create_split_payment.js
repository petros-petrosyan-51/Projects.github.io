let count = 1;
let amount=0;
let emailValid=false;
let payers=[];
let payment = "";
const searchParams = new URLSearchParams(window.location.search);
let edit = false;
let dec=null;
let added = [];
function closed(id){
    added.splice(added.length-1,1)
    if ($("#append").children().length <= 1){
        $("#send").hide(200,function () {
              $(this).css("display","none")
        })
    }
    $("#card"+id).slideUp(500,function () {
         $(this).remove()
         count=$("#append").children().length;
    })
    if ($("#append").children().length === 3){
        $("#append").css("overflow-y","hidden")
    }
}
function closeAlert(item){
    $(item).parent().hide(200,function () {
        $(this).parent().css("display","none")
       $(this).remove();
    })
}
$( document).ready(function () {
    if (!searchParams.has('open')){
        $("#payment").hide();
    }else {
        let str = searchParams.get('open');
        if (!parseInt(str)){
            dec =window.atob(str);
           if (dec){
               $.ajax({
                   type: "post",
                   url: "main.php",
                   data: "id="+dec+"&action=GetPayment",
                   dataType: "json",
                   success: function(data){
                       $("#amount").val(data.amount)
                       $(function() {
                           $('.dates #deadline').datepicker('update',data.date.split(" ")[0]);
                       });
                       for (let i=0;i<JSON.parse(data.payers).length;i++){
                           if (JSON.parse(data.payers)[i].status ==="unpaid" && JSON.parse(data.payers)[i].user_email !==$("#staticEmail").val()){
                               if ($("#send").css("display")==="none"){
                                   $("#send").show(200,function () {
                                       $(this).css("display","block")
                                   })
                               }
                               if (count > 2){
                                   $("#append").css("overflow-y","scroll")
                               }
                               $("#append").append("<div  class=\"card\" id='card"+count+"' style=\"margin-bottom: 20px;display: none;margin-top: 20px\">\n" +
                                   "    <div class=\"card-header d-flex\">\n" +
                                   "        <label  class=\"col-form-label p-2\">User</label>\n" +
                                   "        <button onclick='closed(this.dataset.count)'  type=\"button\" data-count='"+count+"' class=\"close ml-auto p-2 \" aria-label=\"Close\">\n" +
                                   "            <span aria-hidden=\"true\">&times;</span>\n" +
                                   "        </button>\n" +
                                   "    </div>\n" +
                                   "    <div class=\"card-body\">\n" +
                                   "        <div class=\"form-group row\">\n" +
                                   "            <label for=\"amount_"+count+"\" class=\"col-sm-2 col-form-label\">Select amount</label>\n" +
                                   "            <div class=\"col-sm-10\">\n" +
                                   "                <input readonly onfocus='validationPayment(this.value,this.dataset.id,this.dataset.type)' onkeyup='validationPayment(this.value,this.dataset.id,this.dataset.type)' onchange='validationPayment(this.value,this.dataset.id,this.dataset.type)' data-type='num' data-id='"+count+"' type=\"text\" id=\"amount_"+count+"\" class=\"form-control\" value='"+JSON.parse(data.payers)[i].user_debt+"' placeholder=\"0000$\">\n" +
                                   "            </div>\n" +
                                   "        </div>\n" +
                                   "        <div class=\"form-group row\">\n" +
                                   "            <label for=\"email_"+count+"\" class=\"col-sm-2 col-form-label\">Email</label>\n" +
                                   "            <div class=\"col-sm-10\">\n" +
                                   "                <input readonly onfocus='validationPayment(this.value,this.dataset.id,this.dataset.type)' onkeyup='validationPayment(this.value,this.dataset.id,this.dataset.type)' onchange='validationPayment(this.value,this.dataset.id,this.dataset.type)' data-type='email' data-id='"+count+"' type=\"email\" id=\"email_"+count+"\"  class=\"form-control\" value='"+JSON.parse(data.payers)[i].user_email+"' placeholder=\"member@example.com\">\n" +
                                   "            </div>\n" +
                                   "        </div>\n" +
                                   "    </div>\n" +
                                   "</div>")
                               $("#card"+count).hide();
                               $("#card"+count).slideDown(500,function () {
                                   $(this).css("display","block")
                               })
                               count=count+1;
                               edit=true;
                           }
                       }
                   }
               })
           }
        }
    }

    $("[data-close]").click(function () {
         if ($("#payment").css("display") ==="none"){
             $("#payment").slideDown(500,function () {
                 $(this).css("display","block")
             })
         }else {
             $("#payment").slideUp(500,function () {
                 $(this).css("display","none")
             })
         }
    })
    $("#add").click(function () {
        if ($("#card"+count)[0]){
            count=count+1;
        }
        if (dec){
            if (!added.includes($("#append").children().length)){
                added.push($("#append").children().length);
            }
        }
        if ($("#send").css("display")==="none"){
            $("#send").show(200,function () {
                $(this).css("display","block")
            })
        }
        if (count <= 10){
            if (count > 2){
                $("#append").css("overflow-y","scroll")
            }
            $("#append").append("<div  class=\"card\" id='card"+count+"' style=\"margin-bottom: 20px;display: none;margin-top: 20px\">\n" +
                "    <div class=\"card-header d-flex\">\n" +
                "        <label  class=\"col-form-label p-2\">User</label>\n" +
                "        <button onclick='closed(this.dataset.count)'  type=\"button\" data-count='"+count+"' class=\"close ml-auto p-2 \" aria-label=\"Close\">\n" +
                "            <span aria-hidden=\"true\">&times;</span>\n" +
                "        </button>\n" +
                "    </div>\n" +
                "    <div class=\"card-body\">\n" +
                "        <div class=\"form-group row\">\n" +
                "            <label for=\"amount_"+count+"\" class=\"col-sm-2 col-form-label\">Select amount</label>\n" +
                "            <div class=\"col-sm-10\">\n" +
                "                <input onfocus='validationPayment(this.value,this.dataset.id,this.dataset.type)' onkeyup='validationPayment(this.value,this.dataset.id,this.dataset.type)' onchange='validationPayment(this.value,this.dataset.id,this.dataset.type)' data-type='num' data-id='"+count+"' type=\"text\" id=\"amount_"+count+"\" class=\"form-control\" placeholder=\"0000$\">\n" +
                "            </div>\n" +
                "        </div>\n" +
                "        <div class=\"form-group row\">\n" +
                "            <label for=\"email_"+count+"\" class=\"col-sm-2 col-form-label\">Email</label>\n" +
                "            <div class=\"col-sm-10\">\n" +
                "                <input onfocus='validationPayment(this.value,this.dataset.id,this.dataset.type)' onkeyup='validationPayment(this.value,this.dataset.id,this.dataset.type)' onchange='validationPayment(this.value,this.dataset.id,this.dataset.type)' data-type='email' data-id='"+count+"' type=\"email\" id=\"email_"+count+"\"  class=\"form-control\" placeholder=\"member@example.com\">\n" +
                "            </div>\n" +
                "        </div>\n" +
                "    </div>\n" +
                "</div>");
            $("#card"+count).hide();
            $("#card"+count).slideDown(500,function () {
               $(this).css("display","block")
            })
            count++
        }
    })

   $("#amount").on("keyup",function () {
       validationPayment(this.value,this.dataset.id,'num');
   })
    $("#amount").on("change",function () {
        validationPayment(this.value,this.dataset.id,'num');
    })
    $("#amount").on("focus",function () {
        validationPayment(this.value,this.dataset.id,'num');
    })
    $("#deadline").on("keyup",function () {
        validationPayment(this.value,this.dataset.id,'date')
    })
    $("#deadline").on("change",function () {
        validationPayment(this.value,this.dataset.id,'date')
    })
    $("#deadline").on("focus",function () {
        validationPayment(this.value,this.dataset.id,'date')
    })
    $("#send").click(function () {
        validationPayment($("#amount").val(),$("#amount")[0].dataset.id,'num');
        validationPayment($("#deadline").val(), $("#deadline")[0].dataset.id,'date');
        if ($("#append .card").length){
            $("#append .card").map(function (index,item) {
                $(item).find(" input").map(function (i,e) {
                     validationPayment(e.value,e.dataset.id,e.dataset.type)
                })
            })
        }else {
            $("#send").hide(200,function () {
           $(this).css("display","none")
       })
        }
      if (!$(".error").length){
          amount=0;
          emailValid=false;
          if ($("#alert").css("display")==="block"){
              $("#alert").children().hide(200,function () {
                  $(this).parent().css("display","none");
                  $(this).remove();
              })
          }
          $("#append .card").map(function (index,item) {
              if (!emailValid){
                  if ($(item).find(" input[data-type='email']").val() === $("#staticEmail").val()){
                      emailValid = true
                  }
              }
              amount=amount+parseInt($(item).find(" input[data-type='num']").val())
          })
          if (!emailValid && amount <= parseInt($("#amount").val())){
              payers=[];
              $("#append").find(".card").map(function (index,item) {
                       payers.push({
                           user_debt: $(item).find(" input")[0].value,
                           user_email: $(item).find(" input")[1].value,
                           status: 'unpaid',
                       })
              });
              payment="end_date="+ $("#deadline").val()+"&debt="+$("#amount").val()+"&payers="+JSON.stringify(payers)
                  $("#append").hide();
                  $(".loader").show();
                  $.ajax({
                      type: "post",
                      url: "main.php",
                      data: edit?payment+"&action=UpdatePayment"+"&id="+dec+"&added="+added:payment+"&action=addPayment",
                      dataType: "JSON",
                      success: function (data) {
                          if (data.response ==="error"){
                              $("#alert").show(200,function () {
                                  if ($(this).children().length){
                                      $(this).children().remove()
                                  }
                                  $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                                      data.message+
                                      "        <button onclick='closeAlert(this)' type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                                      "            <span aria-hidden=\"true\">&times;</span>\n" +
                                      "        </button>\n" +
                                      "    </div>")
                                  $(this).css("display","block")
                              })
                          }else {
                              if (data.array){
                                  if (data.message && data.message !==$("#append").children().length){
                                      $("#alert").show(200,function () {
                                          if ($(this).children().length){
                                              $(this).children().remove()
                                          }
                                          $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                                              "None of these emails exist, please check again."+
                                              "        <button onclick='closeAlert(this)' type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                                              "            <span aria-hidden=\"true\">&times;</span>\n" +
                                              "        </button>\n" +
                                              "    </div>")
                                          $(this).css("display","block")
                                      })
                                  }
                                  window.location.href = "dashboard.php"
                              }else {
                                  $("#alert").show(200,function () {
                                      if ($(this).children().length){
                                          $(this).children().remove()
                                      }
                                      $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                                          "The data is incorrect, please check."+
                                          "        <button onclick='closeAlert(this)' type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                                          "            <span aria-hidden=\"true\">&times;</span>\n" +
                                          "        </button>\n" +
                                          "    </div>")
                                      $(this).css("display","block")
                                  })
                              }
                          }
                      },
                      complete: function(){
                          $("#append").show();
                          $('.loader').hide();
                      }
                  })

          }else {

              $("#alert").show(200,function () {
                  if ($(this).children().length){
                      $(this).children().remove()
                  }
                  $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                      "Please check the split amount and users email"+
                      "        <button onclick='closeAlert(this)' type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                      "            <span aria-hidden=\"true\">&times;</span>\n" +
                      "        </button>\n" +
                      "    </div>")
                  $(this).css("display","block")
              })
          }
      }else {
          $("#alert").show(200,function () {
              if ($(this).children().length){
                  $(this).children().remove()
              }
              $(this).append(" <div style=\"position: absolute;width: 50%;bottom: 0;left: 1%;z-index: 9999\"  class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">\n" +
                 "Something went wrong please check in is everything correct?"+
                  "        <button onclick='closeAlert(this)' type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">\n" +
                  "            <span aria-hidden=\"true\">&times;</span>\n" +
                  "        </button>\n" +
                  "    </div>")
              $(this).css("display","block")
          })
      }
      if ($("#alert").css("display") ==="block"){
          for (let i=1;i<$(".form-group").length;i++){
             $($(".form-group")[i]).removeClass("success");
             $($(".form-group")[i]).addClass("error")
          }
      }

    })

})