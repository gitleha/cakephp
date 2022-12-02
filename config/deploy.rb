# config valid for current version and patch releases of Capistrano
lock "~> 3.17.0"

set :application, "cakephp"
set :repo_url, "git@github.com:gitleha/cakephp.git"

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, "/var/www/html/cakephp"

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
append :linked_files, ".htaccess", "config/database.php", "config/core.php", "webroot/index.php", "webroot/.htaccess"

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for local_user is ENV['USER']
# set :local_user, -> { `git config user.name`.chomp }

# Default value for keep_releases is 5
set :keep_releases, 3

after :deploy, "deploy:chcon"

# Uncomment the following to require manually verifying the host key before first deploy.
# set :ssh_options, verify_host_key: :secure

namespace :deploy do
    task :chcon do
        on roles :all do
            execute :chmod, "-R 777 #{current_path}/tmp/*"
        end
    end

    task :sharedDirectory do
        on roles :all do
            execute "ln -sf #{fetch(:media_indemnites)} #{current_path}/tmp"
            execute "ln -sf #{fetch(:media_attachments)} #{current_path}/tmp"
        end
    end
end

after "deploy:finished", "deploy:sharedDirectory"