<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, EagerLoadPivotTrait, Searchable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
    ];

    public function searchableAs()
    {
        return 'users_index';
    }

    public function projects () {
        return $this->belongsToMany(Project::class, 'project_members')
                    ->withPivot('lead', 'is_starred')
                    ->wherePivot('user_id', auth()->id())
                    ->using(ProjectMember::class);
    }

    public function tasks () {
        return $this->belongsToMany(Task::class, 'task_assignments')
                    ->using(TaskAssignment::class);
    }

    public function skills () {
        return $this->belongsToMany(Skill::class, 'user_skills', 'user_id', 'skill_id')
                    ->withPivot('level', 'experience')
                    ->orderBy('experience', 'DESC')
                    ->using(UserSkill::class);
    }

    public function taskCreated () {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function leader () {
        return $this->hasMany(ProjectMember::class, 'lead', 'id');
    }

    public function scopeNotInProject($query, Project $project) {
        $memberIds = $project->members->map(function ($member) {
            return $member->id;
        });

        return $query->whereNotIn('id', $memberIds);
    }
}
