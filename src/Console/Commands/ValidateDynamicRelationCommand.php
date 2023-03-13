<?php

namespace Gianfriaur\LaravelDynamicRelation\Console\Commands;

use Illuminate\Console\Command;

class ValidateDynamicRelationCommand extends Command
{
    protected $signature = 'dynamic-relation:validate';

    protected $description = 'This command make the validation of all dynamic relation';

    public function handle(): void
    {

    }
}