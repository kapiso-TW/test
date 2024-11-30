const http = require('http');
const { exec } = require('child_process');

const server = http.createServer((req, res) => {
    if (req.url === '/') {
        exec('php index.php', (error, stdout, stderr) => {
            if (error) {
                res.writeHead(500, { 'Content-Type': 'text/plain' });
                res.end(`Error executing PHP: ${stderr}`);
                return;
            }
            res.writeHead(200, { 'Content-Type': 'text/html' });
            res.end(stdout);
        });
    } else {
        res.writeHead(404, { 'Content-Type': 'text/plain' });
        res.end('404 Not Found');
    }
});

server.listen(3000, () => {
    console.log('Server is running at http://localhost:3000');
});
