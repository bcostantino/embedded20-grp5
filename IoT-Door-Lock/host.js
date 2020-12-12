// import server and fs libraries
let http = require('http');
let fs = require('fs');

// define server hostname and port
const hostname = '127.0.0.1';
const port = 3000;

// create server object
const handleRequest = (request, response) => {
	response.writeHead(200, {
		'Content-Type' : 'text/html'
	});
	fs.readFile('./index.html', null, function(error, data) {
		if(error) {
			response.writeHead(404);
			response.write('Whoops! File not found!');
		} else {
			response.write(data);
		}
		response.end();
	});
};

http.createServer(handleRequest).listen(8000);