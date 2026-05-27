<?php
namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
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

  public function login() {
    $this->validate();

    $credentials = [
      'username' => $this->username,
      'password' => $this->password,
    ];

    if (Auth::attempt($credentials)) {
      session()->regenerate();

      $user = Auth::user();
      $role = $user->role;

      // Redirect berdasarkan role
      if ($role === 'kepala_gudang') {
        return redirect()->to('/kepala-gudang/dashboard');
      } elseif ($role === 'admin_fakturis') {
        return redirect()->to('/admin/dashboard');
      } elseif ($role === 'sales') {
        return redirect()->to('/sales/dashboard');
      } elseif ($role === 'pimpinan') {
        return redirect()->to('/pimpinan/dashboard');
      } else {
        return redirect()->to('/');
      }
    }

    // Login gagal
    $this->addError('username', 'Username atau password salah.');
    $this->password = '';
  }

  public function render(): View {
    return view('livewire.auth.login');
  }
}
