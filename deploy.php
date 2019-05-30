<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'deployer_test');

// Project repository
set('repository', 'https://github.com/NekouSama/front-test-deployer.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server
set('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts
host('51.77.200.226')
    ->user('root')
    ->roles('app')
    ->set('branch', 'master')
    ->set('deploy_path', '/var/www/deployer/public/{{application}}');

// Tasks

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    // 'deploy:vendors',
    'deploy:node_modules',
    'deploy:build',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success',
]);

desc('Installing node_modules');
task('deploy:node_modules', function () {
    run('cd {{release_path}} && npm install');
});

desc('Building application');
task('deploy:build', function () {
    run('cd {{release_path}} && ng build');
});

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
