<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Curso;
use App\Models\Cota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Requests\UserRequest;
use App\Models\TipoAnalista;
use Exception;
use Illuminate\Support\Facades\DB;

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
        $tipos = TipoAnalista::all();
        $cursos = Curso::distinct()->orderBy('nome')->pluck('cod_curso', 'nome');
        $cotas = Cota::all();
        return view('user.index', compact('users', 'tipos', 'cursos', 'cotas'));
    }
    public function listarTodos()
    {
        $this->authorize('isAdmin', User::class);

        $usuarios = User::paginate(10);
        $tipos = TipoAnalista::all();
        $cursos = Curso::distinct()->orderBy('nome')->pluck('cod_curso', 'nome');
        $cotas = Cota::all();

        return view('user.todos', compact('usuarios', 'tipos', 'cursos', 'cotas'));
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
        foreach ($request->tipos_analista as $tipo_id) {
            $user->tipo_analista()->attach(TipoAnalista::find($tipo_id));
        }
        foreach ($request->cursos_analista as $cod_curso) {
            $user->analistaCursos()->attach(Curso::where('cod_curso', $cod_curso)->get());
        }
        if (!$user->tipo_analista()->whereIn('tipo', [TipoAnalista::TIPO_ENUM['heteroidentificacao'], TipoAnalista::TIPO_ENUM['medico']])->exists()) {
            $user->analistaCotas()->attach(Cota::find($request->cotas_analista));
        }

        return redirect(route('usuarios.index'))->with(['success' => 'Analista cadastrado com sucesso!']);
    }

    public function storeUser(Request $request)
    {
        $this->authorize('isAdmin', User::class);

        $validRoles = array_values(User::ROLE_ENUM);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in($validRoles)],
            'tipos_analista' => 'nullable|array',
            'tipos_analista.*' => 'exists:tipo_analistas,id',
            'cursos_analista' => 'nullable|array',
            'cursos_analista.*' => 'exists:cursos,cod_curso',
            'cotas_analista' => 'nullable|array',
            'cotas_analista.*' => 'exists:cotas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        // set role from request
        $user->role = $request->role;
        $user->email_verified_at = now();
        $user->primeiro_acesso = false;
        $user->save();

        if ($user->role == User::ROLE_ENUM['analista']) {
            if ($request->filled('tipos_analista')) {
                foreach ($request->tipos_analista as $tipo_id) {
                    $user->tipo_analista()->attach(TipoAnalista::find($tipo_id));
                }
            }

            if ($request->filled('cursos_analista')) {
                foreach ($request->cursos_analista as $cod_curso) {
                    $user->analistaCursos()->attach(Curso::where('cod_curso', $cod_curso)->first());
                }
            }

            if ($request->filled('cotas_analista')) {
                $hasSpecial = $user->tipo_analista()->whereIn('tipo', [TipoAnalista::TIPO_ENUM['heteroidentificacao'], TipoAnalista::TIPO_ENUM['medico']])->exists();
                if (!$hasSpecial) {
                    foreach ($request->cotas_analista as $cota_id) {
                        $user->analistaCotas()->attach(Cota::find($cota_id));
                    }
                }
            }
        }

        return redirect()->route('usuarios.todos')->with(['success' => 'Usuário criado com sucesso!']);
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
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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

        return redirect(route('logar'))->with(['success' => 'Primeiro acesso realizado! Digite o e-mail e senha para entrar agora.']);
    }

    public function updateAnalista(Request $request)
    {
        $user = User::find($request->user_id);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'tipos_analista_edit' => 'required',
            'cotas_analista_edit' => 'exists:cotas,id',
            'cursos_analista_edit' => 'required|exists:cursos,cod_curso',
        ]);

        $validator->sometimes('cotas_analista_edit', 'required', function ($input) {
            return in_array(TipoAnalista::TIPO_ENUM['geral'], $input->tipos_analista_edit);
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $user->tipo_analista()->sync($request->tipos_analista_edit);

        $cursos = Curso::whereIn('cod_curso', $request->cursos_analista_edit)->pluck('id');

        $user->analistaCursos()->sync($cursos);

        if (!$user->tipo_analista()->whereIn('tipo', [TipoAnalista::TIPO_ENUM['heteroidentificacao'], TipoAnalista::TIPO_ENUM['medico']])->exists()) {
            $user->analistaCotas()->sync($request->cotas_analista_edit);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->update();

        return redirect()->back()->with(['success' => 'Analista editado com sucesso']);
    }

    public function updateUser(Request $request)
    {
        $user = User::find($request->user_id);
        $validRoles = array_values(User::ROLE_ENUM);
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'tipos_analista_edit' => 'nullable|array',
            'tipos_analista_edit.*' => 'exists:tipo_analistas,id',
            'cotas_analista_edit' => 'nullable|array',
            'cotas_analista_edit.*' => 'exists:cotas,id',
            'cursos_analista_edit' => 'nullable|array',
            'cursos_analista_edit.*' => 'exists:cursos,cod_curso',
            'role' => ['nullable', Rule::in($validRoles)],
        ]);

        $validator->sometimes('cotas_analista_edit', 'required', function ($input) {
            return isset($input->tipos_analista_edit) && in_array(TipoAnalista::TIPO_ENUM['geral'], $input->tipos_analista_edit);
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        if ($request->has('tipos_analista_edit')) {
            $user->tipo_analista()->sync($request->tipos_analista_edit ?? []);
        }

        if ($request->has('cursos_analista_edit')) {
            $cursos = Curso::whereIn('cod_curso', $request->cursos_analista_edit)->pluck('id');
            $user->analistaCursos()->sync($cursos);
        }

        if ($request->has('cotas_analista_edit')) {
            $hasSpecialTipo = $user->tipo_analista()->whereIn('tipo', [TipoAnalista::TIPO_ENUM['heteroidentificacao'], TipoAnalista::TIPO_ENUM['medico']])->exists();
            if (!$hasSpecialTipo) {
                $user->analistaCotas()->sync($request->cotas_analista_edit ?? []);
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('role')) {
            $user->role = $request->role;
        }
        $user->update();

        return redirect()->back()->with(['success' => 'Usuário editado com sucesso']);
    }

    public function infoUser(Request $request)
    {
        $user = User::find($request->user_id);

        $userInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'cargos' => $user->tipo_analista,
            'cursos' => $user->analistaCursos,
            'cotas' => $user->analistaCotas,
        ];

        return response()->json($userInfo);
    }

    public function editUser(Request $request)
    {
        $user = User::find($request->user_id);

        $userInfo = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'cargos' => $user->tipo_analista,
            'cursos' => $user->analistaCursos,
            'cotas' => $user->analistaCotas,
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
        DB::beginTransaction();
        try {
            foreach ($user->tipo_analista()->get() as $tipo) {
                $tipo->pivot->delete();
            }

            $user->delete();
        } catch (Exception $e) {
            DB::rollBack();
            if ($e->getCode() == '23503') {
                return redirect()->back()->withErrors(['analista' => 'O analista não pode ser deletado pois possui avaliações.']);
            }
        }

        DB::commit();

        $message = 'Usuário deletado com sucesso!';
        if ($user && $user->role == User::ROLE_ENUM['analista']) {
            $message = 'Analista deletado com sucesso!';
        }
        else if ($user && $user->role == User::ROLE_ENUM['admin']) {
            $message = 'Administrador deletado com sucesso!';
        }

        return redirect()->back()->with(['success' => $message]);
    }
}
