<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteAllUsers extends Command
{
    protected $signature = 'users:delete-all';
    protected $description = 'Delete all users from the users table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Logic to delete all users
        DB::table('doctors')->truncate();

        $this->info('All users deleted successfully.');
    }
}

