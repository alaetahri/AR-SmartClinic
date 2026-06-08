<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'photo',
        'role',
    ];

    protected $hidden = [
    'password',
    'remember_token',
    ];

    // Un user peut être un patient
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    // Un user peut être un médecin
    public function medecin()
    {
        return $this->hasOne(Medecin::class);
    }

    // Un user a plusieurs notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Vérifier si l'utilisateur est admin
    public function estAdmin()
    {
        return $this->role === 'admin';
    }

    // Vérifier si l'utilisateur est médecin
    public function estMedecin()
    {
        return $this->role === 'medecin';
    }

    // Vérifier si l'utilisateur est patient
    public function estPatient()
    {
        return $this->role === 'patient';
    }

    // Vérifier si l'utilisateur est secrétaire
    public function estSecretaire()
    {
        return $this->role === 'secretaire';
    }
}