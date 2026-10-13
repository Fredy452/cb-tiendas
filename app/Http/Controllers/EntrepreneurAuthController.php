<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class EntrepreneurAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('auth.login');
    }

    public function showPasswordResetRequest(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('auth.forgot-password');
    }

    public function sendPasswordResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
        ]);

        PasswordBroker::sendResetLink(['email' => Str::lower($request->input('email'))]);

        return back()->with('status', 'Si existe una cuenta asociada a ese correo, recibirás un enlace para restablecer tu contraseña.');
    }

    public function showPasswordResetForm(Request $request, string $token): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.mixed' => 'La contraseña debe incluir letras mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'password.symbols' => 'La contraseña debe incluir al menos un símbolo.',
            'password.uncompromised' => 'La contraseña es demasiado común. Elegí otra.',
        ]);
        $credentials['email'] = Str::lower($credentials['email']);

        $status = PasswordBroker::reset(
            $credentials,
            function (User $user, string $password): void {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return to_route('login')->with('status', 'Tu contraseña se restableció correctamente. Ya podés iniciar sesión.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'El enlace para restablecer la contraseña no es válido o venció. Solicitá uno nuevo.']);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser texto válido.',
        ]);

        $key = Str::lower($credentials['email']).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Demasiados intentos. Probá nuevamente en '.RateLimiter::availableIn($key).' segundos.',
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => 'El correo o la contraseña no son correctos.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        return $this->authenticatedRedirect($request->user());
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los :max caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.max' => 'El correo electrónico no puede superar los :max caracteres.',
            'email.unique' => 'Este correo ya está registrado. Probá iniciar sesión o recuperá tu contraseña.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.mixed' => 'La contraseña debe incluir letras mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',
            'password.symbols' => 'La contraseña debe incluir al menos un símbolo.',
            'password.uncompromised' => 'La contraseña es demasiado común. Elegí otra.',
        ]);

        $user = DB::transaction(function () use ($validated): User {
            $email = Str::lower($validated['email']);
            $user = User::query()->create([
                'name' => $validated['name'],
                'email' => $email,
                'password' => $validated['password'],
            ]);

            $user->assignRole(Role::findOrCreate('emprendedor', 'web'));

            Store::query()
                ->whereNull('user_id')
                ->whereRaw('LOWER(email) = ?', [$email])
                ->update(['user_id' => $user->getKey()]);

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return to_route('verification.notice');
    }

    public function verificationNotice(Request $request): View|RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return to_route('filament.admin.resources.stores.index');
        }

        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill();

        return to_route('filament.admin.resources.stores.index')
            ->with('success', 'Tu correo fue verificado correctamente.');
    }

    public function resendVerification(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return to_route('filament.admin.resources.stores.index');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Te enviamos un nuevo enlace de verificación.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }

    private function authenticatedRedirect(User $user): RedirectResponse
    {
        if (! $user->hasVerifiedEmail()) {
            return to_route('verification.notice');
        }

        return redirect()->intended(route('filament.admin.resources.stores.index'));
    }
}
