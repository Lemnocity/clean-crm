@extends('layouts.app')

@section('content')

<div class="container">
    <div class="mb-3">
        <label for="team-selector">Select a Team:</label>
        <select id="team-selector" class="form-control" onchange="window.location.href=this.value">
            @foreach($allTeams as $teamOption)
            <option value="{{ route('teams_permission.edit', $teamOption->team_id) }}" @if($teamOption->team_id == $team->id) selected @endif>
                {{ $teamOption->name }}
            </option>
            @endforeach
        </select>
    </div>

    <form action="{{ route('teams_permission.update', $team) }}" method="POST">
        @csrf
        <div class="row">
            @foreach($allPermissions as $permission)
            <div class="col-md-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="permissions[]" id="permission-{{ $permission->id }}" value="{{ $permission->id }}" @if($team->permissions->contains($permission)) checked @endif>
                    <label class="form-check-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                </div>
            </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Update Permissions</button>
    </form>
</div>
@endsection