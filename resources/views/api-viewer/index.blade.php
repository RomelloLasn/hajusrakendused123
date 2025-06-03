@extends('layouts.app')

@section('title', 'API Viewer')

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
    <h1 class="mb-4 text-center">API Viewer</h1>

    <div class="input-group mb-3">
        <input type="url" id="api-url" class="form-control form-control-lg" placeholder="Enter API URL (e.g., /api/monsters)" aria-label="API URL">
        <button class="btn btn-cyan" type="button" id="fetch-api-btn">
            Fetch Data
        </button>
    </div>

    <div class="input-group mb-3">
        <input type="text" id="api-token" class="form-control" placeholder="Auth Token" aria-label="API Token">
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
    document.getElementById('fetch-api-btn').addEventListener('click', function() {
        const apiUrlInput = document.getElementById('api-url');
        const apiTokenInput = document.getElementById('api-token');
        const responseContainer = document.getElementById('response-container');
        const errorContainer = document.getElementById('error-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const fetchButton = this;

        let apiUrl = apiUrlInput.value.trim();
        const token = apiTokenInput.value.trim();
        responseContainer.classList.add('d-none');
        responseContainer.innerHTML = '';
        errorContainer.textContent = '';

        if (!apiUrl) {
            errorContainer.textContent = 'Please enter an API URL.';
            return;
        }

        if (apiUrl.startsWith('/')) {
            apiUrl = window.location.origin + apiUrl;
        }

        loadingIndicator.classList.remove('d-none');
        fetchButton.disabled = true;

        const headers = {
            'Content-Type': 'application/json'
        };

        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        fetch(apiUrl, {
            headers: headers
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
                        return `<div class=\"col\"><div class=\"card monster-card h-100\">${
                            item.image ? `<img src=\"${item.image}\" class=\"card-img-top monster-image\" alt=\"${item.title || ''}\" style=\"height: 160px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;\">` : `<div class=\"card-img-top-placeholder\"><i class=\"bi bi-shield-shaded icon\"></i></div>`
                        }<div class=\"card-body\"><h5 class=\"card-title\">${item.title || ''}</h5><div><h6 class=\"description-title\">Description</h6><p class=\"card-text\">${item.description || ''}</p></div><div><h6 class=\"behavior-title\">Behavior</h6><p class=\"card-text\">${item.behavior || ''}</p></div>${item.habitat ? `<div class=\"mt-1\"><h6 class=\"habitat-title\">Habitat</h6><p class=\"card-text mb-0\">${item.habitat}</p></div>` : ''}</div></div></div>`;
                    }).join('');
                }
                responseContainer.classList.remove('d-none');
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                errorContainer.textContent = `Error fetching API: ${error.message}. Check the console for details. Make sure the URL is correct and the API allows requests from this origin (CORS).`;
            })
            .finally(() => {
                loadingIndicator.classList.add('d-none');
                fetchButton.disabled = false;
            });
    });
</script>
@endsection 