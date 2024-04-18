<?php
$page = 'customize';
$cssFilePath = '../assets/styles.css';
$currentCssContent = file_get_contents($cssFilePath);

// Define regular expressions to extract default values
// Helper function to generate regex pattern
function extract_css($variable)
{
    return "/$variable:\s*(.*);/";
}

// Initialize CSS variables to the current values in the styles.css
$cssVariables = [
    'button-color' => extract_css('--button-color'),
    'button-width' => extract_css('--button-width'),
    'button-margin-bottom' => extract_css('--button-margin-bottom')
];

// Extract default values
$current_value = [];
foreach ($cssVariables as $key => $pattern) {
    preg_match($pattern, $currentCssContent, $matches);
    $current_value[$key] = $matches[1];
}

// Get the form values, the current_value value is the saved changes
$button_color = $_POST['button-color'] ?? $current_value['button-color'];
$button_width = $_POST['button-width'] ?? $current_value['button-width'];
$button_margin_bottom = $_POST['button-margin-bottom'] ?? $current_value['button-margin-bottom'];

// Construct the CSS content based on the form values
$cssContent = ":root {
    --button-color: $button_color;
    --button-width: $button_width%;
    --button-margin-bottom: $button_margin_bottom;
}";


// Replace the relevant parts with the updated content
$newCssContent = preg_replace('/:root\s*\{[^}]*\}/s', $cssContent, $currentCssContent);

// Write the updated CSS content back to styles.css
file_put_contents($cssFilePath, $newCssContent);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <!-- Bootstrap 4.5.2 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome (Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@2.0.1/dist/css/multi-select-tag.css">
    <title>Customize</title>

    <style>
        #preview {
            /* Ensure proper alignment of elements */
            display: flex;
            flex-direction: column;
        }

        #preview iframe {
            width: 100%;
            height: 100vh;
            /* Fill entire height */
            border: none;
            /* Remove border around iframe */
        }

        #iframe-container {
            /* Center the iframe horizontally and vertically */
            display: flex;
            justify-content: center;
            align-items: center;
            /* Set maximum width and height */
            max-width: 100%;
            max-height: 80vh;
            /* Adjust as needed */
            overflow: auto;
            /* Enable scrolling if content exceeds container */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <!-- Forms column -->
        <div class="row">
            <div class="col-md-3">
                <h2>Customize Settings</h2>
                <form id="customize-form" method="post">
                    <div class="form-group">
                        <label for="button-color">Color:</label>
                        <input type="color" name="button-color" id="button-color" class="form-control" value="<?php echo $button_color; ?>">
                    </div>

                    <div class="form-group">
                        <label>Width:</label><br>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="button-width" value="50" <?php if ($button_width == '50') echo 'checked'; ?> class="form-check-input">
                            <label for="w50" class="form-check-label">50%</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="radio" name="button-width" value="100" <?php if ($button_width == '100') echo 'checked'; ?> class="form-check-input">
                            <label for="w100" class="form-check-label">100%</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="button-margin-bottom">Margin Bottom:</label>
                        <input type="text" name="button-margin-bottom" id="button-margin-bottom" class="form-control" value="<?php echo $button_margin_bottom; ?>"><br><br>
                    </div>

                    <button type="submit" name="apply" class="btn btn-primary">Apply</button>
                </form>
            </div>
            <!-- Preview column -->

            <div class="col-md-9">
                <div id="preview">
                    <div class="row">
                        <div class="col-md-5">
                            <label for="page">Choose Page:</label>
                            <select name="page" id="page" class="form-control mb-3">
                                <option value="../index.php">Home</option>
                                <option value="../category-page.php">Category</option>
                                <option value="../quiz.php">Quiz</option>
                                <option value="../result.php">Result</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <button type="button" class="btn btn-primary mr-2" onclick="setIframeSize('mobile')">Mobile</button>
                            <button type="button" class="btn btn-primary mr-2" onclick="setIframeSize('tablet')">Tablet</button>
                            <button type="button" class="btn btn-primary" onclick="setIframeSize('desktop')">Desktop</button>
                        </div>
                    </div>

                    <div id="iframe-container">
                        <iframe id="preview-iframe" src="../index.php"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script>
        var form = document.getElementById('customize-form');

        form.addEventListener('input', function(event) {
            // Get form values
            var color = document.getElementById('button-color').value;
            var width = document.querySelector('input[name="button-width"]:checked').value;
            var marginBottom = document.getElementById('button-margin-bottom').value;

            // Update CSS variables in the preview iframe
            var iframeDocument = document.getElementById('preview-iframe').contentDocument;
            var styleTag = iframeDocument.createElement('style');
            styleTag.textContent = `
                :root {
                    --button-color: ${color};
                    --button-width: ${width}%;
                    --button-margin-bottom: ${marginBottom};
                }
            `;
            iframeDocument.head.appendChild(styleTag);
        });


        var pageSelect = document.getElementById('page');
        var previewIframe = document.getElementById('preview-iframe');

        // Function to update iframe source based on selected page
        function updateIframeSrc() {
            var selectedPage = pageSelect.value;
            previewIframe.src = selectedPage;
        }

        // Add event listener to the page select for change event
        pageSelect.addEventListener('change', function(event) {
            // Update iframe source
            updateIframeSrc();
        });

        // Add event listener to the preview page select for change event
        previewPageSelect.addEventListener('change', function(event) {
            // Update iframe source
            previewIframe.src = this.value;
        });

        function setIframeSize(size) {
            var iframe = document.getElementById('preview-iframe');
            if (size === 'mobile') {
                iframe.style.width = '50%';
                iframe.style.height = '100vh'; // Adjust height percentage as needed
                iframe.style.maxWidth = '500px'; // Adjust maximum width as needed
                iframe.style.maxHeight = '850px'; // Adjust maximum height as needed
            } else if (size === 'tablet') {
                iframe.style.width = '70%';
                iframe.style.height = '100vh'; // Adjust height percentage as needed
                iframe.style.maxWidth = '800px'; // Adjust maximum width as needed
                iframe.style.maxHeight = '1200px'; // Adjust maximum height as needed
            } else if (size === 'desktop') {
                iframe.style.width = '100%';
                iframe.style.height = '100vh';
                iframe.style.maxWidth = '2300px'; // Adjust maximum width as needed
                iframe.style.maxHeight = '1200px'; // Adjust maximum height as needed
            }
        }
    </script>
</body>

</html>