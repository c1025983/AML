// app.js
document.querySelectorAll('.bubble').forEach(bubble => {
    bubble.addEventListener('click', function() {
        const bubbleId = this.id;  // Get the id of the clicked bubble

        // Depending on the bubble clicked, update the modal content
        let content = '';

        switch (bubbleId) {
            case 'totalMembers':
                content = 'Total number of members in the library: 1024';
                break;
            case 'newMembers':
                content = 'New members joined this week: 25';
                break;
            case 'booksBorrowed':
                content = 'Total number of books borrowed: 876';
                break;
            case 'booksDue':
                content = 'Number of books currently due: 45';
                break;
            default:
                content = 'No data available.';
                break;
        }

        // Set the modal content
        document.getElementById('modalContent').innerText = content;
    });
});
