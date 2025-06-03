@extends('layouts.app')

@section('title', 'Weather - Hajusrakendused')

@section('content')
<div class="weather-container">
    <h1 class="weather-title">Weather</h1>
    
    <div class="weather-search">
        <input type="text" id="cityInput" placeholder="Enter city name" class="weather-input" onkeypress="handleKeyPress(event)" spellcheck="false">
        <button onclick="getWeather()" class="weather-button">Search</button>
    </div>
    
    <div id="weatherResult" class="weather-result">
        <div class="weather-card">
            <div class="weather-header">
                <h2 id="cityName"></h2>
                <img id="weatherIcon" class="weather-icon" src="" alt="Weather icon">
            </div>
            <p id="weatherDescription" class="weather-description"></p>
            <div class="weather-details">
                <div class="weather-detail">
                    <span>Temperature: <span id="temperature"></span>°C</span>
                </div>
                <div class="weather-detail">
                    <span>Wind Speed: <span id="windSpeed"></span> m/s</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.weather-container {
    padding: 2rem;
    max-width: 800px;
    margin: 0 auto;
}

.weather-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    background: linear-gradient(90deg, var(--text-color), var(--primary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.weather-search {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.weather-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: var(--secondary-bg);
    color: var(--text-color);
    font-size: 1rem;
    outline: none;
}

.weather-input:focus {
    border-color: var(--primary-color);
    box-shadow: none;
}

.weather-button {
    padding: 0.75rem 1.5rem;
    background: var(--primary-color);
    color: #000;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: opacity 0.2s;
}

.weather-button:hover {
    opacity: 0.9;
}

.weather-result {
    display: none;
}

.weather-card {
    background: var(--secondary-bg);
    border-radius: 16px;
    padding: 2rem;
    border: 1px solid var(--border-color);
}

.weather-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.weather-header h2 {
    font-size: 1.5rem;
    color: var(--text-color);
    margin: 0;
}

.weather-icon {
    width: 100px;
    height: 100px;
    object-fit: contain;
}

.weather-description {
    text-transform: capitalize;
    color: #999;
    margin-bottom: 1.5rem;
    font-size: 1.2rem;
}

.weather-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.weather-detail {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.1rem;
    color: var(--text-color);
}

.weather-detail span {
    font-weight: 600;
}

.weather-detail span span {
    color: var(--primary-color);
}
</style>

<script>
function handleKeyPress(event) {
    if (event.key === 'Enter') {
        getWeather();
    }
}

function getWeather() {
    const city = document.getElementById('cityInput').value;
    if (!city) return;

    fetch(`{{ route('weather.get') }}?city=${encodeURIComponent(city)}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('weatherResult').style.display = 'block';
            document.getElementById('cityName').textContent = data.city;
            document.getElementById('weatherDescription').textContent = data.description;
            document.getElementById('temperature').textContent = data.temperature;
            document.getElementById('windSpeed').textContent = data.windSpeed;
            document.getElementById('weatherIcon').src = `https://openweathermap.org/img/wn/${data.icon}@2x.png`;
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error retrieving weather data');
        });
}
</script>
@endsection 