<?php
$page = 'customize';
$cssFilePath = '../assets/styles.css';
$currentCssContent = file_get_contents($cssFilePath);

include 'navbar.php';

// Define regular expressions to extract default values
// Helper function to generate regex pattern
function extract_css($variable)
{
    return "/$variable:\s*([^;]+);/";
}

// Initialize CSS variables to the current values in the styles.css
$cssVariables = [
    'body_font-family' => extract_css('--body_font-family'),
    'wrapper_animation' => extract_css('--wrapper_animation'),
    'wrapper_padding' => extract_css('--wrapper_padding'),
    'wrapper_flex-direction' => extract_css('--wrapper_flex-direction'),
    'h1_font-size' => extract_css('--h1_font-size'),
    'h1_font-weight' => extract_css('--h1_font-weight'),
    'h1_letter-spacing' => extract_css('--h1_letter-spacing'),
    'h1_line-height' => extract_css('--h1_line-height'),
    'h1_text-align' => extract_css('--h1_text-align'),
    'h1_color' => extract_css('--h1_color'),
    'h1_margin-top' => extract_css('--h1_margin-top'),
    'h1_margin-right' => extract_css('--h1_margin-right'),
    'h1_margin-bottom' => extract_css('--h1_margin-bottom'),
    'h1_margin-left' => extract_css('--h1_margin-left'),
    'h2_font-size' => extract_css('--h2_font-size'),
    'h2_font-weight' => extract_css('--h2_font-weight'),
    'h2_text-transform' => extract_css('--h2_text-transform'),
    'h2_color' => extract_css('--h2_color'),
    'h2_text-align' => extract_css('--h2_text-align'),
    'h2_margin' => extract_css('--h2_margin'),
    'h3_font-size' => extract_css('--h3_font-size'),
    'h3_color' => extract_css('--h3_color'),
    'h3_font-weight' => extract_css('--h3_font-weight'),
    'h3_line-height' => extract_css('--h3_line-height'),
    'h3_letter-spacing' => extract_css('--h3_letter-spacing'),
    'h3-margin' => extract_css('--h3-margin'),
    'p_font-size' => extract_css('--p_font-size'),
    'p_font-weight' => extract_css('--p_font-weight'),
    'p_text-align' => extract_css('--p_text-align'),
    'p_color' => extract_css('--p_color'),
    'spacer_height' => extract_css('--spacer_height'),
    'button_padding' => extract_css('--button_padding'),
    'button_font-size' => extract_css('--button_font-size'),
    'button_background-image' => extract_css('--button_background-image'),
    'button_background-color' => extract_css('--button_background-color'),
    'button_border-radius' => extract_css('--button_border-radius'),
    'button_border' => extract_css('--button_border'),
    'button_width' => extract_css('--button_width'),
    'button_color' => extract_css('--button_color'),
    'button_letter-spacing' => extract_css('--button_letter-spacing'),
    'button_margin' => extract_css('--button_margin'),
    'button_hover_color' => extract_css('--button_hover_color'),
    'button_hover_background-image' => extract_css('--button_hover_background-image'),
    'button_hover_transition' => extract_css('--button_hover_transition'),
    'button_hover_transform' => extract_css('--button_hover_transform'),
    'progress_bar_width' => extract_css('--progress_bar_width'),
    'progress_bar_height' => extract_css('--progress_bar_height'),
    'progress_bar_background-color' => extract_css('--progress_bar_background-color'),
    'progress_bar_border-radius' => extract_css('--progress_bar_border-radius'),
    'progress_bar_border-color' => extract_css('--progress_bar_border-color'),
    'progress_bar_border-style' => extract_css('--progress_bar_border-style'),
    'progress_bar_position' => extract_css('--progress_bar_position'),
    'progress_bar-top' => extract_css('--progress_bar-top'),
    'progress_bar-left' => extract_css('--progress_bar-left'),
    'progress_background-color' => extract_css('--progress_background-color'),
    'progress_border-radius' => extract_css('--progress_border-radius'),
    'q_container_background-color' => extract_css('--q_container_background-color'),
    'q_container_border' => extract_css('--q_container_border'),
    'q_container_padding' => extract_css('--q_container_padding'),
    'q_container_border-radius' => extract_css('--q_container_border-radius'),
    'q_container_letter-spacing' => extract_css('--q_container_letter-spacing'),
    'q_container_color' => extract_css('--q_container_color'),
    'next_font-weight' => extract_css('--next_font-weight'),
    'next_background-image' => extract_css('--next_background-image'),
    'next_color' => extract_css('--next_color'),
    'product_container_background_image' => extract_css('--product_container_background_image'),
    'product_container_border-radius' => extract_css('--product_container_border-radius'),
    'product_container_border' => extract_css('--product_container_border'),
    'product_container_margin' => extract_css('--product_container_margin'),
    'product_container_width' => extract_css('--product_container_width'),
    'suggested_image_width' => extract_css('--suggested_image_width'),
    'suggested_image_border-radius' => extract_css('--suggested_image_border-radius'),
    'product_body_p_font-size' => extract_css('--product_body_p_font-size'),
    'product_body_p_color' => extract_css('--product_body_p_color'),
    'product_body_p_font-weight' => extract_css('--product_body_p_font-weight'),
    'result_title_h3_font-weight' => extract_css('--result_title_h3_font-weight'),
    'result_title_h3_font-size' => extract_css('--result_title_h3_font-size'),
    'result_title_h3_color' => extract_css('--result_title_h3_color'),
    'result_title_h3_margin' => extract_css('--result_title_h3_margin'),
    'bg_home' => extract_css('--bg_home'),
    'bg_category' => extract_css('--bg_category'),
    'bg_quiz' => extract_css('--bg_quiz'),
    'bg_result' => extract_css('--bg_result')
    
];


