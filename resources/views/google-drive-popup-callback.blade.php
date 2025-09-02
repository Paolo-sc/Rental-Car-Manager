<!DOCTYPE html>
<html>
<head>
    <title>Google Drive Autenticazione</title>
</head>
<body>
<script>
    // Passa token alla finestra principale
    window.opener.postMessage({
        googleDriveToken: {!! $token !!}
    }, "*");
    window.close();
</script>
Autenticazione completata, puoi chiudere questa finestra.
</body>
</html>