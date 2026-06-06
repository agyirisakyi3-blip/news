exports.validate = function(response) {
  let platform = 'Vercel';

  if (process.env['DATABASE'] && process.env['USERNAME'] && process.env['PASSWORD'] && process.env['HOST']) {
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
<p>ServerlessWP is installed! Add DATABASE, USERNAME, PASSWORD, HOST environment variables in your Vercel project settings and redeploy.</p>
</body></html>`
  };
}
