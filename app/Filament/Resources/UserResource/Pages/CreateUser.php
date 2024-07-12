<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
{
    return static::getResource()::getUrl('index');
}

protected function handleRecordCreation(array $data): Model
{
    $data['password'] = Hash::make($data['password']);
    $data['remember_token'] = Str::random(50);
    $user = static::getModel()::create($data);

 
    return $user;
}



}
