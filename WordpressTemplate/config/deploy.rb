### This file contains project-specific settings ###
### See config/deploy/{stage}.rb for stage-specific settings ###

# The project name (something that capistrano can call this)
set :application, "__name__"
set :repository, "ssh://git@git.aristotle.net/__repo_name__"

#=========================================================
# The following usually doesn't change for our deployments
#=========================================================

# --------------------------------------------
# Server
# --------------------------------------------
set :use_sudo, true
role :web, "gaia.aristotle.net" #the webserver's hostname

# --------------------------------------------
# General
# --------------------------------------------
set :group_writable, true
set :keep_releases, 4 

# Multistage support - see config/deploy/[STAGE].rb for specific configs
set :default_stage, "production"
set :stages, %w(production staging)

# --------------------------------------------
# Repository
# --------------------------------------------
set :scm, :git
set :deploy_via, :remote_cache
set :copy_exclude, [".svn", ".git"]

# --------------------------------------------
# SSH
# --------------------------------------------
default_run_options[:pty] = true
ssh_options[:forward_agent] = false
ssh_options[:port] = 22

# --------------------------------------------
# Set up actions
# --------------------------------------------
after 'deploy:setup', 'wordpress:setup' # Here we setup the shared files directory
after 'deploy:update_code', 'aristotle:update_code'
after 'deploy:symlink', 'wordpress:symlink' # After symlinking the code we symlink the shared dirs
after 'deploy:finalize_update', 'aristotle:permissions:fix'

namespace :wordpress do
  task :setup, :except => { :no_release => true } do
    #create user if necessary
    run "#{sudo} id #{application} || #{sudo} /usr/sbin/useradd #{application} -d /dev/null -s /usr/bin/nologin"

    #create directories
    readable_dirs = [deploy_to, releases_path, shared_path]
    writable_dirs = [shared_path + "/uploads"]
    dirs = readable_dirs + writable_dirs
    dirs += %w(system).map { |d| File.join(shared_path, d) }
    run "#{sudo} mkdir -p #{dirs.join(' ')}"
    run "#{sudo} chmod -R 775 #{dirs.join(' ')}"
    run "#{sudo} chown -R #{application}:users #{readable_dirs.join(' ')}"
    run "#{sudo} chown -R #{application}:apache #{writable_dirs.join(' ')}"
  end

  task :symlink, :except => { :no_release => true } do
    # remove any link or directory that was exported from SCM, and link to remote Wordpress filesystem
    run "rm -rf #{release_path}/wordpress/wp-content/uploads"
    run "ln -fs #{deploy_to}/#{shared_dir}/uploads #{release_path}/wordpress/wp-content/uploads"
  end
end

namespace :aristotle do
  namespace :permissions do
    task :fix, :except => { :no_release => true } do
      run "#{sudo} chown -R #{application}:users #{latest_release}"
    end
  end
  task :update_code, :except => { :no_release => true } do
    # if cached-copy is not group writable after update, make it so. This allows all group users to deploy using git.
	# sudo access is necessary, but this should only happen on first deployment.
    run "if [ \"`ls -g #{shared_path} | grep ^.....-.*cached-copy`\" ]; then #{sudo} chmod -R g+w #{shared_path}/cached-copy; fi";
  end
end
