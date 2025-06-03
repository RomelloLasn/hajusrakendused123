@extends('layouts.app')

@section('title', 'Home - Hajusrakendused')

@section('styles')
<style>
    .home-container {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        position: relative;
        background: transparent;
    }
    
    .home-background {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: -1;
    }
    
    .home-title {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 4rem;
        text-transform: uppercase;
        letter-spacing: 4px;
        color: #fff;
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
    }
    
    .home-subtitle {
        font-size: 1.5rem;
        margin-bottom: 4rem;
        color: #aaa;
        font-weight: 300;
    }
    
    .features-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 3rem;
    }
    
    @keyframes float {
        0% { transform: translateZ(0) translateY(0) rotate(0deg); }
        25% { transform: translateZ(100px) translateY(-20px) rotate(5deg); }
        50% { transform: translateZ(50px) translateY(0) rotate(0deg); }
        75% { transform: translateZ(120px) translateY(20px) rotate(-5deg); }
        100% { transform: translateZ(0) translateY(0) rotate(0deg); }
    }
    
    @keyframes pulse {
        0% { opacity: 0.5; transform: scale(0.8); }
        50% { opacity: 1; transform: scale(1.2); }
        100% { opacity: 0.5; transform: scale(0.8); }
    }
    
    @keyframes borderPulse {
        0% { border-color: rgba(255, 255, 255, 0.2); }
        50% { border-color: rgba(255, 255, 255, 0.8); }
        100% { border-color: rgba(255, 255, 255, 0.2); }
    }
    
    @keyframes rotate3D {
        0% { transform: rotateX(0deg) rotateY(0deg); }
        100% { transform: rotateX(360deg) rotateY(360deg); }
    }
    
    @keyframes electroGlow {
        0% { box-shadow: 0 0 30px 5px rgba(0, 200, 255, 0.5), 0 0 50px 10px rgba(0, 150, 255, 0.3), inset 0 0 20px rgba(0, 225, 255, 0.4); }
        25% { box-shadow: 0 0 40px 8px rgba(255, 0, 150, 0.5), 0 0 70px 15px rgba(255, 0, 100, 0.3), inset 0 0 30px rgba(255, 0, 175, 0.4); }
        50% { box-shadow: 0 0 30px 10px rgba(0, 255, 100, 0.5), 0 0 60px 15px rgba(0, 200, 80, 0.3), inset 0 0 25px rgba(0, 255, 120, 0.4); }
        75% { box-shadow: 0 0 35px 8px rgba(255, 200, 0, 0.5), 0 0 65px 15px rgba(255, 150, 0, 0.3), inset 0 0 30px rgba(255, 220, 0, 0.4); }
        100% { box-shadow: 0 0 30px 5px rgba(0, 200, 255, 0.5), 0 0 50px 10px rgba(0, 150, 255, 0.3), inset 0 0 20px rgba(0, 225, 255, 0.4); }
    }
    
    .feature-btn {
        position: relative;
        width: 280px;
        height: 280px;
        background: rgba(0, 200, 227, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }
    
    .feature-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        opacity: 0.4;
        transition: opacity 0.4s;
        z-index: 0;
        filter: blur(1px);
    }
    
    .feature-btn.weather::before {
        background-image: url('https://imgs.search.brave.com/b3Y24g5KSfdRxhFoibkqf5xmdeP7YSWTQSIDu0P22Lc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9wcmV2/aWV3LnJlZGQuaXQv/bGVicm9uLW15LW9u/bHktc3Vuc2hpbmUt/bGlrZS10aGUtc3Vu/LWhlcy1ob3QtdjAt/ZjdobnBleGllcnJj/MS5qcGVnP2F1dG89/d2VicCZzPWM4ZmNh/OThmODBmMjc5ZmY1/NTI1YWQyN2I5Mjcw/ZjRjMzAxZjdjMzk'), url('https://imgs.search.brave.com/b3Y24g5KSfdRxhFoibkqf5xmdeP7YSWTQSIDu0P22Lc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9wcmV2/aWV3LnJlZGQuaXQv/bGVicm9uLW15LW9u/bHktc3Vuc2hpbmUt/bGlrZS10aGUtc3Vu/LWhlcy1ob3QtdjAt/ZjdobnBleGllcnJj/MS5qcGVnP2F1dG89/d2VicCZzPWM4ZmNh/OThmODBmMjc5ZmY1/NTI1YWQyN2I5Mjcw/ZjRjMzAxZjdjMzk');
        background-blend-mode: overlay;
    }
    
    .feature-btn.maps::before {
        background-image: url('https://imgs.search.brave.com/r_YpSmuQl-RFXJ9fZy5IMUNKH-Wgca4j8dInA8XNGwY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnBp/bmltZy5jb20vb3Jp/Z2luYWxzLzBkLzMw/LzA2LzBkMzAwNmE3/Mzc1MmUyZDA0MGU5/Y2M1NDYwODg5NGY0/LmpwZw');
    }
    
    .feature-btn.blog::before {
        background-image: url('https://imgs.search.brave.com/I10KL_fcpEyJCRSeXwTwMFBlpscVELUQZhUKnorSATM/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnBp/bmltZy5jb20vb3Jp/Z2luYWxzL2Y1LzJm/LzNiL2Y1MmYzYjQw/ZDE4MWMyOTIxOTM3/M2NiNDk5ZDRjNDg5/LmpwZw');
    }
    
    .feature-btn.shop::before {
        background-image: url('https://imgs.search.brave.com/VnevXyhHS5OPkjvvT4gG2LOySbVGkpq9EanzWLZlOhw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnBp/bmltZy5jb20vb3Jp/Z2luYWxzLzQ2L2Ex/LzhlLzQ2YTE4ZWYw/MjU0ZThjOGZkNzll/ZDA4YzM5NzMzZDkz/LmpwZw');
    }
    
    .feature-btn.api::before {
        background-image: url('https://imgs.search.brave.com/BvzCzYI8fIwpLS9hIMQN2opu-Vu5r3St6VoUNrXlHDI/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnBp/bmltZy5jb20vb3Jp/Z2luYWxzLzUwL2Nm/L2MwLzUwY2ZjMDYw/M2ZhMjcyMjhlMTEx/MmVmODYwYTliZmYz/LmpwZw');
    }
    
    .feature-btn.api-viewer::before {
        background-image: url('https://imgs.search.brave.com/S5p17ObZVjX-uk9EbU5Ma7kjrMlRWgfSLDdEs418s_w/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnBp/bmltZy5jb20vb3Jp/Z2luYWxzL2MwL2Jk/LzdhL2MwYmQ3YWNk/Zjg5YTc0MTljYThm/MzE4NDYzOTJhMzVk/LmpwZw');
    }
    
    .feature-btn:hover::before {
        opacity: 0.6;
    }
    
    .feature-icon {
        font-size: 4.5rem;
        margin-bottom: 1.5rem;
        color: rgb(0, 200, 227);
        transition: all 0.4s ease;
        position: relative;
        z-index: 1;
    }
    
    .feature-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: rgb(0, 200, 227);
        transition: all 0.4s ease;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        z-index: 1;
    }
    
    .feature-btn:hover {
        background: rgba(0, 200, 227, 0.2);
        border-color: rgba(255, 255, 255, 0.4);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4);
    }
    
    .feature-btn:hover .feature-icon {
        transform: scale(1.2);
        color: #fff;
    }
    
    .feature-btn:hover .feature-title {
        color: #fff;
    }
    
    @media (max-width: 768px) {
        .features-container {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .feature-btn {
            width: 240px;
            height: 240px;
        }
        
        .home-title {
            font-size: 3rem;
        }
    }
</style>
@endsection

@section('content')
<div class="home-container">
    <div class="home-background"></div>
    <h1 class="home-title">Hajusrakendused</h1>
    
    <div class="features-container">
        <a href="{{ route('weather.index') }}" class="feature-btn weather">
            <i class="bi bi-cloud-sun-fill feature-icon"></i>
            <span class="feature-title">Weather</span>
        </a>
        
        <a href="{{ route('markers.index') }}" class="feature-btn maps">
            <i class="bi bi-map-fill feature-icon"></i>
            <span class="feature-title">Maps</span>
        </a>
        
        <a href="{{ route('blogs.index') }}" class="feature-btn blog">
            <i class="bi bi-journal-richtext feature-icon"></i>
            <span class="feature-title">Blog</span>
        </a>
        
        <a href="{{ route('products.index') }}" class="feature-btn shop">
            <i class="bi bi-shop feature-icon"></i>
            <span class="feature-title">Shop</span>
        </a>

        <a href="{{ route('monsters.index') }}" class="feature-btn api">
            <i class="bi bi-code-slash feature-icon"></i>
            <span class="feature-title">API</span>
        </a>
        
        <a href="{{ url('api-viewer') }}" class="feature-btn api-viewer">
            <i class="bi bi-eye-fill feature-icon"></i>
            <span class="feature-title">API Viewer</span>
        </a>
    </div>
</div>
@endsection 