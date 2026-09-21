<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TensorFlow.js Example</title>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
</head>
<body>
    <h2>TensorFlow.js Example: Predicting Values</h2>
    <button onclick="trainModel()">Train Model</button>
    <p id="output"></p>

    <script>
        async function trainModel() {
            // 1. Define a Simple Model (y = mx + b)
            const model = tf.sequential();
            model.add(tf.layers.dense({units: 1, inputShape: [1]}));

            // 2. Compile the Model
            model.compile({
                optimizer: 'sgd',
                loss: 'meanSquaredError'
            });

            // 3. Define Training Data (x and y values)
            const xs = tf.tensor2d([1, 2, 3, 4, 5], [5, 1]); // Inputs
            const ys = tf.tensor2d([2, 4, 6, 8, 10], [5, 1]); // Outputs (y = 2x)

            // 4. Train the Model
            await model.fit(xs, ys, {epochs: 100});

            // 5. Make a Prediction
            const prediction = model.predict(tf.tensor2d([6], [1, 1]));
            prediction.data().then(data => {
                document.getElementById("output").innerText = "Prediction for x=6: " + data[0];
            });
        }
    </script>
</body>
</html>
