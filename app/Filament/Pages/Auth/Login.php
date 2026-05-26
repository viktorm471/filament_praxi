<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseAuthLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;


class Login extends BaseAuthLogin
{
    public function authenticate(): ?LoginResponse
    {
        $data = $this->form->getState();
        // try {
        //     $response = Http::post('https://api.company.com/login', [
        //     'email' => $data['email'],
        //     'password' => $data['password'],
        // ]);
        // } catch (\Throwable $th) {
        //     $this->addError('email', 'Invalid credentials.');
        //     // dd($this->getErrorBag());

        //     Notification::make()
        //         ->danger()
        //         ->title('Something went wrong')
        //         ->body('Unable to authenticate at this time.')
        //         ->send();
        //     return null;    
        // }


        // if (! $response->successful() || true) {
        // if (true) {
        //     $this->addError('data.email', 'Invalid credentials.');

        //     return null;
        // }

        // $apiUser = $response->json();

        $user = User::updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => "generico",
                'active' => false,
            ]
        );

        if ($user->wasRecentlyCreated) {
            try {
                Notification::make()
                    ->title('New user pending approval')
                    ->body("{$user->name} needs approval.")
                    ->warning()
                    ->sendToDatabase(
                        User::where('is_admin', true)
                            ->where('active', true)
                            ->get(),
                        true
                    );
            } catch (\Throwable $th) {
                $this->addError('email', 'Invalid credentials.');
                // dd($this->getErrorBag());

                Notification::make()
                    ->danger()
                    ->title('Something went wrong')
                    ->body('No se pudo enviar la notificación de nuevo usuario.')
                    ->send();
                return null;
            }
        }

        if (! $user->active) {

            Notification::make()
                ->danger()
                ->title('Account pending approval')
                ->body('Your account is pending approval. Please wait for an administrator to activate your account.')
                ->send();

            return null;
        }

        Auth::login($user, remember: true);

        session()->regenerate();

        return app(LoginResponse::class);

        Auth::login($user, remember: true);

        session()->regenerate();

        return app(LoginResponse::class);
    }
}
