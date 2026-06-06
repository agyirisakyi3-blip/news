exports.handler = function(request, response) {
    if (request.method !== 'GET' && request.method !== 'HEAD') {
        return {
            statusCode: 403,
            body: 'Site is in read-only mode.'
        };
    }
    return false;
};
