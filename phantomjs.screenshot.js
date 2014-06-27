/*!
 * Blockify's Screenshot Script (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/blockify/blockify/blob/master/LICENSE)
 */

var webpage = require('webpage');
var system = require('system');
var args = system.args;
var apiUrl = (args.length > 1) ? args[1] : 'http://192.168.0.11/blockify/header.php';
var data;
var i;

function renderBlockStep() {
  var basename = data[i].basename;
  var page = webpage.create();

  page.viewportSize = {
    width: 1024,
    height: 1
  }

  page.open( apiUrl + '?api/render=' + basename, function() {
    console.log( 'Generating screenshot for: ' + basename );
    page.render( 'blocks/' + basename + '/screenshot.png' );

    i++; ( i < data.length ) ? renderBlockStep() : phantom.exit();
  });
}

var list = webpage.create();
list.open(apiUrl + '?api/list', function() {
  data = JSON.parse(list.plainText);
  i = 0;
  renderBlockStep();
});
