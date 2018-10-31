<!DOCTYPE html>
<html>
  <head>
    <title>IBM Watson Conversation</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->
    <link rel='stylesheet' href='http://localhost/path/to/html/stylesheets/style.css' />
    <link rel="stylesheet" type="text/css" href="style.css">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">-->
    <script type="text/javascript">
      var context = {};
      function smoothScroll() {
        window.scroll({
        top: 10000, 
        left: 0, 
        behavior: 'smooth' 
        });
      }
      function updateChatLog(user, message) {
        if (message) {
          //var div = document.createElement("div");
          //div.innerHTML = "<b>" + user + "</b>: " + message;
          //document.getElementById("history").appendChild(div);
          //document.getElementById("text").value = "";
          if (user === "Andi") {
            var div = document.createElement("div");
            div.id = "Watson-design"
            div.innerHTML = "<b>" + user + "</b>: " + message;
            document.getElementById("history").appendChild(div);
            document.getElementById("text").value = "";
          } else {
            var div = document.createElement("div");
            div.id = "Nutzer-design"
            div.innerHTML = "<b>" + user + "</b>: " + message;
            document.getElementById("history").appendChild(div);
            document.getElementById("text").value = "";
          }
        }
      }
      function sendMessage() {
        var text = document.getElementById("text").value;
        updateChatLog("Du", text);
        var payload = {};
        if (text) {
          payload.input = {"text": text};
        };
        if (context) {
          payload.context = context;
        };
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4) {
            if (xhr.status == 200) {
            var json = JSON.parse(xhr.responseText);
            context = json.context;
            for (i = 0; i < json.output.text.length; i++) {
              updateChatLog("Andi", json.output.text[i]);
            }
            //updateChatLog("Andi", json.output.text[0]);
            smoothScroll();
            }
          }
        }
        xhr.open("POST", "./assistant", true);
        xhr.setRequestHeader("Content-type", "application/json");
        xhr.send(JSON.stringify(payload));
        
      }
      function init() {
        document.getElementById("text").addEventListener("keydown", function(e) {
          if (!e) {
          var e = window.event;
          }
          if (e.keyCode == 13) {
            sendMessage();
          }
        }, false);
        sendMessage();
      }

      
    </script>
  </head>
<body onLoad="init()">
<div class="container" style="text-align: center">
<div class="row"><div class="col-md-12"><p><h2>STIHL Service-Bot</h2></p></div></div>

<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-6 mt-2">
    <div><b>Verlauf:</b></div>
    <div id="history" class="form-control col text-left" ></div>
  </div>
</div>
<div class="row">
  <div class="col-md-3"></div>
  <div class="input-group col-md-6">
    </span>
  </div>
  </div>
</div>

</body>
<footer>
    <main class="flex-container">
        <section class="flex-item">
          <input type="text" id="text" name="text" class="form" placeholder="Nachricht schreiben ...">
        </section>
        <section class="flex-item">
          <div class="buttons"><button class="btn-hover color-1" onclick="sendMessage()">SEND</button> </div>
        </section>
      </main>
</footer>
</html>