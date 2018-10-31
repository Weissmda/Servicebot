var config = require('../config');
var express = require('express');
var bodyParser = require('body-parser');
var AssistantV1 = require('watson-developer-cloud/assistant/v1');
var fetch = require('node-fetch');
var geocoder = require('geocoder');
var XMLHttpRequest = require("xmlhttprequest").XMLHttpRequest;

const googleMapsClient = require('@google/maps').createClient({
    key: 'AIzaSyAnkrtCkuL_6MB8cECenqf4eJoPiKoh7IU',
    Promise: Promise
  });

var router = express.Router();
var jsonParser = bodyParser.json();

var watsonAssistant = new AssistantV1(config.watson.assistant);

function deUmlaut(value){
    value = value.toLowerCase();
    value = value.replace(/ä/g, 'ae');
    value = value.replace(/ö/g, 'oe');
    value = value.replace(/ü/g, 'ue');
    value = value.replace(/ß/g, 'ss');
    value = value.replace(/ /g, '-');
    value = value.replace(/\./g, '');
    value = value.replace(/,/g, '');
    value = value.replace(/\(/g, '');
    value = value.replace(/\)/g, '');
    return value;
  }

  var getJSON = function(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
      var status = xhr.status;
      if (status === 200) {
        callback(null, xhr.response);
      } else {
        callback(status, xhr.response);
      }
    };
    xhr.send();
};

router.post('/', jsonParser, function(req, res, next) {
    watsonAssistant.message({
        'input': req.body.input,
        'context': req.body.context,
        'workspace_id': config.watson.assistant.workspace_id
    },
    function(err, response) {
        if (err) {
            console.log('error:', err);
        } else {
            console.log("Detected input: " + response.input.text);
            if (response.intents.length > 0) {
                var intent = response.intents[0];
                console.log("Detected intent: " + intent.intent);
                console.log("Confidence: " + intent.confidence);
            }
            if (response.entities.length > 0) {
                var entity = response.entities[0];
                console.log("Detected entity: " + entity.entity);
                console.log("Value: " + entity.value);
            }    

        }
            //console.log(JSON.stringify(jsonObject, null, 2));
            res.json(response);
        }
    );
});

module.exports = router;
