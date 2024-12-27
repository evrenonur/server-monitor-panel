const WebSocket = require('ws');
const { Client } = require('ssh2');

const wss = new WebSocket.Server({ port: 8090 });
const sessions = new Map();

wss.on('connection', (ws, req) => {

    const token = req.url.split('=')[1];
    console.log('New connection');

    ws.on('message', async (message) => {
        const data = JSON.parse(message);

        switch (data.type) {
            case 'connect':
                handleConnect(ws, data);
                break;
            case 'input':
                handleInput(ws, data);
                break;
            case 'resize':
                handleResize(ws, data);
                break;
        }
    });

    ws.on('close', () => {
        const session = sessions.get(ws);
        if (session) {
            session.stream.close();
            session.client.end();
            sessions.delete(ws);
        }
    });
});

function handleConnect(ws, data) {
    const client = new Client();

    client.on('ready', () => {
        client.shell({
            term: 'xterm-256color',
            cols: data.cols || 80,
            rows: data.rows || 24
        }, (err, stream) => {
            if (err) {
                ws.send(JSON.stringify({
                    type: 'error',
                    message: 'Shell açılamadı!'
                }));
                return;
            }

            sessions.set(ws, { client, stream });

            stream.on('data', (data) => {
                ws.send(JSON.stringify({
                    type: 'output',
                    data: data.toString('utf8')
                }));
            });

            stream.on('close', () => {
                ws.close();
            });

            ws.send(JSON.stringify({ type: 'connected' }));
        });
    });

    client.on('error', (err) => {
        ws.send(JSON.stringify({
            type: 'error',
            message: 'Bağlantı hatası: ' + err.message
        }));
    });

    client.connect({
        host: data.host,
        port: data.port,
        username: data.username,
        password: data.password
    });
}

function handleInput(ws, data) {
    const session = sessions.get(ws);
    if (session && session.stream) {
        session.stream.write(data.input);
    }
}

function handleResize(ws, data) {
    const session = sessions.get(ws);
    if (session && session.stream) {
        session.stream.setWindow(data.rows, data.cols);
    }
}

console.log('WebSocket server running on port 8090');
