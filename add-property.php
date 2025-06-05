<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Property</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background: url('backs.gif') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
        }

        .form-container {
            max-width: 900px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            margin: 50px auto;
        }

        .footer {
            background-color: #000;
            color: #fff;
            padding: 30px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center text-warning mt-4">🏡 Add Your Property</h2>
        <form id="propertyForm" class="form-container" enctype="multipart/form-data">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Your Name:</label>
                    <input type="text" name="name" class="form-control" placeholder="ex: Ajay Singh" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Number:</label>
                    <input type="text" name="contact" class="form-control" placeholder="Enter your 10-digit Mobile No." required>
                </div>
            </div>
            
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label class="form-label">Property Title:</label>
                    <input type="text" name="title" class="form-control" placeholder="ex: 1BHK Flat, Building Name.." required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location:</label>
                    <input type="text" name="location" class="form-control" placeholder="ex: Kalyan" required>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label class="form-label">Price:</label>
                    <input type="number" name="price" class="form-control" placeholder="ex: ₹5100" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Property Type:</label>
                    <select name="type" class="form-select" required>
                        <option value="">Select Property Type</option>
                        <option value="Residential">Residential</option>
                        <option value="Commercial">Commercial</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label class="form-label">District:</label>
                    <select name="district" class="form-select" id="district" required>
                        <option value="">Select District</option>
                        <option value="Pune">Pune</option>
                        <option value="Thane">Thane</option>
                        <option value="Mumbai">Mumbai</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Village:</label>
                    <select name="village" class="form-select" id="village" >
                        <option value="">Select Village</option>
                    </select>
                </div>
            </div>
            <script>
                document.getElementById("district").addEventListener("change", function() {
                    const villageDropdown = document.getElementById("village");
                    const district = this.value;
                    villageDropdown.innerHTML = '<option value="">Select Village</option>';

                    const villages = {
                        "Pune": ["Hinjewadi", "Baner", "Shivajinagar", "Kothrud", "Wakad", "Hadapsar"],
                        "Thane": ["Kalyan", "Murbad", "Bhiwandi", "Shahapur", "Ulhasnagar", "Dombivli"],
                        "Mumbai": ["Andheri", "Bandra", "Dadar", "Goregaon", "Borivali", "Malad"]
                    };

                    if (villages[district]) {
                        villages[district].forEach(village => {
                            let option = document.createElement("option");
                            option.value = village;
                            option.textContent = village;
                            villageDropdown.appendChild(option);
                        });
                    }
                });
            </script>
            <div class="mt-3">
                <label class="form-label">Description:</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Enter property description" required></textarea>
            </div>

            <div class="mt-3">
                <label class="form-label">Upload Images:</label>
                <input type="file" name="images[]" class="form-control" multiple required>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-warning w-100">Add Property</button>
            </div>
        </form>
    </div>

    <footer class="footer mt-5">
        <p>&copy; 2024 KhojGhar. All Rights Reserved.</p>
    </footer>

    <script>
        document.getElementById("propertyForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formValid = true;
            let contact = document.querySelector("input[name='contact']").value;
            let price = document.querySelector("input[name='price']").value;
            let images = document.querySelector("input[name='images[]']").files;

            if (!/^[0-9]{10}$/.test(contact)) {
                Swal.fire("Invalid Contact Number", "Enter a valid 10-digit mobile number.", "error");
                formValid = false;
            }

            if (price <= 0) {
                Swal.fire("Invalid Price", "Price must be greater than zero.", "error");
                formValid = false;
            }

            if (images.length === 0) {
                Swal.fire("No Images Uploaded", "Please upload at least one image.", "error");
                formValid = false;
            }

            if (formValid) {
                Swal.fire({
                    title: "Success!",
                    text: "Property added successfully!",
                    icon: "success"
                }).then(() => {
                    event.target.submit();
                });
            }
        });
    </script>
</body>
</html>
