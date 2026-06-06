const serverlesswp = require('serverlesswp');

const { validate } = require('../util/install.js');
const { setup } = require('../util/directory.js');
const readOnly = require('../util/readOnly.js');

const pathToWP = '/tmp/wp';

setup();

async function handler(event, context, callback) {
    // Debug endpoint
    if (event.queryStringParameters?._debug === '1' || event.path?.endsWith('/debug')) {
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

    function env(k) { return (process.env[k] || '').trim(); }
    function hex(s) { return [...Buffer.from(s)].map(b => b.toString(16).padStart(2, '0')).join(' '); }

    lines.push('Environment check:');
    lines.push('');
    lines.push('ENV VARS (raw hex):');
    lines.push('  DB_NAME = [' + hex(process.env.DB_NAME || '') + ']');
    lines.push('  DB_USER = [' + hex(process.env.DB_USER || '') + ']');
    lines.push('  DB_PASS = [' + (process.env.DB_PASSWORD ? '***' : 'NOT SET') + ']');
    lines.push('  DB_HOST = [' + hex(process.env.DB_HOST || '') + ']');
    lines.push('');
    lines.push('ENV VARS (trimmed):');
    lines.push('  DB_NAME = [' + env('DB_NAME') + ']');
    lines.push('  DB_USER = [' + env('DB_USER') + ']');
    lines.push('  DB_HOST = [' + env('DB_HOST') + ']');
    lines.push('');

    // Test TCP connection
    const host = env('DB_HOST');
    const parts = host.includes(':') ? host.split(':') : [host, '3306'];
    const h = parts[0];
    const port = parseInt(parts[1]);

    lines.push('Attempting TCP to [' + h + ']:' + port + '...');
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
                sock.destroy();
                reject(err);
            });
            sock.setTimeout(10000, () => {
                lines.push('TCP TIMEOUT after 10s');
                sock.destroy();
                reject(new Error('timeout'));
            });
        });
    } catch (e) {
        // already logged
    }

    return {
        statusCode: 200,
        headers: headers,
        body: lines.join('\n')
    };
}

module.exports = handler;
module.exports.handler = handler;
