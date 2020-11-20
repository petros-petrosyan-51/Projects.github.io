$("[data-open='modal1']").click(function () {
    $("#modalText").text("You should pay "+$($(this).parent().parent().parent().children()[0]).text())
    $("#pay_now").attr("data-id",this.dataset.id);
    $("#pay_now").attr("data-type","my");
})
$("[data-open='modal2']").click(function () {
    $("#modalText").text("You should pay "+$($(this).parent().parent().children()[0]).text())
    $("#pay_now").attr("data-id",this.dataset.id);
    $("#pay_now").attr('data-index',this.dataset.index);
    $("#pay_now").attr('data-creator',this.dataset.creator);
    $("#pay_now").attr("data-type","other");
})
$("[data-click='reject']").click(function () {
    $.ajax({
        type: "post",
        url: "main.php",
        data: "id="+this.dataset.id+"&creator="+this.dataset.creator+"&index="+this.dataset.index+"&action=reject",
        dataType: "json",
        success: function(data){
            location.reload();
        }
    })
})
$("#pay_now").click(function () {
  if (this.dataset.id && this.dataset.type ==="my"){
      $.ajax({
          type: "post",
          url: "main.php",
          data: "id="+this.dataset.id+"&action=PayMy",
          dataType: "json",
          success: function(data){
              location.reload();
          }
      })
  }else {
      $.ajax({
          type: "post",
          url: "main.php",
          data: "id="+this.dataset.id+"&creator="+this.dataset.creator+"&index="+this.dataset.index+"&action=PayOther",
          dataType: "json",
          success: function(data){
              location.reload();
          }
      })
  }
})
if ($('.dates #deadline').length){
    $(function() {
        $('.dates #deadline').datepicker({
            'format': 'yyyy-mm-dd',
            'autoclose': true,
            'startDate': new Date()
        });
    });
}
if ($("#myTable1").length){
    $('#myTable1').DataTable();
    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })
}
if ($("#myTable2").length){
    $('#myTable2').DataTable();
}
$("#logout").click(function () {
    $.ajax({
        type: "post",
        url: "main.php",
        data: "action=logout",
        dataType: "json",
        success: function(data){
            if (data){
                location.reload()
            }
        }
    })
})