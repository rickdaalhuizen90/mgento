# Mgento CLI
CLI tool for Magento 2

## Command Structure
```bash
src/Command
├── Composer
│   └── InstallCommand.php
├── Database
│   ├── ExportCommand.php
│   └── ImportCommand.php
├── Docker
│   ├── ExecCommand.php
│   ├── ListCommand.php
│   └── PurgeCommand.php
├── Git
│   ├── CheckoutCommand.php
│   ├── CommitCommand.php
│   ├── FinishCommand.php
│   ├── StartCommand.php
│   └── TagCommand.php
├── Log
│   ├── MutagenCommand.php
│   ├── MysqlCommand.php
│   └── PhpCommand.php
├── Magento
│   ├── GenerateCommand.php
│   └── InstallCommand.php
├── Rsync
│   ├── DownloadCommand.php
│   └── UploadCommand.php
└── Warden
    └── SetupCommand.php

8 directories, 19 files
```

# COMMANDS

## Prompt for warden:create commands
warden:setup (complete setup)
warden:setup env (env_local.php found, do you want to use this one instead?), (env.php found, are you sure you want to override it?)
warden:setup config
warden:setup ssl

## Database commands
db:import
db:export

## Only for users in ~/.ssh/authorized
rsync:download db example.sql (autocomplete from /mnt/bigstorage/db_backups)
rsync:upload db example.sql (upload to /mnt/bigstorage/db_backups)

## Magento commands
magento:install (prompt)
magento:generate module
magento:generate theme

## Composer commands
composer:install

## Log commands
log:php -f(follow)
log:mysql
log:mutagen

## Docker commands
docker:exec php ls -al (run command in container)
docker:exec mysql SELECT * FROM core_config_data
docker:list
docker:purge burton (purge current project, no argument is system purge)

## Wrapper for gitflow
git:start feature FOO-123
git:commit feature FOO-123 (prompt, message in webfant format, push, pr)
git:finish feature FOO-123 (merge branch in develop or master, delete branch)
git:checkout develop (fetch, pull)
git:tag v1.0 (update composer version, update CHANGELOG.md, create tag, push tag)

Prompt warden setup:
1. Enter project name
2. Create new env.php [Y/n]
3. Install composer [Y/n]
4. Download database [Y/n]
    - No database has been found, do you want to create a database dump?
5. Start Warden [Y/n]
6. Stop running containers [Y/n]
7. Start containers [Y/n]
8. Create new SSL-certificate:
9. Setup Magento [Y/n]
