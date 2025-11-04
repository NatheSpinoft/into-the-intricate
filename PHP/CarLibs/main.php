<!DOCTYPE html>
<!--main.php-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dialogue Box Example</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>

<dialog id="dialogBox" open>
    <fieldset>
        <legend>Choose an Option</legend>
        <label for="carSelect">Choose a car:</label>
        <select id="carSelect">
            <option value="VW">VW</option>
            <option value="honda">Honda</option>
            <option value="bmw">BMW</option>
        </select>

        <button onclick="getCarInfo()">getCarInfo</button>
        <button onclick="showCreateForm()">showCreateForm</button>
        <button onclick="getSpecs()">getSpecs</button>
        <button onclick="getFeatures()">getFeatures</button>

        <button onclick="updateDisplay()">updateDisplay</button>
        
        <div id="createForm" style="display:none; margin-top: 10px; padding: 10px; background: #e8e8e8; border-radius: 5px;">
            <strong>Create New Car</strong><br>
            <label>Model: <input type="text" id="model" placeholder="R Series" style="margin: 5px;"></label><br>
            <label>Gas Limit: <input type="number" id="gasLimit" placeholder="60" style="margin: 5px;"></label><br>
            <label>Km Limit: <input type="number" id="kmLimit" placeholder="100000" style="margin: 5px;"></label><br>
            <label>Features: <input type="text" id="features" placeholder="Sport Package" style="margin: 5px;"></label><br>
            <button onclick="createCar()">Submit</button>
            <button onclick="hideCreateForm()">Cancel</button>
        </div>
        
        <div id="display">Click a button to see the result here.</div>
    </fieldset>
</dialog>

<script>
    function updateDisplay(text) {
        document.getElementById('display').innerHTML = text;
    }
    
    function showCreateForm() {
        document.getElementById('createForm').style.display = 'block';
        updateDisplay('Fill out the form above to create a new car.');
    }
    
    function hideCreateForm() {
        document.getElementById('createForm').style.display = 'none';
    }
    
    async function getCarInfo() {
        const make = document.getElementById('carSelect').value;
        
        try {
            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=getCarInfo&make=' + make
            });
            
            const result = await response.json();
            
            if (result.success) {
                const car = result.data;
                updateDisplay(`
                    <strong>Car Information</strong><br>
                    Make: ${car.make}<br>
                    Model: ${car.model}<br>
                    Gas Tank: ${car.gasLimit}L<br>
                    Km Limit: ${car.kmLimit.toLocaleString()} km<br>
                    Features: ${car.features}
                `);
            } else {
                updateDisplay('Error: ' + result.message);
            }
        } catch (error) {
            updateDisplay('Error fetching car info: ' + error.message);
        }
    }
    
    async function createCar() {
        const make = document.getElementById('carSelect').value;
        const model = document.getElementById('model').value;
        const gasLimit = document.getElementById('gasLimit').value;
        const kmLimit = document.getElementById('kmLimit').value;
        const features = document.getElementById('features').value;
        
        try {
            const formData = new URLSearchParams();
            formData.append('action', 'createCar');
            formData.append('make', make);
            formData.append('model', model);
            formData.append('gasLimit', gasLimit);
            formData.append('kmLimit', kmLimit);
            formData.append('features', features);
            
            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                const car = result.data;
                updateDisplay(`
                    <strong>Car Created Successfully!</strong><br>
                    Make: ${car.make}<br>
                    Model: ${car.model}<br>
                    Gas Tank: ${car.gasLimit}L<br>
                    Km Limit: ${car.kmLimit.toLocaleString()} km<br>
                    Features: ${car.features}
                `);
                hideCreateForm();
                document.getElementById('model').value = '';
                document.getElementById('gasLimit').value = '';
                document.getElementById('kmLimit').value = '';
                document.getElementById('features').value = '';
            } else {
                updateDisplay('Error: ' + result.message);
            }
        } catch (error) {
            updateDisplay('Error creating car: ' + error.message);
        }
    }
    
    function getSpecs() {
        const make = document.getElementById('carSelect').value;
        updateDisplay(`
            <strong>Specifications for ${make}</strong><br>
            Engine: Turbocharged<br>
            Horsepower: 280 HP<br>
            Transmission: 7-speed DSG<br>
            Drive: AWD
        `);
    }
    
    function getFeatures() {
        const make = document.getElementById('carSelect').value;
        updateDisplay(`
            <strong>Features for ${make}</strong><br>
            - Sport Package<br>
            - Navigation System<br>
            - Leather Seats<br>
            - Panoramic Sunroof<br>
            - Adaptive Cruise Control
        `);
    }
</script>

</body>
</html>