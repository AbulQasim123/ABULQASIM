$(document).ready(function () {

    // Function to load all members
    function showAllMembers() {
        $.ajax({
            type: "POST",
            url: "action.php",
            data: { action: "view" },
            success: function (response) {
                $('#memberTree').html(response);
            }
        });
    }
    showAllMembers();


    // Function to populate parent dropdown
    function populateParentDropdown() {
        $.ajax({
            type: "POST",
            url: "action.php",
            data: { action: "getMembers" },
            success: function (response) {
                $('#parent').html(response);
            }
        });
    }
    // Show Add Member Modal
    $('#addMemberBtn').on('click', function () {
        populateParentDropdown();
        $('#addMemberModal').modal('show');
    });

    // Handle form submission
    $('#addMemberForm').on('submit', function (e) {
        e.preventDefault();
        var name = $('#name').val().trim();
        var parent = $('#parent').val();

        // Validate name
        if (!name) {
            $('#name').addClass('is-invalid').next('.invalid-feedback').text("This field is required");
            return;
        } else if (/^\d+$/.test(name)) {
            $('#name').addClass('is-invalid').next('.invalid-feedback').text("Numbers are not allowed");
            return;
        }

        // AJAX request
        $.ajax({
            type: "POST",
            url: "action.php",
            data: { action: "add", name: name, parent: parent },
            success: function (response) {
                $('#addMemberModal').modal('hide');
                showAllMembers();
                alert("Member added successfully");
            }
        });
    });

    $('#name').on('input', function () {
        var name = $(this).val().trim();
        if (name) {
            if (!/^\d+$/.test(name)) {
                $(this).removeClass('is-invalid').next('.invalid-feedback').text("");
            } else {
                $(this).addClass('is-invalid').next('.invalid-feedback').text("Numbers are not allowed");
            }
        } else {
            $(this).addClass('is-invalid').next('.invalid-feedback').text("This field is required");
        }
    });

});

