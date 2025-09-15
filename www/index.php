<!doctype html>
<html>

<head>
    <base href="/">
    <script src="dmxAppConnect/dmxAppConnect.js"></script>
    <meta charset="UTF-8">
    <title>Untitled Documents</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="bootstrap/5/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Blob.js/2.0.2/Blob.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver@2/dist/FileSaver.min.js"></script>
    <script src="https://app-rsrc.getbee.io/plugin/BeePlugin.js"></script>

</head>

<body is="dmx-app" id="index">

    <script is="dmx-flow" id="flow1" type="text/dmx-flow">{
  runJS: {
    name: "start",
    outputType: "text",
    function: "startBee"
  }
}

</script>


    <dmx-serverconnect id="serverconnect1" url="dmxConnect/api/getaccesstoken.php" dmx-on:success="flow1.run()"></dmx-serverconnect>

    <div id="beefree-sdk-container"></div>

    <div id="controls">
        <label for="choose-template">Template:</label>
        <input id="choose-template" type="file" accept=".json" />

        <!-- <button id="btn-save-template" onclick="bee.saveAsTemplate()" type="button">Guardar Template</button> -->

        <div id="status" class="success">Initializing...</div>
    </div>

    <script>
        // Configuration:
// const EDGE_FUNCTION_URL = 'https://gentle-alfajores-4d8d5f.netlify.app/edge-functions/auth';

// UI Status Management
function updateStatus(message, isError = false) {
  const statusEl = document.getElementById('status');
  statusEl.textContent = message;
  statusEl.style.color = isError ? '#d32f2f' : '#388e3c';
  console.log(`[Status] ${message}`);
}

async function fetchToken() {
  updateStatus("Requesting token...");
  try {

  
    
    const tokenData = {
"access_token": dmx.parse('serverconnect1.data.access_token'),
"v2": true
}


    if (!tokenData.access_token || tokenData.v2 === undefined) {
      throw new Error("Invalid token response structure");
    }
    
    updateStatus("Token received");
    return tokenData;
    
  } catch (error) {
    updateStatus("Token request failed", true);
    console.error("Token Error:", error);
    throw error;
  }
}


  async function initEditor() {
    try {
      updateStatus("Initializing...");
      const token = await fetchToken();

      const beeConfig = {
        container: "beefree-sdk-container",
        autosave: 15,
        language: "en-US",
        onChange: (json) => console.log("Content changed:", json),
        onSave: (json, html) => {
          saveAs(new Blob([html], { type: "text/html" }), "newsletter.html");
        },
        onError: (err) => {
          updateStatus(`Editor error: ${err}`, true);
          console.error("Editor Error:", err);
        }
      };

      return new Promise((resolve) => {
        BeePlugin.create(token, beeConfig, (bee) => {
          window.bee = bee;
          updateStatus("Initialized!");

     
          const fileInput = document.getElementById("choose-template");
          if (fileInput) fileInput.disabled = false;

   
          setupTemplateUpload();

          resolve(bee);


          fetch("https://rsrc.getbee.io/api/templates/m-bee")
            .then(res => res.json())
            .then(template => bee.start(template))
            .catch(err => {
              updateStatus("Template load failed", true);
              console.error("Template Error:", err);
            });
        });
      });
    } catch (error) {
      updateStatus("Initialization failed", true);
      console.error("Init Error:", error);
    }
  }

function setupTemplateUpload() {
  document.getElementById("choose-template")?.addEventListener("change", (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    const reader = new FileReader();
    reader.onload = (event) => {
      try {
        window.bee?.load(JSON.parse(event.target.result));
        updateStatus("Custom template loaded!");
      } catch (err) {
        updateStatus("Invalid template file", true);
        console.error("Template Parse Error:", err);
      }
    };
    reader.readAsText(file);
  });
}

function startBee() {
  return initEditor().then((bee) => {
    if (typeof setupTemplateUpload === 'function') setupTemplateUpload();
    return bee;
  });
}
    </script>


    <script src="bootstrap/5/js/bootstrap.bundle.min.js"></script>
</body>

</html>