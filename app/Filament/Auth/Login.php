<?php

namespace App\Filament\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;

class Login extends \Filament\Auth\Pages\Login
{
    public int $captchaKey;

    public function mount(): void
    {
        parent::mount();

        $this->refreshCaptcha();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                View::make('filament.auth.captcha-image')
                    ->viewData(fn (): array => [
                        'captchaUrl' => captcha_src().'?'.$this->captchaKey,
                    ]),
                TextInput::make('captcha')
                    ->label('Captcha')
                    ->required()
                    ->rule('captcha')
                    ->validationMessages([
                        'captcha' => 'captcha salah',
                    ]),
                $this->getRememberFormComponent(),
            ]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (ValidationException $exception) {
            $this->refreshCaptcha();

            throw $exception;
        }
    }

    public function refreshCaptcha(): void
    {
        $this->captchaKey = random_int(1, 999999);
    }
}
