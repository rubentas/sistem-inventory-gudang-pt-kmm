<?php
namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class Login extends Component {
    public string $username = '';
    public string $password = '';

    protected array $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
    ];

    protected array $messages = [
        'username.required' => 'Username wajib diisi.',
        'password.required' => 'Password wajib diisi.',
    ];

    public function login(): void {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password])) {
            session()->regenerate();

            $role = Auth::user()->role;

            $redirectRoute = match ($role) {
                'kepala_gudang'  => route('kg.dashboard'),
                'admin_fakturis' => route('admin.dashboard'),
                'sales'          => route('sales.dashboard'),
                'pimpinan'       => route('pimpinan.dashboard'),
                default          => route('login'),
            };

            $this->redirect($redirectRoute, navigate: true);
        } else {
            $this->addError('username', 'Username atau password salah.');
            $this->password = '';
        }
    }

    public function render(): View {
        /** @var View $view */
        $view = view('livewire.auth.login');

        return $view->layout('layouts.guest');
    }
}
