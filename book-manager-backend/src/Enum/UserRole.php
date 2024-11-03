<?php
namespace App\Enum;

enum UserRole: String
{
    case READER = 'reader';
    case AUTHOR = 'author';
    case ADMIN = 'admin';
}