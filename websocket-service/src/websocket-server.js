const WebSocket = require('ws');
const { Client } = require('ssh2');
const axios = require('axios');
const dotenv = require('dotenv');

// Load environment variables
dotenv.config();

// Server configuration
const WS_PORT = process.env.WEBSOCKET_PORT || 8090;
const WS_HOST = '0.0.0.0';
const API_URL = process.env.APP_URL || 'http://localhost';

// Store active sessions
const sessions = new Map();

// Validate authentication token
async function validateToken(token) {
    try {
        const response = await axios.get(`${API_URL}/api/validate-token`, {
            headers: { 'Authorization': `Bearer ${token}` }
        });
        return response.data.valid;
    } catch (error) {
        console.error('Token validation error:', error.message);
        return false;
    }
}

// Create WebSocket server
const wss = new WebSocket.Server({
    port: WS_PORT,
    host: WS_HOST
});

// Handle new WebSocket connections
wss.on('connection', async (ws, req) => {
    console.log('New connection established');

    ws.on('message', async (message) => {
        try {
            const data = JSON.parse(message);

            switch (data.type) {
                case 'connect':
                    const token = req.url.split('=')[1];
                    data.token = token;
                    await handleConnect(ws, data);
                    break;
                case 'input':
                    handleInput(ws, data);
                    break;
                case 'resize':
                    handleResize(ws, data);
                    break;
            }
        } catch (error) {
            console.error('Message handling error:', error);
            ws.send(JSON.stringify({
                type: 'error',
                message: 'Invalid message format'
            }));
        }
    });

    ws.on('close', () => {
        console.log('Connection closed');
        terminateConnection(ws);
    });
});

// Handle SSH connection
async function handleConnect(ws, data) {
    try {
        // Validate token
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
                    handleError(ws, 'Failed to open shell: ' + err.message);
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
                    console.log('SSH stream closed');
                    terminateConnection(ws);
                });

                ws.send(JSON.stringify({ type: 'connected' }));
            });
        });

        client.on('error', (err) => {
            handleError(ws, 'SSH connection error: ' + err.message);
        });

        client.connect({
            host: data.host,
            port: data.port,
            username: data.username,
            password: data.password
        });

    } catch (error) {
        handleError(ws, 'Connection error: ' + error.message);
    }
}

// Handle terminal input
function handleInput(ws, data) {
    const session = sessions.get(ws);
    if (session && session.stream) {
        session.stream.write(data.input);
    }
}

// Handle terminal resize
function handleResize(ws, data) {
    const session = sessions.get(ws);
    if (session && session.stream) {
        session.stream.setWindow(data.rows, data.cols);
    }
}

// Handle errors
function handleError(ws, message) {
    console.error(message);
    ws.send(JSON.stringify({
        type: 'error',
        message: message
    }));
    terminateConnection(ws);
}

// Clean up connection
function terminateConnection(ws) {
    const session = sessions.get(ws);
    if (session) {
        if (session.stream) session.stream.end();
        if (session.client) session.client.end();
        sessions.delete(ws);
    }
    ws.close();
}

console.log(`WebSocket server running on ${WS_HOST}:${WS_PORT}`);
