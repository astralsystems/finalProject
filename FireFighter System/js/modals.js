/*function asd(msg) {
  var box = bootbox.dialog({
    message: "I am a custom dialog<br>test",
    title: "Custom title",
    buttons: {
      success: {
        label: msg,
        className: "btn-success",
        callback: function() {
        }
      },
      danger: {
        label: "Danger!",
        className: "btn-danger",
        callback: function() {
        }
      },
      main: {
        label: "Click ME!",
        className: "btn-primary",
        callback: function() {
        }
      }
    }
  });
  box.css({
    'top': '50%',
    'margin-top': function () {
      return -(box.height() / 2);
    }
  });
}*/

function show_alert(msg) {
  var box = bootbox.dialog({
    //size: "small",
    message: "<h5><p>"+msg+"</h5></p>",
    title: "Error",
    buttons: {
      ok: {
        label: "OK"
      }
    }
  });

  // center modal
  box.css({
    'top': '50%',
    'margin-top': function () {
      return -(box.height() / 2);
    }
  }); 
}

function show_confirm(msg, cb = function(){}) {
  var box = bootbox.dialog({
    //size: "small",
    message: "<h5><p>"+msg+"</h5></p>",
    title: "",
    buttons: {
      ok: {
        label: "Cancel",
      },
      cancel: {
        label: "OK",
        className: "btn-danger",
        callback: cb
      }
    }
  });

  // center modal
  box.css({
    'top': '50%',
    'margin-top': function () {
      return -(box.height() / 2);
    }
  }); 
}

function show_end_call(msg, id) {
  var box = bootbox.dialog({
    //size: "small",
    message: "<h5><p>"+msg+"</h5></p><br>"+
			  "<div class='col-xs-12'>"+
      "<div class='form-group'>"+
       "<label class='control-label' for=injuries>Injuries</label>"+
       "<input type='text' class='form-control' id=injuries name=injuries value=''>"+
      "</div></div>",
    title: "Error",
    buttons: {
      ok: {
        label: "Cancel",
      },
      cancel: {
        label: "OK",
        className: "btn-danger",
        callback: cb = function(){
									window.location="activecallslist.php?action=end&id=" + id + 
									"&injuries="+document.getElementById("injuries").value}
      }
    }
  });

  // center modal
  box.css({
    'top': '50%',
    'margin-top': function () {
      return -(box.height() / 2);
    }
  }); 
}