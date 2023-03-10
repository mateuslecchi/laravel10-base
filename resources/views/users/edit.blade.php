<x-app-layout>
    @include('users.menu')
    @if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />
    <div class="card shadow rounded p-2">
        <div class="row">
            <div class="col d-flex justify-content-between">
                <div>
                    <p class="fs-5 mt-2">
                        Dados do Usuário:
                    </p>
                </div>

            </div>
            <form id="form" method="POST" action="{{ route('usuarios.update', ['usuario' => $usuario->id]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <x-input-group name="nome" type="text" required value="{{ $usuario->name }}">
                            Nome:
                        </x-input-group>
                    </div>
                    <div class="col-12 col-sm-6">
                        <x-input-group name="email" type="email" required value="{{ $usuario->email }}" disabled>
                            Email:
                            <x-slot name="muted">
                                Não é possível alterar.
                            </x-slot>
                        </x-input-group>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <x-input-group name="password" type="password">
                            Senha:
                            <x-slot name="muted">
                                Deixe em branco caso não for alterar.
                            </x-slot>
                        </x-input-group>
                    </div>
                    <div class="col-12 col-sm-6">
                        <x-input-group name="password_confirmation" type="password">
                            Confirmar Senha:
                            <x-slot name="muted">
                                Deixe em branco caso não for alterar.
                            </x-slot>
                        </x-input-group>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label for="role">Selecione as atribuições do usuário:</label>
                        @php
                        $i=0
                        @endphp

                        @foreach($user_roles as $role)
                        <div class="form-check">
                            <input id="{{ 'role'.$i }}" type="checkbox" name="role[]" value="{{ $role }}" checked
                                class="form-check-input">
                            <label for="{{ 'role'.$i }}" class="form-check-label">
                                {{ $role }}
                            </label>
                        </div>
                        @php
                        $i++
                        @endphp
                        @endforeach

                        @foreach($roles as $role)
                        <div class="form-check">
                            <input id="{{ 'role'.$i }}" type="checkbox" name="role[]" value="{{ $role->name }}"
                                class="form-check-input">
                            <label for="{{ 'role'.$i }}" class="form-check-label">
                                {{ $role->name }}
                            </label>
                        </div>
                        @php
                        $i++
                        @endphp
                        @endforeach
                    </div>
                    <div class="col-12 col-md-8">
                        <p>Atribuições:</p>
                        <ul>
                            <li><strong>Administrador:</strong> Acesso total ao sistema</li>
                            <li><strong>USUÁRIOS - CONSULTAR:</strong> Consultar USUÁRIOS do sistema cadastrados</li>
                            <li><strong>USUÁRIOS - EDITAR:</strong> Pode realizar cadastro, edição e exclusão de
                                USUÁRIOS do sistema</li>
                        </ul>
                    </div>
                </div>
            </form>
            <div class="d-flex justify-content-end">
                <div class="me-1">
                    <x-modal-delete-button target="deleteUser" />
                </div>
                <x-button-salvar />
            </div>
        </div>
</x-app-layout>

<x-modal-delete target="deleteUser" title="Deletar Usuário?"
    body="Você está prestes a deleter um usuário do sistema. Essa ação não pode ser revertida."
    action="{{ route('usuarios.destroy',['usuario' => $usuario->id])}}" />