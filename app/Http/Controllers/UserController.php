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
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\TipoAnalista;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        $users = User::paginate(10);
        $tipos = TipoAnalista::all();
        $cursos = Curso::distinct()->orderBy('nome')->pluck('cod_curso', 'nome');
        $cotas = Cota::all();

        return view('user.todos', compact('users', 'tipos', 'cursos', 'cotas'));
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

        return redirect(route('users.index'))->with(['success' => 'Analista cadastrado com sucesso!']);
    }

    public function storeUser(UserStoreRequest $request)
    {
        $this->authorize('isAdmin', User::class);

        $validated = $request->validated();

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        // set role from validated data
        $user->role = $validated['role'];
        $user->email_verified_at = now();
        $user->primeiro_acesso = false;
        $user->save();

        if ($user->role == User::ROLE_ENUM['analista']) {
            if (!empty($validated['tipos_analista'] ?? [])) {
                foreach ($validated['tipos_analista'] as $tipo_id) {
                    $user->tipo_analista()->attach(TipoAnalista::find($tipo_id));
                }
            }

            if (!empty($validated['cursos_analista'] ?? [])) {
                foreach ($validated['cursos_analista'] as $cod_curso) {
                    $user->analistaCursos()->attach(Curso::where('cod_curso', $cod_curso)->first());
                }
            }
            if (!empty($validated['cotas_analista'] ?? [])) {
                $hasSpecial = $user->tipo_analista()->whereIn('tipo', [TipoAnalista::TIPO_ENUM['heteroidentificacao'], TipoAnalista::TIPO_ENUM['medico']])->exists();
                if (!$hasSpecial) {
                    foreach ($validated['cotas_analista'] as $cota_id) {
                        $user->analistaCotas()->attach(Cota::find($cota_id));
                    }
                }
            }
        }

        return redirect()->route('users.todos')->with(['success' => 'Usuário criado com sucesso!']);
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

    public function updateUser(UserUpdateRequest $request)
    {
        $this->authorize('isAdmin', User::class);
        $user = User::find($request->user_id);
        $validated = $request->validated();


        if (isset($validated['tipos_analista_edit'])) {
            $user->tipo_analista()->sync($validated['tipos_analista_edit'] ?? []);
        }

        if (isset($validated['cursos_analista_edit'])) {
            $cursos = Curso::whereIn('cod_curso', $validated['cursos_analista_edit'])->pluck('id');
            $user->analistaCursos()->sync($cursos);
        }

        if (isset($validated['cotas_analista_edit'])) {
            $hasSpecialTipo = $user->tipo_analista()->whereIn('tipo', [TipoAnalista::TIPO_ENUM['heteroidentificacao'], TipoAnalista::TIPO_ENUM['medico']])->exists();
            if (!$hasSpecialTipo) {
                $user->analistaCotas()->sync($validated['cotas_analista_edit'] ?? []);
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (isset($validated['role'])) {
            $user->role = $validated['role'];
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
