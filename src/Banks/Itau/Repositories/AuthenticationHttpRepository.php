<?php

declare(strict_types=1);

namespace Banklink\Banks\Itau\Repositories;

use Banklink\Banks\Itau\Repositories\Contracts\AuthenticationRepository;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

final readonly class AuthenticationHttpRepository implements AuthenticationRepository
{
    public function __construct(private Factory|PendingRequest $http) {}

    public function initializeSession(): Response
    {
        return $this->http
            ->asForm()
            ->post('/router-app/router', [
                'portal' => '005',
                'pre-login' => 'pre-login',
                'tipoLogon' => '7',
                'usuario.agencia' => config()->get('banklink.banks.itau.agency'),
                'usuario.conta' => config()->get('banklink.banks.itau.account'),
                'usuario.dac' => config()->get('banklink.banks.itau.digit'),
                'destino' => '',
            ]);
    }

    public function loadSecurityChallenge(): void
    {
        $this->http
            ->withHeaders([
                'op' => session()->pull('security_challenge_operation'),
                'renderType' => 'parcialPage',
                'X-Auth-Token' => session()->get('auth_token'),
                'X-CLIENT-ID' => session()->get('client_id'),
                'X-FLOW-ID' => session()->get('flow_id'),
                'X-Requested-With' => 'XMLHttpRequest',
            ])
            ->post('/router-app/router');
    }

    public function confirmChallengeReady(): void
    {
        $this->http
            ->replaceHeaders([
                'op' => session()->pull('challenge_ready_operation'),
            ])
            ->post('/router-app/router');
    }

    public function fetchChallengeTokens(): string
    {
        return $this->http
            ->replaceHeaders([
                'op' => session()->pull('fetch_tokens_operation'),
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function executeSignCommand(): void
    {
        $this->http
            ->replaceHeaders([
                'op' => session()->get('sign_command_operation'),
            ])
            ->post('/router-app/router');
    }

    public function executeAntiPirateCommand(): void
    {
        $this->http
            ->replaceHeaders([
                'op' => session()->get('anti_pirate_operation'),
            ])
            ->post('/router-app/router');
    }

    public function fetchGuardianResponse(): string
    {
        return $this->http
            ->replaceHeaders([
                'op' => session()->get('guardian_callback_operation'),
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function loadITokenForm(): string
    {
        return $this->http
            ->replaceHeaders([
                'op' => session()->pull('itoken_form_operation'),
            ])
            ->post('/router-app/router')
            ->body();
    }

    public function submitIToken(string $token): void
    {
        $this->http
            ->replaceHeaders([
                'op' => session()->pull('submit_itoken_operation'),
            ])
            ->post('/router-app/router', [
                'token' => $token,
            ]);
    }

    public function loadPasswordForm(): void
    {
        $this->http
            ->replaceHeaders([
                'op' => session()->pull('password_form_operation'),
            ])
            ->post('/router-app/router');
    }

    public function submitPassword(): string
    {
        return $this->http
            ->replaceHeaders([
                'op' => session()->pull('submit_password_operation'),
            ])
            ->post('/router-app/router', [
                'senha' => session()->pull('letter_password'),
            ])
            ->body();
    }
}
