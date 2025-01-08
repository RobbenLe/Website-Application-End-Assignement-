<?php
// Ensure session is started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Time - Rosa Nails & Spa</title>
    <link rel="stylesheet" href="../../assets/css/chooseTime.css">
</head>
<body>
<div class="choose-time-container">
    <h1>Select Technician and Time</h1>

    <!-- Selected Treatments Summary -->
    <div class="appointment-summary">
        <h3>Selected Treatments</h3>
        <div id="selected-treatments">
            <p>Loading selected treatments...</p>
        </div>
    </div>

    <!-- Technician Dropdown -->
    <div class="technician-selection">
        <label for="technician"><strong>Select Technician:</strong></label>
        <select id="technician" name="technician" onchange="loadSuggestedTimeSlots()">
           <option value="" disabled selected>Select a Technician</option>
           <?php foreach ($technicians as $technician): ?>
              <option value="<?php echo $technician['technician_id']; ?>">
                  <?php echo htmlspecialchars($technician['technician_name'] ?? 'Technician'); ?>
             </option>
           <?php endforeach; ?>
       </select>
    </div>

    <!-- Select Date -->
    <div class="date-selection">
        <label for="selected-date"><strong>Select Date:</strong></label>
        <input type="date" id="selected-date" name="selected_date" min="<?php echo date('Y-m-d'); ?>" onchange="loadSuggestedTimeSlots()">
    </div>

    <!-- Suggested Time Slots Section -->
    <div id="time-slots" class="time-slot-container">
        <p>Select a suggested time slot below:</p>
    </div>

    <!-- Selected Time Display -->
    <p id="selected-time"></p>

    <!-- Hidden Inputs for Appointment Details -->
    <input type="hidden" id="selected-technician-id" name="technician_id">
    <input type="hidden" id="selected-date" name="selected_date">
    <input type="hidden" id="selected-start-time" name="start_time">
    <input type="hidden" id="selected-end-time" name="end_time">
    <input type="hidden" id="selected-service-ids" name="service_ids">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

    <!-- Confirm Appointment Button -->
    <button id="confirm-btn" onclick="confirmAppointment()">Confirm Appointment</button>
</div>

<!-- JavaScript for Displaying Treatments -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const selectedTreatments = JSON.parse(sessionStorage.getItem('selectedTreatments')) || [];
    const summaryDiv = document.getElementById('selected-treatments');

    summaryDiv.innerHTML = ''; // Clear default message

    if (selectedTreatments.length > 0) {
        selectedTreatments.forEach(treatment => {
            const treatmentInfo = document.createElement('p');
            treatmentInfo.textContent = `${treatment.name || 'Unknown Service'} - ${treatment.duration || '00:00:00'} min - €${treatment.price || '0.00'}`;
            summaryDiv.appendChild(treatmentInfo);
        });
    } else {
        summaryDiv.innerHTML = '<p>No treatments selected. Please go back and select treatments.</p>';
    }

    // Extract and store service IDs explicitly
    const serviceIds = selectedTreatments
        .map((treatment) => treatment.id)
        .filter((id) => id !== undefined && id !== null);

    console.log('🛠️ Service IDs:', serviceIds);

    sessionStorage.setItem('serviceIds', JSON.stringify(serviceIds));
});

</script>
<script src="../../assets/js/chooseTime.js"></script>
<script src="../../assets/js/auth.js"></script>
</body>
</html>
