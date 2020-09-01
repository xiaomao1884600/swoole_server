<!DOCTYPE html>
<html>
    <head>
        <title>Be right back.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
html, body {
    height: 100%;
}

            body {
    margin: 0;
    padding: 0;
    width: 100%;
    color: #B0BEC5;
    display: table;
    font-weight: 100;
                font-family: 'Lato';
            }

            .container {
    text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
    text-align: center;
                display: inline-block;
            }

            .title {
    font-size: 72px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Be right back.</div>
                <button id="btSendAndReceive">向WS服务器发消息并接收消息</button>


                <div id="val"></div>

            </div>
        </div>
    </body>
</html>
<script>
    var wsServer = 'ws://127.0.0.1:9503';
var websocket = new WebSocket(wsServer);
websocket.onopen = function (evt) {
    console.log("Connected to WebSocket server.");
};

websocket.onclose = function (evt) {
    console.log("Disconnected");
};

btSendAndReceive.onclick = function(){
      //向WS服务器发送一个消息
      websocket.send('Hello Server');
      //接收WS服务器返回的消息
      websocket.onmessage = function(e){
        console.log('WS客户端接收到一个服务器的消息：'+ e.data);
{{--        val.innerHTML=e.data;--}}
        val.append("<p> " + e.data + "</p>");

      }


    }


websocket.onmessage = function (evt) {
    console.log('Retrieved data from server: ' + evt.data);
};

websocket.onerror = function (evt, e) {
    console.log('Error occured: ' + evt.data);
};

</script>
