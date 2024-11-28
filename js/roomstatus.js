function updateSelectOptions() {
    const select = document.getElementById('section-filter');
    const selectedRadio = document.querySelector('input[name="options"]:checked');

    if (!selectedRadio) {
        console.error('No radio button selected.');
        return;
    }

    const selectedCourseId = selectedRadio.value;
    console.log('Selected Course ID:', selectedCourseId); // Debug output

    // Clear existing options except the default one
    select.innerHTML = '<option value="choose">Choose...</option>';

    // Fetch sections based on the selected course ID
    fetchSections(selectedCourseId);
}

function fetchSections(courseId) {
    const url = courseId === 'ALL' 
        ? '../class-room-status/fetch-sections.php?course_id=ALL' 
        : `../class-room-status/fetch-sections.php?course_id=${courseId}`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json(); // Parse JSON response
        })
        .then(sections => {
            const select = document.getElementById('section-filter');
            // Clear existing options
            select.innerHTML = '<option value="choose">Choose...</option>';

            // Populate the select with new options
            sections.forEach(section => {
                const option = document.createElement('option');
                option.value = section.section_name;
                option.textContent = section.section_name;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

