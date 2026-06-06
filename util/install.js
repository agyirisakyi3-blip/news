exports.validate = function(response) {
  let platform = 'Vercel';

  if (process.env['DB_NAME'] && process.env['DB_USER'] && process.env['DB_PASSWORD'] && process.env['DB_HOST']) {
    return false;
  }

  return {
    statusCode: 200,
    headers: { 'content-type': 'text/html; charset=utf-8' },
    body: `<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ServerlessWP - Needs Database</title></head>
<body>
<p>ServerlessWP is installed! Add DB_NAME, DB_USER, DB_PASSWORD, DB_HOST environment variables in your Vercel project settings and redeploy.</p>
</body></html>`
  };
}
