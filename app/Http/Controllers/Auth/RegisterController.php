<?php
// app/Http/Controllers/Auth/RegisterController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Penyewa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    protected string $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'no_hp' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // max 2MB
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('fotos', 'public');
            $data['foto'] = $path;
        } else {
            
            $data['foto'] = 'fotos/profile.png'; 

        }
        $data_user = array();

        $data_user['name'] = $data['name'];
        $data_user['email'] = $data['email'];
        $data_user['password'] = $data['password'];
        $data_user['foto'] = $data['foto'];
        $user = $this->create($data_user);
        // assign role

        // dd($user);

        if ($user) {
            $data_penyewa = array();
            $data_penyewa['nama'] = $data['name'];
            $data_penyewa['email'] = $data['email'];
            $data_penyewa['nohp'] = $data['no_hp'];
            $data_penyewa['alamat'] = $data['alamat'];
            $data_penyewa['jenis_kelamin'] = $data['jenis_kelamin'];
            $data_penyewa['foto'] = $data['foto'];
            $data_penyewa['user_id'] = $user['id'];
            $penyewa = Penyewa::create($data_penyewa);
            Auth::login($user);
        }
        return redirect($this->redirectPath());
    }


    protected function create(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'foto' => $data['foto']
        ]);

        // Assign default role (jika menggunakan Spatie Laravel Permission)
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('Penyewa');
        }

        return $user;
    }

    protected function redirectPath(): string
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/dashboard';
    }
}
