<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'git@github.com:OlehKrupa/Binance.loc.git'); 
set('branch', 'main');

add('shared_files', ['.env']);
add('shared_dirs', ['storage']);
add('writable_dirs', ['bootstrap/cache', 'storage', 'vendor']);

// Hosts

host('209.38.228.181') // Droplet IP
    ->set('remote_user', 'root') 
    ->set('deploy_path', '/var/www/binanceapi'); 

// Tasks

task('deploy:composer', function () {
    run('cd {{release_path}} && composer install --no-interaction --no-dev --prefer-dist');
});

task('deploy:artisan:migrate', function () {
    run('{{bin/php}} {{release_path}}/artisan migrate --force');
});

task('deploy:currency_fill', function () {
    run('{{bin/php}} {{release_path}}/artisan currency:fill');
});

task('deploy:run_seeder', function () {
    run('{{bin/php}} {{release_path}}/artisan db:seed');
});

task('deploy:cron:queue', function() {
    run('echo "* * * * * cd {{release_path}} && {{bin/php}} artisan queue:work --daemon --quiet --tries=3" | crontab -');
});

task('deploy:cron:schedule', function() {
    run('echo "* * * * * cd {{release_path}} && {{bin/php}} artisan schedule:work --daemon --quiet --tries=3" | crontab -');
});

task('deploy:start_stripe_listener', function () {
    run('stripe listen --forward-to 209.38.228.181:80/api/webhook');
});

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
    'deploy:currency_fill',
    'deploy:run_seeder',
    'deploy:symlink',
    'deploy:cron:queue',
    'deploy:cron:schedule',
    'deploy:start_stripe_listener',
    'deploy:unlock',
    'deploy:cleanup',
]);