// Extract default values
$current_value = [];
foreach ($cssVariables as $key => $pattern) {
    if (preg_match($pattern, $currentCssContent, $matches)) {
        $current_value[$key] = $matches[1];
    } else {
        // Handle the case when no match is found, maybe provide a default value
        $current_value[$key] = ""; // or any default value you want
    }
}

// Iterate over CSS variables and update their values based on form inputs
foreach ($cssVariables as $key => $formInputName) {
    // Construct the variable name for the form input
    $$formInputName = $_POST[$formInputName] ?? $current_value[$key];
}

// Construct the CSS content based on the form values
$cssContent = ":root {\n";
    foreach ($cssVariables as $key => $pattern) {
        $value = $_POST[$key] ?? $current_value[$key];
        $cssContent .= "    --$key: $value;\n";
    }
    $cssContent .= "}";
    
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
            width: 50%;
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
            /* Adjust as needed */
            overflow: auto;
            /* Enable scrolling if content exceeds container */
        }

        .group-header {
            background-color: #f0f0f0;
            color: #333;
            padding: 10px;
            margin: 6px 0 0 0;
            cursor: pointer;
            border: 1px solid #ccc;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            box-shadow: 0px 5px 10px -5px rgba(0, 0, 0, 0.5);
        }

        .group-header:hover {
            background-color: #e0e0e0;
        }

        .group-content {
            display: none;
            padding: 10px;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 4px 4px;
        }
    </style>
</head>

