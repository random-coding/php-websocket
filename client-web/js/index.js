// new connection
var socket = new WebSocket('ws://127.0.0.1:8080');

var log = $("#log");
var count = 0;

/**
 * callbakcs
 */

/**
 * 
 * @param {*} event 
 */
socket.onopen = function (event) {
    socket.send('{ "user": "1", "group": "3" }');
};

/**
 * 
 */
socket.onerror = function() {
    log.append('ERROR: Connection to chat server failed <br>');
}

/**
 * 
 * @param {*} event 
 */
socket.onmessage = function (event) {
    log.append('Message from server: ', event.data, '<br>');
    count = count+1;
    console.log(count);
};

/**
 * 
 * @param {*} event 
 */
socket.onclose = function (event) {
    log.append('Message from server: ', event.data, '<br>');
};