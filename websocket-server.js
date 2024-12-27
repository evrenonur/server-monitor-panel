// Required dependencies
const WebSocket = require('ws');
const { Client } = require('ssh2');
const axios = require('axios');
const dotenv = require('dotenv');

// Load environment variables
dotenv.config();

// Server configuration
const WS_PORT = process.env.WEBSOCKET_PORT || 8090;
const WS_HOST = process.env.WEBSOCKET_HOST || '127.0.0.1';
const API_URL = process.env.APP_URL || 'http://localhost';
const PING_INTERVAL = 30000; // 30 seconds
const CONNECTION_TIMEOUT = 60000; // 60 seconds

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
    let pingTimeout;

    // Set up ping interval
    const pingInterval = setInterval(() => {
        if (ws.readyState === WebSocket.OPEN) {
            ws.ping();
            // Set up timeout for pong response
            pingTimeout = setTimeout(() => {
                console.log('Connection timed out');
                terminateConnection(ws);
            }, CONNECTION_TIMEOUT);
        }
    }, PING_INTERVAL);

    // Handle pong responses
    ws.on('pong', () => {
        clearTimeout(pingTimeout);
    });

    // Handle incoming messages
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
                default:
                    console.warn('Unknown message type:', data.type);
            }
        } catch (error) {
            console.error('Message handling error:', error);
            ws.send(JSON.stringify({
                type: 'error',
                message: 'Invalid message format'
            }));
        }
    });

    // Handle connection close
    ws.on('close', () => {
        console.log('Connection closed');
        clearInterval(pingInterval);
        clearTimeout(pingTimeout);
        terminateConnection(ws);
    });

    // Handle errors
    ws.on('error', (error) => {
        console.error('WebSocket error:', error);
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

        // Create new SSH client
        const client = new Client();

        // Handle successful SSH connection
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

                // Store session
                sessions.set(ws, { client, stream });

                // Handle stream data
                stream.on('data', (data) => {
                    if (ws.readyState === WebSocket.OPEN) {
                        ws.send(JSON.stringify({
                            type: 'output',
                            data: data.toString('utf8')
                        }));
                    }
                });

                // Handle stream close
                stream.on('close', () => {
                    console.log('SSH stream closed');
                    terminateConnection(ws);
                });

                // Handle stream errors
                stream.on('error', (err) => {
                    handleError(ws, 'Stream error: ' + err.message);
                });

                // Notify client of successful connection
                ws.send(JSON.stringify({ type: 'connected' }));
            });
        });

        // Handle SSH client errors
        client.on('error', (err) => {
            handleError(ws, 'SSH connection error: ' + err.message);
        });

        // Connect to SSH server
        client.connect({
            host: data.host,
            port: data.port,
            username: data.username,
            password: data.password,
            readyTimeout: 20000, // 20 seconds timeout for connection
            keepaliveInterval: 10000, // Send keepalive every 10 seconds
        });

    } catch (error) {
        handleError(ws, 'Connection error: ' + error.message);
    }
}

// Handle terminal input
function handleInput(ws, data) {
    const session = sessions.get(ws);
    if (session && session.stream && session.stream.writable) {
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

// Handle errors and send to client
function handleError(ws, message) {
    console.error(message);
    if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
            type: 'error',
            message: message
        }));
    }
    terminateConnection(ws);
}

// Clean up connection
function terminateConnection(ws) {
    const session = sessions.get(ws);
    if (session) {
        if (session.stream) {
            session.stream.end();
            session.stream.destroy();
        }
        if (session.client) {
            session.client.end();
        }
        sessions.delete(ws);
    }
    if (ws.readyState === WebSocket.OPEN) {
        ws.close();
    }
}

// Handle process termination
process.on('SIGTERM', () => {
    console.log('Server shutting down...');
    wss.clients.forEach(terminateConnection);
    process.exit(0);
});

// Start server
console.log(`WebSocket server running on ${WS_HOST}:${WS_PORT}`);
