<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iNotes - Note Taking App</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
        }

        .navbar {
            background-color: #2563eb;
            padding: 1rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .search-bar {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            border: none;
            width: 200px;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: none;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .form-container {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: #1e293b;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4b5563;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-textarea {
            height: 150px;
            resize: vertical;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
        }

        .notes-table {
            width: 100%;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .notes-table th {
            background-color: #f8fafc;
            padding: 1rem;
            text-align: left;
            color: #4b5563;
            font-weight: 600;
        }

        .notes-table td {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 0.5rem;
            transition: opacity 0.2s;
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        .edit-btn {
            background-color: #2563eb;
            color: white;
        }

        .delete-btn {
            background-color: #dc2626;
            color: white;
        }

        .copy-btn {
            background-color: #059669;
            color: white;
        }

        .timestamp {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .table-length {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        select {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }

        .search-filter {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
            width: 200px;
        }

        .pagination {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            background-color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .page-btn:hover {
            background-color: #f8fafc;
        }

        .page-btn.active {
            background-color: #2563eb;
            color: white;
            border-color: #2563eb;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-sticky-note"></i>
            iNotes
        </div>
        <input type="text" class="search-bar" placeholder="Search notes...">
    </nav>

    <div class="container">
        <!-- Alert Messages -->
        <div id="successAlert" class="alert alert-success">
            <i class="fas fa-check-circle"></i> Note has been added successfully!
        </div>
        <div id="editAlert" class="alert alert-warning">
            <i class="fas fa-edit"></i> Note has been updated successfully!
        </div>
        <div id="deleteAlert" class="alert alert-danger">
            <i class="fas fa-trash"></i> Note has been deleted successfully!
        </div>

        <div class="form-container">
            <h2 class="form-title">Add a Note</h2>
            <form id="noteForm">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" id="titleInput" class="form-input" placeholder="Enter note title" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea id="descriptionInput" class="form-textarea" placeholder="Enter note description" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Note
                </button>
            </form>
        </div>

        <div class="table-controls">
            <div class="table-length">
                Show 
                <select id="rowsPerPage">
                    <option value="10">10</option>
                    <option value="25" selected>25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                entries
            </div>
            <input type="text" class="search-filter" placeholder="Filter notes...">
        </div>

        <div class="notes-table">
            <table width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Timestamp</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="notesTableBody">
                    <tr>
                        <td>1</td>
                        <td>first note</td>
                        <td>this is my first note!</td>
                        <td class="timestamp">2025-02-02 11:31:28</td>
                        <td>
                            <button class="action-btn edit-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn copy-btn" title="Copy">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="action-btn delete-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>second</td>
                        <td>this is my second note!</td>
                        <td class="timestamp">2025-02-02 11:32:12</td>
                        <td>
                            <button class="action-btn edit-btn" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn copy-btn" title="Copy">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="action-btn delete-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <button class="page-btn">Previous</button>
            <button class="page-btn active">1</button>
            <button class="page-btn">Next</button>
        </div>
    </div>

    <script>
// Function to show alert
function showAlert(alertId) {
    const alert = document.getElementById(alertId);
    alert.style.display = 'block';
    setTimeout(() => {
        alert.style.display = 'none';
    }, 3000);
}

// Function to load notes
async function loadNotes() {
    try {
        const response = await fetch('api.php');
        const data = await response.json();
        
        if (data.status === 'success') {
            const notes = data.data.notes;
            const tbody = document.getElementById('notesTableBody');
            tbody.innerHTML = '';
            
            notes.forEach(note => {
                tbody.innerHTML += `
                    <tr>
                        <td>${note.id}</td>
                        <td>${note.title}</td>
                        <td>${note.description}</td>
                        <td class="timestamp">${note.tstamp}</td>
                        <td>
                            <button class="action-btn edit-btn" data-id="${note.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn copy-btn" data-id="${note.id}" title="Copy">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="action-btn delete-btn" data-id="${note.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            // Reattach event listeners
            attachEventListeners();
        } else {
            showAlert('error', data.message);
        }
    } catch (error) {
        console.error('Error loading notes:', error);
        showAlert('deleteAlert', 'Failed to load notes');
    }
}

// Function to add a note
async function addNote(title, description) {
    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title, description })
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            showAlert('successAlert');
            loadNotes();  // Reload the notes table
            return true;
        } else {
            showAlert('deleteAlert', data.message);
            return false;
        }
    } catch (error) {
        console.error('Error adding note:', error);
        showAlert('deleteAlert', 'Failed to add note');
        return false;
    }
}

// Function to update a note
async function updateNote(id, title, description) {
    try {
        const response = await fetch(`api.php?id=${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title, description })
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            showAlert('editAlert');
            loadNotes();  // Reload the notes table
            return true;
        } else {
            showAlert('deleteAlert', data.message);
            return false;
        }
    } catch (error) {
        console.error('Error updating note:', error);
        showAlert('deleteAlert', 'Failed to update note');
        return false;
    }
}

// Function to delete a note
async function deleteNote(id) {
    try {
        const response = await fetch(`api.php?id=${id}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            showAlert('deleteAlert');
            loadNotes();  // Reload the notes table
            return true;
        } else {
            showAlert('deleteAlert', data.message);
            return false;
        }
    } catch (error) {
        console.error('Error deleting note:', error);
        showAlert('deleteAlert', 'Failed to delete note');
        return false;
    }
}

// Load notes when page loads
document.addEventListener('DOMContentLoaded', loadNotes);

// Form submission handler
document.getElementById('noteForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const title = document.getElementById('titleInput').value;
    const description = document.getElementById('descriptionInput').value;
    const editId = this.dataset.editId;
    
    let success;
    if (editId) {
        success = await updateNote(editId, title, description);
        if (success) {
            this.dataset.editId = '';
            document.querySelector('#noteForm button[type="submit"]').innerHTML = '<i class="fas fa-plus"></i> Add Note';
        }
    } else {
        success = await addNote(title, description);
    }
    
    if (success) {
        this.reset();
    }
});

// Function to attach event listeners to dynamic elements
function attachEventListeners() {
    // Edit button handler
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const id = this.dataset.id;
            const title = row.cells[1].textContent;
            const description = row.cells[2].textContent;
            
            document.getElementById('titleInput').value = title;
            document.getElementById('descriptionInput').value = description;
            document.getElementById('noteForm').dataset.editId = id;
            
            // Change form submit button text
            const submitButton = document.querySelector('#noteForm button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-save"></i> Update Note';
        });
    });
    
    // Delete button handler
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', async function() {
            if (confirm('Are you sure you want to delete this note?')) {
                const id = this.dataset.id;
                await deleteNote(id);
            }
        });
    });
    
    // Copy button handler
    document.querySelectorAll('.copy-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('tr');
            const title = row.cells[1].textContent;
            const description = row.cells[2].textContent;
            
            const text = `Title: ${title}\nDescription: ${description}`;
            navigator.clipboard.writeText(text);
            alert('Note copied to clipboard!');
        });
    });
}

// Search functionality
document.querySelector('.search-filter').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#notesTableBody tr');
    
    rows.forEach(row => {
        const title = row.cells[1].textContent.toLowerCase();
        const description = row.cells[2].textContent.toLowerCase();
        const matches = title.includes(searchText) || description.includes(searchText);
        row.style.display = matches ? '' : 'none';
    });
});
    </script>
</body>
</html>

