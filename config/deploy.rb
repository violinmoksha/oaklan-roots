set :application, 'oaklandesigns'
set :repo_url, 'violinmoksha@gmail.com:violinmoksha/oaklan-roots.git'

set :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }

set :deploy_to, '/public_html/oaklandesigns'

set :log_level, :info
set :pty, true

# set :linked_dirs, %w{bin log tmp/pids tmp/cache tmp/sockets vendor/bundle public/system}

namespace :deploy do

  desc 'Restart application'
  task :restart do
    on roles(:app) do
      sudo 'service', 'apache2', 'restart'
    end
  end

  after :restart, :clear_cache do
    on roles(:web), in: :groups, limit: 3, wait: 10 do
      # Here we can do anything such as:
      # within release_path do
      #   execute :rake, 'cache:clear'
      # end
    end
  end

  after :finishing, 'deploy:cleanup'

end
