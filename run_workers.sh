#!/bin/bash

# Запуск первой команды в новом screen сессии
sudo screen -dmS workerQ ./vendor/bin/sail artisan queue:work

# Запуск второй команды в новом screen сессии
sudo screen -dmS workerS ./vendor/bin/sail artisan schedule:work

# Вывод информации о процессах с ключевым словом "worker"
ps aux | grep -i worker

