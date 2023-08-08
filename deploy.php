<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:OlehKrupa/Binance.loc.git'); 
set('branch', 'Release');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host('164.90.162.49') // Droplet IP
    ->user('remote_user','root') 
    ->set('deploy_path', '/var/www/binanceapi'); 

// Tasks

task('deploy:composer', function () {
    run('cd {{release_path}} && composer install --no-interaction --no-dev --prefer-dist');
});

task('deploy:artisan:migrate', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate --force');
});

//Currency fill

// Hooks

after('deploy:failed', 'deploy:unlock');

// Deployment

desc('Deploy your project');
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:composer',
    'deploy:artisan:migrate',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);
