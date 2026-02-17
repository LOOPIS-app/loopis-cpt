flatpickr(".loopis-datetime", {
enableTime: true,
dateFormat: "Y-m-d H:i:ss", // Standardizes the output format
time_24hr: true, // Fixes the AM/PM confusion
allowInput: true, // Allows manual typing if they really want to
altInput: true, // Hides the "ugly" input and shows a pretty one
altFormat: "Y-m-d H:i:ss", // How the user sees it 
});