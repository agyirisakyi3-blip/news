const serverlesswp = require('serverlesswp');

const { validate } = require('../util/install.js');
const { setup } = require('../util/directory.js');
const readOnly = require('../util/readOnly.js');

const pathToWP = '/tmp/wp';

setup();

async function handler(event, context, callback) {
    // Debug endpoint
    if (event.path === '/api/debug' || event.queryStringParameters?._debug === '1') {
        return await handleDebug(event);
    }

    let response = await serverlesswp({docRoot: pathToWP, event: event});

    let checkInstall = validate(response);

    if (checkInstall) {
        return checkInstall;
    } else {
        return response;
    }
}

async function handleDebug(event) {
    const headers = { 'content-type': 'text/plain; charset=utf-8' };
    let lines = [];

    lines.push('Environment check:');
    lines.push('');
    lines.push('getenv():');
    lines.push('  DB_NAME = ' + (process.env.DB_NAME || 'NOT SET'));
    lines.push('  DB_USER = ' + (process.env.DB_USER || 'NOT SET'));
    lines.push('  DB_PASS = ' + (process.env.DB_PASSWORD ? '***SET***' : 'NOT SET'));
    lines.push('  DB_HOST = ' + (process.env.DB_HOST || 'NOT SET'));
    lines.push('');
    lines.push('Event path: ' + event.path);
    lines.push('HTTP method: ' + event.httpMethod);
    lines.push('Query: ' + JSON.stringify(event.queryStringParameters || {}));
    lines.push('');

    // Test TCP connection
    const host = process.env.DB_HOST || '';
    const [h, p] = host.includes(':') ? host.split(':') : [host, '3306'];
    const port = parseInt(p);

    lines.push('Attempting TCP connection to ' + h + ':' + port + '...');
    const net = require('net');
    try {
        await new Promise((resolve, reject) => {
            const sock = net.createConnection(port, h, () => {
                lines.push('TCP connection: OK');
                sock.end();
                resolve();
            });
            sock.on('error', (err) => {
                lines.push('TCP FAILED: ' + err.message);
                reject(err);
            });
            sock.setTimeout(10000, () => {
                lines.push('TCP TIMEOUT after 10s');
                sock.destroy();
                reject(new Error('timeout'));
            });
        });
    } catch (e) {
        lines.push('TCP connection failed');
    }

    return {
        statusCode: 200,
        headers: headers,
        body: lines.join('\n')
    };
}

module.exports = handler;
module.exports.handler = handler;
