@extends('layouts.app')

@section('title', 'Stripe Debug')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h2 mb-0">Stripe Configuration Debug</h1>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Environment</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>APP_ENV:</span>
                            <span>{{ $output['env']['app_env'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>APP_DEBUG:</span>
                            <span>{{ $output['env']['app_debug'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Stripe Keys from Environment</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>STRIPE_KEY:</span>
                            <span class="{{ $output['stripe_keys']['env_key_exists'] ? 'text-success' : 'text-danger' }}">
                                {{ $output['stripe_keys']['env_key_exists'] ? $output['stripe_keys']['env_key_start'] . ' (length: ' . $output['stripe_keys']['env_key_length'] . ')' : 'Not Set' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>STRIPE_SECRET:</span>
                            <span class="{{ $output['stripe_keys']['env_secret_exists'] ? 'text-success' : 'text-danger' }}">
                                {{ $output['stripe_keys']['env_secret_exists'] ? $output['stripe_keys']['env_secret_start'] . ' (length: ' . $output['stripe_keys']['env_secret_length'] . ')' : 'Not Set' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Stripe Keys from Config</h2>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>services.stripe.key:</span>
                            <span class="{{ $output['config']['config_key_exists'] ? 'text-success' : 'text-danger' }}">
                                {{ $output['config']['config_key_exists'] ? $output['config']['config_key_start'] . ' (length: ' . $output['config']['config_key_length'] . ')' : 'Not Set' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>services.stripe.secret:</span>
                            <span class="{{ $output['config']['config_secret_exists'] ? 'text-success' : 'text-danger' }}">
                                {{ $output['config']['config_secret_exists'] ? $output['config']['config_secret_start'] . ' (length: ' . $output['config']['config_secret_length'] . ')' : 'Not Set' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">Stripe Connection Test</h2>
                </div>
                <div class="card-body">
                    <div class="alert {{ $output['stripe_test']['success'] ? 'alert-success' : 'alert-danger' }}">
                        <strong>{{ $output['stripe_test']['success'] ? 'Success' : 'Failed' }}:</strong> {{ $output['stripe_test']['message'] }}
                        @if($output['stripe_test']['success'])
                            <br>Account: {{ $output['stripe_test']['account_email'] }}
                        @endif
                    </div>
                    
                    @if(isset($output['stripe_test_config']))
                        <div class="alert {{ $output['stripe_test_config']['success'] ? 'alert-success' : 'alert-danger' }} mt-3">
                            <strong>{{ $output['stripe_test_config']['success'] ? 'Success' : 'Failed' }} (using config):</strong> {{ $output['stripe_test_config']['message'] }}
                            @if($output['stripe_test_config']['success'])
                                <br>Account: {{ $output['stripe_test_config']['account_email'] }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="alert alert-info">
                <p><strong>Note:</strong> Check the server logs for more detailed information.</p>
            </div>
        </div>
    </div>
</div>
@endsection
