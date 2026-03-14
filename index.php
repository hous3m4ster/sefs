<?php

//Created by Hous3M4ster


if(isset($_FILES)){
    //checks if dir files/ exists and if not creates it
    if(!file_exists("files/")){
        mkdir("files");
    }
    //goes through the files array and checks if a file with the same name already exists, 
    //if it does adds new to the filename until it is unique, then copies it from tmp to the files/ directory
    foreach($_FILES as $file){
        $filename = $file["name"];

        while(file_exists("files/" . $filename)){
            $filename = "New" . $filename;
        }

        copy($file["tmp_name"], "files/" . $filename);

    }
}

if (isset($_GET['download'])) {

    //creates safe file location
    $filepath = "files/" . basename(base64_decode($_GET['download']));

    //checks if the file exists and it is in fact a file.
    if (file_exists($filepath) && is_file($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename(base64_decode($_GET['download'])) . '"');
        header('Content-Length: ' . filesize($filepath));
        
        ob_clean();
        flush();
        
        readfile($filepath);
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stupidly Easy File Server</title>
  <style>
    /* --- CSS Variables & Reset --- */
    :root {
      --bg: #0f0f0f;
      --bg-elevated: #1a1a1a;
      --fg: #f5f5f4;
      --muted: #78716c;
      --accent: #f97316;
      --accent-soft: rgba(249, 115, 22, 0.15);
      --border: #2a2a2a;
      --card: #161616;
      --radius: 12px;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      background-color: var(--bg);
      color: var(--fg);
      min-height: 100vh;
      line-height: 1.5;
      position: relative;
    }

    /* Subtle background gradient */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(ellipse 80% 50% at 50% -20%, rgba(249, 115, 22, 0.08), transparent),
        radial-gradient(ellipse 60% 40% at 100% 100%, rgba(249, 115, 22, 0.05), transparent);
      pointer-events: none;
      z-index: -1;
    }

    /* --- Layout --- */
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 0 1.5rem;
    }

    /* --- Header --- */
    header {
      padding: 4rem 0 2rem;
      border-bottom: 1px solid var(--border);
    }

    h1 {
      font-size: 2.5rem;
      font-weight: 700;
      letter-spacing: -0.025em;
      margin-bottom: 0.5rem;
    }

    .subtitle {
      color: var(--muted);
      font-size: 1rem;
    }

    /* --- Upload Section --- */
    .upload-section {
      padding: 3rem 0;
    }

    .upload-zone {
      border: 2px dashed var(--border);
      border-radius: var(--radius);
      padding: 4rem 2rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
      background: var(--card);
      position: relative;
      overflow: hidden;
    }

    .upload-zone:hover {
      border-color: var(--accent);
      background: var(--accent-soft);
    }

    .upload-zone.dragover {
      border-color: var(--accent);
      background: var(--accent-soft);
      transform: scale(1.01);
    }

    .upload-icon {
      width: 48px;
      height: 48px;
      margin: 0 auto 1rem;
      color: var(--muted);
    }

    .upload-text {
      font-size: 1.125rem;
      font-weight: 500;
      margin-bottom: 0.25rem;
    }

    .upload-subtext {
      font-size: 0.875rem;
      color: var(--muted);
    }

    /* --- Table Section --- */
    .files-section {
      padding-bottom: 4rem;
    }

    .section-title {
      font-size: 1.25rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .table-wrapper {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      overflow: hidden;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      text-align: left;
      padding: 1rem 1.25rem;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--muted);
      background: var(--bg-elevated);
      border-bottom: 1px solid var(--border);
    }

    td {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid var(--border);
      font-size: 0.9375rem;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tbody tr {
      transition: background 0.15s ease;
    }

    tbody tr:hover {
      background: var(--accent-soft);
    }

    .file-link {
      color: var(--accent);
      text-decoration: none;
      font-weight: 500;
      font-size: 0.875rem;
    }

    .file-link:hover {
      text-decoration: underline;
    }

    /* --- Accessibility & Motion --- */
    @media (prefers-reduced-motion: reduce) {
      .upload-zone,
      tbody tr {
        transition: none;
      }
    }

    /* Focus states for accessibility */
    *:focus-visible {
      outline: 2px solid var(--accent);
      outline-offset: 2px;
    }

    input[type="file"] {
      display: none;
    }
  </style>
</head>
<body>

  <div class="container">
    <!-- Header -->
    <header>
      <h1>Stupidly Easy File Server</h1>
      <p class="subtitle">Upload and share files in seconds</p>
    </header>

    <!-- Main Content -->
    <main>
      <!-- Upload Section -->
      <section class="upload-section">
        <!-- The form action is set to the current URL or a specific endpoint. 
             Method is POST for file uploads. -->
        <form id="uploadForm" action="/upload" method="POST" enctype="multipart/form-data">
          <div 
            class="upload-zone" 
            id="dropZone"
            role="button"
            tabindex="0"
            aria-label="Click or drag to upload files"
          >
            <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="17 8 12 3 7 8"/>
              <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            <p class="upload-text">Drop your file here</p>
            <p class="upload-subtext">or click to browse</p>
            <input type="file" id="fileInput" name="file">
          </div>
        </form>
      </section>

      <!-- Files Table -->
      <section class="files-section">
        <h2 class="section-title">Recent Files</h2>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>File Name</th>
                <th>Link</th>
              </tr>
            </thead>
            <tbody>
                <?php
                    $files = new FilesystemIterator( getcwd() . '/files/', FilesystemIterator::SKIP_DOTS );

                    foreach($files as $file){
                        ?>
                        <tr>
                            <td><?php print htmlspecialchars($file->getFilename()) ?></td>
                            <td><a href="?download=<?php print base64_encode($file->getFilename()) ?>" class="file-link">Download</a></td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadForm = document.getElementById('uploadForm');

    // Click handling
    dropZone.addEventListener('click', () => fileInput.click());
    
    // Keyboard accessibility
    dropZone.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        fileInput.click();
      }
    });

    // Drag interactions
    dropZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
      dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
      e.preventDefault();
      dropZone.classList.remove('dragover');
      
      if (e.dataTransfer.files.length > 0) {
        // Assign the dropped file to the input
        fileInput.files = e.dataTransfer.files;
        // Immediately submit the form
        uploadForm.submit();
      }
    });

    // Auto-submit on file selection via browse dialog
    fileInput.addEventListener('change', () => {
      if (fileInput.files.length > 0) {
        uploadForm.submit();
      }
    });
  </script>
</body>
</html>
