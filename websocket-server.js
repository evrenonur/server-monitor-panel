const WebSocket = require('ws');
const { Client } = require('ssh2');
const axios = require('axios');

const wss = new WebSocket.Server({ port: 8090 });
const sessions = new Map();

async function validateToken(token) {
    try {
        const response = await axios.get('http://localhost/api/validate-token', {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        return response.data.valid;
    } catch (error) {
        console.error('Token validation error:', error.message);
        return false;
    }
}

wss.on('connection', (ws, req) => {
    console.log('New connection');

    ws.on('message', async (message) => {
        const data = JSON.parse(message);

        switch (data.type) {
            case 'connect':
                const token = req.url.split('=')[1];
                data.token = token;
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

async function handleConnect(ws, data) {
    try {
        const isValid = await validateToken(data.token);
        if (!isValid) {
            ws.send(JSON.stringify({
                type: 'error',
                message: 'Unauthorized: Invalid token'
            }));
            ws.close();
            return;
        }

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

    } catch (error) {
        ws.send(JSON.stringify({
            type: 'error',
            message: 'Connection error: ' + error.message
        }));
        ws.close();
    }
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
