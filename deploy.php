<?php
namespace Deployer;

require 'recipe/laravel.php';

// Config

set('repository', 'https://github.com/OlehKrupa/Binance.loc.git');

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

//Доступ до дроплета == SSH tunnel
host('example.org')
    ->set('remote_user', 'laravel')
    ->set('deploy_path', '~/example');

// Hooks

after('deploy:failed', 'deploy:unlock');
