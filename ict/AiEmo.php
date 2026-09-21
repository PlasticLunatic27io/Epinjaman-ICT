<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Emotion Detection</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/face-api.js"></script>
</head>
<body>
    <h2>Real-Time Emotion Detection</h2>
    <video id="video" width="500" height="400" autoplay></video>
    <canvas id="canvas"></canvas>
    <p id="emotionOutput">Detecting emotion...</p>

    <script>
        // Load the webcam
        async function setupCamera() {
            const video = document.getElementById("video");
            const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
            video.srcObject = stream;
        }

        // Load the Face API models
        async function loadModels() {
            await faceapi.nets.tinyFaceDetector.loadFromUri("https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js/models");
            await faceapi.nets.faceExpressionNet.loadFromUri("https://cdn.jsdelivr.net/gh/justadudewhohacks/face-api.js/models");
        }

        async function detectEmotion() {
            const video = document.getElementById("video");
            const canvas = document.getElementById("canvas");
            const displaySize = { width: video.width, height: video.height };
            faceapi.matchDimensions(canvas, displaySize);

            setInterval(async () => {
                const detections = await faceapi.detectAllFaces(video, new faceapi.TinyFaceDetectorOptions()).withFaceExpressions();
                const resizedDetections = faceapi.resizeResults(detections, displaySize);

                canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);
                faceapi.draw.drawDetections(canvas, resizedDetections);
                faceapi.draw.drawFaceExpressions(canvas, resizedDetections);

                if (detections.length > 0) {
                    const emotions = detections[0].expressions;
                    const highestEmotion = Object.keys(emotions).reduce((a, b) => emotions[a] > emotions[b] ? a : b);
                    document.getElementById("emotionOutput").innerText = `Detected Emotion: ${highestEmotion}`;
                }
            }, 500);
        }

        // Run everything
        async function startEmotionDetection() {
            await setupCamera();
            await loadModels();
            detectEmotion();
        }

        startEmotionDetection();
    </script>
</body>
</html>
