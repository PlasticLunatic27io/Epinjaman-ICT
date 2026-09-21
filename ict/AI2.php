<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Classification with TensorFlow.js</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/mobilenet"></script>
</head>
<body>
    <h2>Image Classification with TensorFlow.js</h2>
    
    <input type="file" accept="image/*" id="imageUpload">
    <br><br>
    <img id="image" width="300">
    <br><br>
    <button onclick="classifyImage()">Classify Image</button>
    <p id="output"></p>

    <script>
        let model;

        async function loadModel() {
            model = await mobilenet.load();  // Load pre-trained MobileNet model
            document.getElementById("output").innerText = "Model loaded. Choose an image!";
        }

        async function classifyImage() {
            const img = document.getElementById("image");
            if (!img.src) {
                alert("Please upload an image first!");
                return;
            }

            const predictions = await model.classify(img);
            document.getElementById("output").innerHTML = 
                `Prediction: ${predictions[0].className} <br> Confidence: ${(predictions[0].probability * 100).toFixed(2)}%`;
        }

        document.getElementById("imageUpload").addEventListener("change", function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("image").src = e.target.result;
            };
            reader.readAsDataURL(file);
        });

        loadModel();  // Load model when page starts
    </script>
</body>
</html>
