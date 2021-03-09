@extends('boilerplate::layout.index', [
    'title' => __('boilerplate::users.title'),
    'subtitle' => __('boilerplate::users.edit.title'),
    'breadcrumb' => [
        __('boilerplate::users.title') => 'boilerplate.users.index',
        __('boilerplate::users.edit.title')
    ]
])

@section('content')
    {{ Form::open(['route' => ['boilerplate.users.update', $user->id], 'method' => 'put', 'autocomplete' => 'off']) }}
        <div class="row">
            <div class="col-12 pb-3">
                <a href="{{ route("boilerplate.users.index") }}" class="btn btn-default" title="{{ __('boilerplate::users.returntolist') }}">
                    <span class="far fa-arrow-alt-circle-left text-muted"></span>
                </a>
                <span class="btn-group float-right">
                    <button type="submit" class="btn btn-primary">
                        {{ __('boilerplate::users.save') }}
                    </button>
                </span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                @component('boilerplate::card', ['title' => __('boilerplate::users.informations')])
                    @if(Auth::user()->id !== $user->id)
                        <div class="form-group">
                            {{ Form::label('active', __('boilerplate::users.status')) }}
                            {{ Form::select('active', ['0' => __('boilerplate::users.inactive'), '1' => __('boilerplate::users.active')], old('active', $user->active), ['class' => 'form-control'.$errors->first('active', ' is-invalid')]) }}
                            {!! $errors->first('active','<div class="error-bubble"><div>:message</div></div>') !!}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('last_name', __('boilerplate::users.lastname')) }}
                                {{ Form::text('last_name', old('last_name', $user->last_name), ['class' => 'form-control'.$errors->first('last_name', ' is-invalid'), 'autofocus']) }}
                                {!! $errors->first('last_name','<div class="error-bubble"><div>:message</div></div>') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {{ Form::label('first_name', __('boilerplate::users.firstname')) }}
                                {{ Form::text('first_name', old('first_name', $user->first_name), ['class' => 'form-control'.$errors->first('first_name', ' is-invalid')]) }}
                                {!! $errors->first('first_name','<div class="error-bubble"><div>:message</div></div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('email', __('boilerplate::users.email')) }}
                        {{ Form::email('email', old('email', $user->email), ['class' => 'form-control'.$errors->first('email', ' is-invalid')]) }}
                        {!! $errors->first('email','<div class="error-bubble"><div>:message</div></div>') !!}
                    </div>
                @endcomponent
            </div>
            <div class="col-md-6">
                @component('boilerplate::card', ['color' => 'teal', 'title' =>__('boilerplate::users.roles')])
                    <table class="table table-sm table-hover">
                        @foreach($roles as $role)
                            @if($role->name !== 'admin' || ($role->name === 'admin' && Auth::user()->hasRole('admin')))
                            <tr>
                                <td style="width:25px">
                                    <div class="icheck-primary">
                                    @if(Auth::user()->id === $user->id && $role->name === 'admin' && Auth::user()->hasRole('admin'))
                                        {{ Form::checkbox('roles['.$role->id.']', 1, old('roles['.$role->id.']', $user->hasRole($role->name)), ['id' => 'role_'.$role->id, 'class' => 'icheck', 'checked', 'disabled']) }}
                                        {!! Form::hidden('roles['.$role->id.']', '1', ['id' => 'role_'.$role->id]) !!}
                                    @else
                                        {{ Form::checkbox('roles['.$role->id.']', 1, old('roles['.$role->id.']', $user->hasRole($role->name)), ['id' => 'role_'.$role->id, 'class' => 'icheck']) }}
                                    @endif
                                        <label for="{{ 'role_'.$role->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    {{ Form::label('role_'.$role->id, $role->display_name, ['class' => 'mbn']) }}<br />
                                    <span class="small">{{ $role->description }}</span><br />
                                    <span class="small text-muted">{{ $role->permissions->implode('display_name', ', ') }}</span>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </table>
                @endcomponent
            </div>
        </div>
    {{ Form::close() }}
@endsection