<body>

    <div class="container-fluid mt-5">
        <div>
            <h1 class="h2 text-center font-weight-bold mb-5">Customization Page</h1>
        </div>
        <!-- Forms column -->
        <div class="row">
            <div class="col-md-3">
                <h2 class="h4">Customization</h2>
                <form id="customize-form" method="post" enctype="multipart/form-data">
    <?php
    // Read the JSON file contents
    $jsonFile = 'config.json';
    $jsonData = file_get_contents($jsonFile);

    // Decode the JSON data into a PHP array
    $configData = json_decode($jsonData, true);

    // Loop through the configuration data
    foreach ($configData as $group) {
        // Output group label and start group container
     
        echo '<div class="group" id="' . $group['id'] . '">';
        echo '<p class="group-header">' . $group['label'] . '</p>';
        echo '<div class="group-content">';

        // Loop through settings within the group
        foreach ($group['settings'] as $setting) {
            echo '<div class="form-group">';
            echo '<label for="' . $setting['id'] . '">' . $setting['label'] . '</label><br>';
            
            // Get the current value for this setting
            $currentSettingValue = '';
            if (isset($_POST[$setting['name']])) {
                // If form is submitted, use submitted value
                $currentSettingValue = $_POST[$setting['name']];
            } elseif (isset($current_value[$setting['name']])) {
                // If form is not submitted, use default value from CSS
                $currentSettingValue = $current_value[$setting['name']];
            }

            // Output different input types based on setting type
            switch ($setting['type']) {
                case 'radio':
                    foreach ($setting['options'] as $option) {
                        echo '<input type="radio" id="' . $setting['id'] . '" name="' . $setting['name'] . '" value="' . $option['value'] . '" ' . ($currentSettingValue == $option['value'] ? 'checked' : '') . '>';
                        echo '<label for="' . $option['value'] . '">' . $option['label'] . '</label>';
                    }
                    break;
                case 'color':
                    echo '<input type="color" id="' . $setting['id'] . '" name="' . $setting['name'] . '" value="' . $currentSettingValue . '">';
                    break;
                case 'text':
                    echo '<input type="text" id="' . $setting['id'] . '" name="' . $setting['name'] . '" value="' . $currentSettingValue . '">';
                    break;
                case 'textarea':
                    echo '<textarea id="' . $setting['id'] . '" name="' . $setting['name'] . '" class="form-control" placeholder="' . htmlspecialchars($currentSettingValue) . '"></textarea>';
                    break;
                case 'file':
                    // Output the file input field
                    echo '<input type="file" id="' . $setting['id'] . '" name="file_'. $setting['name'] . '">';
                    // We need this hidden form to hold the value for the file url which will be used as the value to be assigned for the setting ['name']
                    // Assigning an url directly as a value for the form type above which is a 'file' is not possible so we need to use this hidden form
                    echo '<input type="hidden" id="file_' . $setting['id'] . '" name="' . $setting['name'] . '" value="' . htmlspecialchars($currentSettingValue) . '">'; 
                    break;
                case 'select':
                    echo '<select id="' . $setting['id'] . '" name="' . $setting['name'] . '">';
                    foreach ($setting['options'] as $option) {
                        echo '<option value="' . $option['value'] . '" ' . ($currentSettingValue == $option['value'] ? 'selected' : '') . '>' . $option['label'] . '</option>';
                    }
                    echo '</select>';
                    break;
            }
            echo '</div>'; // Close form-group
        }

        echo '</div>'; // Close group-content
        echo '</div>'; // Close group
    }

    ?>

    <button type="submit" name="apply" class="btn btn-primary mt-3">Apply</button>
