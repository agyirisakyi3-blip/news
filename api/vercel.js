const serverlesswp = require('serverlesswp');

const { validate } = require('../util/install.js');
const { setup } = require('../util/directory.js');
const readOnly = require('../util/readOnly.js');

const pathToWP = '/tmp/wp';

setup();

async function handler(event, context, callback) {
    let response = await serverlesswp({docRoot: pathToWP, event: event});

    let checkInstall = validate(response);

    if (checkInstall) {
        return checkInstall;
    } else {
        return response;
    }
}

module.exports = handler;
module.exports.handler = handler;
