<?php
// Define server address and port
$host = '127.0.0.1';
$port = 8080;

// Create and set up the socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_bind($socket, $host, $port);
socket_listen($socket);

// Server start message
echo "==============================================================================\n";
echo "Start server ..... hostname: $host:$port\n";
echo "Press ctrl + C to quit the task.\n";
echo "If you want to kill the server process, type 'lsof -i tcp:".$port."' and 'kill -9 PID'.\n";
echo "==============================================================================\n";

// Define a shutdown flag
$shutdown = false;

// Signal handling for displaying a message upon termination
pcntl_signal(SIGTSTP, function() {
    echo "Pause server .....\n";    
    $shutdown = true;
});

while (!$shutdown) {
    // Accept client connection
    $client = socket_accept($socket);
    if ($client === false) {
        continue;
    }

    $request = socket_read($client, 1024);

    // Separate headers and body in the request
    list($headers, $body) = explode("\r\n\r\n", $request, 2);

    // Extract the filename from the received request
    $fileNameLine = strstr($headers, "GET /");
    preg_match('/GET \/(.*?) HTTP/', $fileNameLine, $matches);
    $filename = isset($matches[1]) ? $matches[1] : null;

    // Check if the requested file exists
    if (!$filename || !file_exists($filename)) {
        $response = "HTTP/1.1 404 Not Found\r\n";
        $response .= "Content-Type: text/plain\r\n";
        $response .= "Content-Length: 13\r\n";
        $response .= "\r\n";
        $response .= "File Not Found";
        socket_write($client, $response);
        socket_close($client);
        continue;
    }
    
    // Determine the file type and read the latest content
    $fileType = pathinfo($filename, PATHINFO_EXTENSION);
    $contentType = ($fileType === 'json') ? 'application/json' : 'text/html';
    $fileContent = file_get_contents($filename);

    // Create HTTP response
    $response = "HTTP/1.1 200 OK\r\n";
    $response .= "Content-Type: $contentType\r\n";
    $response .= "Content-Length: " . strlen($fileContent) . "\r\n";
    $response .= "\r\n";
    $response .= $fileContent;

    // Print all received headers
    echo "FileName:".$filename."\n";
    echo "ContentType:".$contentType."\n";
    echo "Received Headers:\n";
    foreach (explode("\r\n", $headers) as $header) {
        echo $header . "\n";
    }
    echo "\n";

    // Send response to the client
    socket_write($client, $response);
    socket_close($client);


    // Asynchronously check for signals
    pcntl_signal_dispatch();
}

socket_close($socket);
?>
