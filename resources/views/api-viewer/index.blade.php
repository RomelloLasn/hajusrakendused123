@extends('layouts.app')

@section('title', 'Subjects API Viewer')

@section('styles')
<style>
    #api-url {
        background-color: var(--secondary-bg);
        color: var(--text-color);
        border: 1px solid var(--border-color);
    }
    #api-url:focus {
        background-color: var(--secondary-bg);
        color: var(--text-color);
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(0, 200, 227, 0.25);
    }
    #response-container {
        background-color: var(--secondary-bg);
        border: 1px solid var(--border-color);
        border-radius: 5px;
        padding: 1rem;
        margin-top: 1rem;
        max-height: 60vh; 
        overflow-y: auto;
        white-space: pre-wrap; 
        word-wrap: break-word;
        font-family: monospace;
        color: #ccc;
    }
    .loading-text {
        color: var(--primary-color);
        font-size: 0.9rem;
        font-style: italic;
    }
    .error-message {
        color: #dc3545; 
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-center">Subjects API Viewer</h1>
    
    <div class="text-center mb-4">
        <p class="lead">Viewing data from: <code>https://tak22reiljan.itmajakas.ee/api/subjects</code></p>
        <button class="btn btn-cyan" type="button" id="fetch-api-btn">
            Fetch Subjects Data
        </button>
    </div>

    <div id="loading-indicator" class="text-center mt-2 d-none">
        <span class="loading-text">Fetching data...</span>
    </div>

    <div id="response-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 d-none"></div>
    <div id="error-container" class="mt-2 text-center error-message"></div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-fetch on page load
        fetchSubjectsData();
    });
    
    document.getElementById('fetch-api-btn').addEventListener('click', function() {
        fetchSubjectsData();
    });
    
    function fetchSubjectsData() {
        const responseContainer = document.getElementById('response-container');
        const errorContainer = document.getElementById('error-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const fetchButton = document.getElementById('fetch-api-btn');
        
        const apiUrl = 'https://tak22reiljan.itmajakas.ee/api/subjects';
        
        responseContainer.classList.add('d-none');
        responseContainer.innerHTML = '';
        errorContainer.textContent = '';
        
        loadingIndicator.classList.remove('d-none');
        fetchButton.disabled = true;

        fetch(apiUrl, {
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                let items = Array.isArray(data) ? data : (Array.isArray(data.data) ? data.data : [data]);
                if (!items.length) {
                    responseContainer.innerHTML = '<div class="col-12"><p class="text-center text-muted mt-5">No data found.</p></div>';
                } else {
                    responseContainer.innerHTML = items.map(item => {
                        
                        const imagePath = item.image && !item.image.startsWith('http') && !item.image.startsWith('/') 
                            ? `https://tak22reiljan.itmajakas.ee/storage/${item.image}`
                            : item.image;
                            
                        return `<div class=\"col\"><div class=\"card monster-card h-100\">${
                            imagePath ? `<img src=\"${imagePath}\" class=\"card-img-top monster-image\" alt=\"${item.name || item.title || ''}\" style=\"height: 160px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;\">` : `<div class=\"card-img-top-placeholder\"><i class=\"bi bi-shield-shaded icon\"></i></div>`
                        }<div class=\"card-body\">
                            <h5 class=\"card-title\">${item.name || item.title || ''}</h5>
                            <div>
                                <h6 class=\"description-title\">Description</h6>
                                <p class=\"card-text\">${item.description || ''}</p>
                            </div>
                            ${item.teacher ? `<div class=\"mt-1\">
                                <h6 class=\"teacher-title\">Teacher</h6>
                                <p class=\"card-text mb-0\">${item.teacher}</p>
                            </div>` : ''}
                            ${item.credit_points ? `<div class=\"mt-1\">
                                <h6 class=\"credit-points-title\">Credit Points</h6>
                                <p class=\"card-text mb-0\">${item.credit_points}</p>
                            </div>` : ''}
                        </div></div></div>`;
                    }).join('');
                }
                responseContainer.classList.remove('d-none');
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                errorContainer.textContent = `Error fetching API: ${error.message}. Check the console for details.`;
            })
            .finally(() => {
                loadingIndicator.classList.add('d-none');
                fetchButton.disabled = false;
            });
    };
</script>
@endsection 