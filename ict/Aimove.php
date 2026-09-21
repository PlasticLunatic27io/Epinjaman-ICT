<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Body Movement Tracking with TensorFlow.js</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/posenet"></script>
    <style>
        video, canvas {
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>
</head>
<body>
    <h2>Real-Time Body Movement Tracking</h2>
    <video id="video" width="640" height="480" autoplay></video>
    <canvas id="canvas"></canvas>
    <p id="movementOutput">Detecting body movement...</p>

    <script>
        async function setupCamera() {
            const video = document.getElementById("video");
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            video.srcObject = stream;
            return new Promise((resolve) => {
                video.onloadedmetadata = () => resolve(video);
            });
        }

        async function detectPose() {
            const video = await setupCamera();
            const canvas = document.getElementById("canvas");
            const ctx = canvas.getContext("2d");
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const net = await posenet.load();
            async function estimate() {
                const pose = await net.estimateSinglePose(video, { flipHorizontal: false });
                
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Draw keypoints
                pose.keypoints.forEach(point => {
                    if (point.score > 0.5) {
                        ctx.beginPath();
                        ctx.arc(point.position.x, point.position.y, 5, 0, 2 * Math.PI);
                        ctx.fillStyle = "red";
                        ctx.fill();
                    }
                });

                document.getElementById("movementOutput").innerText = "Body movement detected!";
                requestAnimationFrame(estimate);
            }
            estimate();
        }

        detectPose();
    </script>
</body>
</html>
