<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OldPermissionViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:add-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add -view to old permissions on permission reference.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $oldPermissions = DB::select("
            SELECT id,code FROM  permission_references
            WHERE 
            code NOT LIKE '%-view' AND 
            code NOT LIKE '%-add' AND 
            code NOT LIKE '%-update' AND 
            code NOT LIKE '%-delete' AND
            code NOT LIKE '%-edit' AND 
            code NOT LIKE '%-reprint' AND 
            code NOT LIKE '%-save'
        ");
        foreach ($oldPermissions as $permission) {
            $newPermission = $permission->code . "-view";
            DB::table('permission_references')
                ->where('id', $permission->id)
                ->update(['code' => $newPermission]);
            $this->info("$permission->code to $newPermission");
        }
    }
}