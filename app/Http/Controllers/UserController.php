<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', User::class);
        $users = User::where('role', User::ROLE_ENUM['analista'])->paginate(15);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('isAdmin', User::class);
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $user = new User();
        $user->setAtributes($request);
        $user->role = User::ROLE_ENUM['analista'];
        $user->email_verified_at = now();
        $user->primeiro_acesso = false;
        $user->save();

        return redirect(route('usuarios.index'))->with(['success' => 'Analista cadastrado com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => ['required','string','email','max:255','unique:users'],
            'password'         => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return redirect('editar')->withErrors($validator->errors())->withInput();
        }

        $user = User::find($request->id);
        $user->setAtributes($request);
        $user->primeiro_acesso = false;
        $user->update();

        return view('welcome');
    }

    public function updateAnalista(Request $request)
    {
        $user = User::find($request->user_id);
        $validator = Validator::make($request->all(),[
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','email','max:255','unique:users,email,'.$user->id,],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->update();

        return redirect()->back()->with(['success' => 'Analista editado com sucesso']);
    }

    public function infoUser(Request $request)
    {
        $user = User::find($request->user_id);

        $userInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ];

        return response()->json($userInfo);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin', User::class);
        $user = User::find($id);
        $user->delete();

        return redirect(route('usuarios.index'))->with(['success' => 'Analista deletado com sucesso!']);
    }
}