</form>

            </div>
            <!-- Preview column -->

            <div class="col-md-9">
                <div id="preview">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="page" id="page" class="custom-select data">
                                <option name="home" value="demo_home.php">Home</option>
                                <option name="category" value="demo_category.php">Category</option>
                                <option name="quiz" value="demo_quiz.php">Quiz</option>
                                <option name="result" value="demo_result.php">Result</option>
                            </select>
                        </div>
                        <div class="col-md-8 d-flex justify-content-end align-items-center">
                            <button type="button" class="responsive-icons" onclick="setIframeSize('mobile')"><i class="fa-solid fa-mobile-screen-button"></i></button>
                            <button type="button" class="responsive-icons" onclick="setIframeSize('tablet')"><i class="fa-solid fa-tablet-screen-button"></i></button>
                            <button type="button" class="responsive-icons" onclick="setIframeSize('desktop')"><i class="fa-solid fa-desktop"></i></button>
                        </div>
                    </div>

                    <div id="iframe-container">
                        <iframe id="preview-iframe" src="./demo_home.php"></iframe>
                    </div>
                </div>  
            </div>
        </div>
    </div>




    <script>
        // Get all group headers
        var groupHeaders = document.querySelectorAll('.group-header');

        // Add click event listener to each group header
        groupHeaders.forEach(function(header) {
            header.addEventListener('click', function() {
                // Toggle visibility of group content
                var content = this.nextElementSibling;
                content.style.display = (content.style.display === 'none' || content.style.display === '') ? 'block' : 'none';
            });
        });
       
        function updateCSSVariable(variableName, value) {
        // Update CSS variables in the preview iframe
        console.log("Updating CSS variable:", variableName);
        console.log("Value:", value);

        var iframeDocument = document.getElementById('preview-iframe').contentDocument;
        iframeDocument.documentElement.style.setProperty(variableName, value);
    }


        // Define a function to handle input changes
        function handleInputChange(event) {
            console.log('Handling');
            // Get the variable name from the input's name attribute
            var variableName = `--${event.target.name}`;
            
            // Update the CSS variable with the input's value in the iframeDocument
            updateCSSVariable(variableName, event.target.value);
        }

        // Apply the input change handler to all forms
        document.addEventListener('DOMContentLoaded', function() {
            var forms = document.querySelectorAll('form');

            forms.forEach(function(form) {
                var inputs = form.querySelectorAll('input, select');
                var textarea = form.querySelectorAll('textarea');

                inputs.forEach(function(input) {
                if (input.type === 'file') {
                    // For file inputs, add change event listener to handle file input change
                    input.addEventListener('change', handleFileUpload);
                } else {
                    // For other input types, add input and change event listeners
                    input.addEventListener('input', handleInputChange);
                    input.addEventListener('change', handleInputChange);
                }
            });

                var textareas = form.querySelectorAll('textarea');

                textareas.forEach(function(textarea) {
                textarea.addEventListener('input', handleTextInputChange);
                textarea.addEventListener('keydown', handleTextInputKeyDown);
            });
                
            });
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
                iframe.style.maxWidth = '480px'; // Adjust maximum width as needed
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
    
        function handleTextInputKeyDown(event) {
        // If the Enter key is pressed with Shift key
        if (event.keyCode === 13 && event.shiftKey) {
            // Prevent default behavior (form submission)
            event.preventDefault();

            // Insert a newline character into the textarea
            var textarea = event.target;
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var text = textarea.value;

            // Insert newline character at the cursor position
            var newText = text.substring(0, start) + '\n' + text.substring(end);
            textarea.value = newText;

            // Move the cursor to the new line
            textarea.selectionStart = textarea.selectionEnd = start + 1;

            // Update the corresponding element in the iframe with a newline character
            var elementId = textarea.name;
            updateIframeElementContent(elementId, newText);
        }
    }


        function handleTextInputChange(event) {
        var inputName = event.target.name;
        var inputValue = event.target.value;
        var elementId = inputName;
 
       
        updateIframeElementContent(elementId, inputValue);
        }
        
        function updateIframeElementContent(elementId, inputValue) {
        // Find the corresponding element in the iframe
        var iframeDocument = document.getElementById('preview-iframe').contentDocument;
        var htmlElement = iframeDocument.getElementById(elementId);

        // Update the innerHTML of the element with the new value
        if (htmlElement) {
            htmlElement.innerHTML = inputValue.replace(/\n/g, "<br>");
        }
    }

    
    function updateMainTextareaValue() {
        // Get the iframe document
        var iframeDocument = document.getElementById('preview-iframe').contentDocument;
        // Get the value of the textarea from the iframe
        var textareaValue = iframeDocument.getElementById("<?php echo $setting['id']; ?>").textContent;
        // Set the value of the textarea in the main document
        document.getElementById("<?php echo $setting['id']; ?>").value = textareaValue;
    }

    // Add event listener for iframe load event
    var iframe = document.getElementById('preview-iframe');
    iframe.addEventListener('load', updateMainTextareaValue);

//     function handleFileInputChange(event) {
//     console.log("HANDLE FILE ");
//     var fileInput = event.target;
//     var file = fileInput.files[0];
//     var filename = fileInput.id + '-filename';
//     var filenameInput = document.getElementById(filename);

//     if (filenameInput && file) {
//         console.log("HANDLE FILE INPUT CHANGE");
//         var uploadedFilename = filenameInput.value;
        

//         // Construct the URL for the uploaded image
//         var url = 'url("./images/' + uploadedFilename + '")';
        

//         // Pass the URL to the function to update the CSS variable
//         updateCSSVariable("--" + fileInput.id, url);
//     }
// }
//     // Add event listener to file input
//     var fileInputs = document.querySelectorAll('input[type="file"]');
//     fileInputs.forEach(function(input) {
//         input.addEventListener('change', handleFileInputChange);
//     });

    function handleFileUpload(event) {
    // Get the file from the input field
    var file = event.target.files[0];

    // Create FormData object to send file via AJAX
    var formData = new FormData();
    formData.append('file', file);

    // Send file to server using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            // File uploaded successfully
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                // File URL
                var fileUrl = response.fileUrl;

                // Pass the file URL to the function to update the CSS variable
                updateCSSVariable("--" + event.target.id, fileUrl);
                document.getElementById("file_"+event.target.id).value = fileUrl;
            } else {
                // Error uploading file
                console.error(response.message);
            }
        } else {
            // Error uploading file
            console.error('Error uploading file.');
        }
    };
    xhr.send(formData);
}


    </script>
</body>
</html>