<?php
namespace App\Enum;

enum Status: string
{
    case Draft = 'Draft';
    case Published = 'Published';
    case Archived = 'Archived';
}
