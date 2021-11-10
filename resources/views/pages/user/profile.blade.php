@extends('layouts.user')

@section('title')
  Profile - {{ auth()->user()->name }}
@endsection

@section('_content')
  @if ($errors->any())
    {{ $errors->first() }}
  @endif
  <div class="container mt-3">
    <div class="card">
      <div class="card-header">Change Profile</div>
      <div class="card-body">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="row">
            <div class="col-12 col-md-9">
              <div class="form-group">
                <label for='name'>Name</label>
                <input type='text' name='name' id='name' class='form-control' value="{{ auth()->user()->name }}" required>
              </div>
              <div class="form-group">
                <label for='email'>Email</label>
                <input type='email' name='email' id='email' class='form-control' value="{{ auth()->user()->email }}" required>
              </div>
              <div class="form-group">
                <label for='old_password'>Old Password</label>
                <input type='password' name='old_password' id='old_password' class='form-control'>
              </div>
              <div class="form-group">
                <label for='new_password'>New Password</label>
                <input type='password' name='new_password' id='new_password' class='form-control'>
              </div>
              <div class="form-group">
                <label for='new_password_confirmation'>Confirm New Password</label>
                <input type='password' name='new_password_confirmation' id='new_password_confirmation' class='form-control'>
              </div>
            </div>
            <div class="col-12 col-md-3">
              <label for="photo">Photo</label>
              @if (is_null(auth()->user()->photo))
                <p>No Photo</p>
              @else
                <img src="{{ asset('/storage/' . auth()->user()->photo) }}" alt="Profile picture" style="height: 15rem; width: 15rem;" class="rounded-circle">
              @endif
              <input type="file" name="photo" id="photo" class="form-control-file mt-3">
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            User's Skills
          </div>
          <div class="card-body pt-1">
            @forelse ($skills as $skill)
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <p class="lead mb-0">{{ $skill['name'] }}</p>
                  <p class="mb-0">{{ $skill['level_name'] }}</p>
                </div>
                <div class="progress">
                  <div class="progress-bar" role="progressbar" style="width: {{ ($skill['current_exp'] / $skill['max_exp']) * 100 }}%"></div>
                </div>
                <small class="d-block text-right">{{ $skill['current_exp'] }} / {{ $skill['max_exp'] }}</small>
              </div>
            @empty
              <p class="mt-3">You have no skills. Complete tasks to get skills.</p>
            @endforelse
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection